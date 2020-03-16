<?php

namespace Tendril\Traits;

Trait Migration
{
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
     * Get existing post by legacy id.
     *
     * @param $source_id    Legacy id.
     * @param $field        Name of the legacy field in the database.
     */
    public function getExistingPost($source_id, $field = 'source_id')
    {
        global $wpdb;

        $posts_table = $wpdb->prefix . 'posts';
        $meta_table = $wpdb->prefix . 'postmeta';

        $query_existing = "SELECT post_id
            FROM {$meta_table} meta
            JOIN {$posts_table} posts ON posts.ID = meta.post_id
            WHERE posts.post_type = '{$post_type}' AND meta.meta_key = '{$field}' AND meta.meta_value = {$source_id} 
            AND posts.post_status = 'publish'";

        return $wpdb->get_var($query_existing);
    }
}
