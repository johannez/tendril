<?php
/**
 * Base class for all migrations.
 */

namespace Tendril\Migrations;

require_once( ABSPATH . 'wp-admin/includes/image.php' );

abstract class Migration
{
    /**
     * Database table name to read and create mappings.
     */
    protected $mapping_table;

    /**
     * Name of base post type (e.g. post, term, user, comment, etc.)
     */
    protected $entity;


    public function __construct($entity)
    {
        global $wpdb;
        $this->entity = strtolower($entity);
        $this->mapping_table = $wpdb->prefix . "migration_{$this->entity}_mapping";
    }

    /**
    * Migrate the content into Wordpress.
    */
    abstract public function import();

    /**
     * Return the entity id of an existing mapping.
     *
     * @param source_id Source id from legacy system.
     * 
     * @return entity id (e.g. post id, term id, etc.)
     */
    public function getMapping($source_id)
    {
        global $wpdb;

        return $wpdb->get_var("
                SELECT entity_id
                FROM {$this->mapping_table} mapping
                WHERE source_id = '{$source_id}'
            ");
    }

    /**
     * Insert a mapping after a successful import.
     *
     * @param source_id Source id from legacy system.
     * @param entity_id Entity id from Wordpress (e.g. post id).
     * @param entity_type Entity type, if applicable (e.g. name of the custom post type, taxonomy name, etc).
     */
    public function insertMapping($source_id, $entity_id, $entity_type = '')
    {
        global $wpdb;

        $wpdb->insert($this->mapping_table, [
            'source_id' => $source_id,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type ? strtolower($entity_type) : $this->entity
        ]);
    }

    /**
    * Load CSV file into an array
    *
    * @param file_path System path to CSV file.
    *
    * @return array CSV data.
    */
    public function loadCsvFile($file_path)
    {
        $data = [];

        if (file_exists($file_path)) {
            $data = array_map('str_getcsv', file($file_path));

            array_walk($data, function(&$row) use ($data) {
                $row = array_combine($data[0], $row);
            });

            array_shift($data);
        }
        else {
            \WP_CLI::error("Import file ($file_path) does not exist.");
        }

        return $data;
    }

    /**
     * Import an image into Wordpress.
     * This assume that all images are in an import folder. And then
     * filename is then used to copy the file over to the wp_uploads folder
     * to be added to the media libray. After that it's attached to an entity
     *
     * @param source_path The path to the original image file.
     * @param entity_id Entity id from Wordpress (e.g. post id).
     */
    public function importImage($source_path, $pid = 0, $alt = '')
    {
        global $wpdb;

        if (trim($source_path)) {
            $upload_directory = wp_upload_dir();
            $filename = esc_sql(basename($source_path));
            $dest_url = $upload_directory['url'] . '/' . $filename;

            // Check, if image already exists in the system.            
            $image_id = $wpdb->get_var("SELECT ID 
                                FROM {$wpdb->posts} 
                                WHERE guid = '$dest_url'");


            if (!$image_id) {
                // Image doesn't exist. Copy it over from the import folder and 
                // add it to the media library.
                $dest_path = $upload_directory['path'] . '/' . $filename;

                if (file_exists($source_path)) {
                    // Copy the source file to the new destination.
                    copy($source_path, $dest_path);

                    $file_type = wp_check_filetype($source_path);

                    $image = [
                        'guid' => $upload_directory['url'] . '/' . $filename,
                        'post_mime_type' => $file_type['type'],
                        'post_title'     => $alt ? $alt : $filename,
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ];

                    $image_id = wp_insert_attachment($image, $dest_path, $pid);

                    $image_data = wp_generate_attachment_metadata( $image_id, $dest_path );
                    wp_update_attachment_metadata( $image_id, $image_data );
                    update_post_meta($image_id, '_wp_attachment_image_alt', $alt ? $alt : $filename);
                }
                else {
                    error_log("Migration::importImage(): Source file $source_path doesn't exist");
                }
            }
        
            
            return $image_id;
        }
    }
}
