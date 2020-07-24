<?php
/**
 * Base class for all migrations.
 */

namespace Tendril\Migrations;

require_once( ABSPATH . 'wp-admin/includes/image.php' );

abstract class Migration
{
    protected $posts_mapping_table = 'wp_migration_posts_mapping';
    protected $terms_mapping_table = 'wp_migration_terms_mapping';

    /**
    * Identify yourself.
    */
    abstract public function id();

    /**
    * Migrate the content into Wordpress.
    */
    abstract public function import();

  
    public function importImage($source_path, $pid)
    {
        global $wpdb;

        if (trim($source_path)) {
            $upload_directory = wp_upload_dir();
            $filename = basename($source_path);
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
                        'post_title'     => $filename,
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ];

                    $image_id = wp_insert_attachment($image, $dest_path, $pid);

                    $image_data = wp_generate_attachment_metadata( $image_id, $dest_path );
                    wp_update_attachment_metadata( $image_id, $image_data );
                    update_post_meta($image_id, '_wp_attachment_image_alt', $filename);
                }
                else {
                    error_log("Migration::importImage(): Source file $source_path doesn't exist");
                }
            }
        
            
            return $image_id;
        }
    }

    public function getExistingPost($source_id)
    {
        global $wpdb;

        $query_existing = "SELECT post_id
            FROM {$this->mapping_table} mapping
            WHERE source_id = '{$source_id}'";

        return $wpdb->get_var($query_existing);
    }

    public function insertPostMapping($source_id, $post_id, $post_type)
    {
        global $wpdb;

        $wpdb->insert($this->mapping_table, [
            'source_id' => $source_id,
            'post_id' => $post_id,
            'post_type' => $post_type
        ]);
    }

    public function getExistingTerm($source_id)
    {
        global $wpdb;

        $query_existing = "SELECT term_id
            FROM {$this->terms_mapping_table} mapping
            WHERE source_id = '{$source_id}'";

        return $wpdb->get_var($query_existing);
    }

    public function insertTermMapping($source_id, $term_id, $taxonomy)
    {
        global $wpdb;

        $wpdb->insert($this->terms_mapping_table, [
            'source_id' => $source_id,
            'term_id' => $term_id,
            'taxonomy' => $taxonomy
        ]);
    }
}
