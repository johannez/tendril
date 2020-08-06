<?php

namespace Tendril\Traits;

use Timber\Timber;

Trait TimberHelper
{
    /**
     * Check, if template exists.
     *
     * @param $template    Full template path string.
     */
    public function templateExists($template)
    {
        $exists = false;

        $directories = [];

        // Add current theme directories first.
        if (is_array(Timber::$dirname)) {
            foreach (Timber::$dirname as $dir_name) {
                $directories[] = get_template_directory() . '/' . $dirname;
            }
        }
        else {
            $directories[] = get_template_directory() . '/' . Timber::$dirname;
        }

        // Add custom locations.
        if (Timber::$locations) {
            $directories = array_merge($directories, Timber::$locations);
        }
        
        foreach ($directories as $dir) {
            $template_path = $dir . '/' . $template;
            if (file_exists($template_path)) {
                $exists = true;
                break;
            }
        }
        
        return $exists;
    }
}
