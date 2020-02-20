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
}
