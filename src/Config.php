<?php

namespace Tendril;

use Tendril\PostTypes\PostType;
use Tendril\Blocks\BlockType;

class Config
{
    protected $post_types = [];
    protected $block_types = [];

    public function __construct() 
    {
        // Remove link to default post type from menu.
        add_action('admin_menu', function() {
            remove_menu_page( 'edit.php' );
        });

        // Initiliazed ACF options page.
        add_action('init', function() {
            if (class_exists('ACF')) {
                \acf_add_options_page();
            }
        });

        // Disable Wordpress threshold for large images.
        add_filter( 'big_image_size_threshold', '__return_false' );

        // Allow SVG file uploads.
        add_filter('upload_mimes', function($upload_mimes) {
            $upload_mimes['svg'] = 'image/svg+xml'; 
            $upload_mimes['svgz'] = 'image/svg+xml'; 
            return $upload_mimes; 
        }, 10, 1 );
    }

    /**
    * Add a new post type to the site
    * @param PostType $post_type
    */
    public function registerPostType(PostType $post_type)
    {
        add_action('init', function() use ($post_type) {
            $post_type->register();
        });

        array_push($this->post_types, $post_type);
    }

    /**
     * Register custom Gutenberg blocks.
     */
    public function registerBlock(BlockType $block_type)
    {
        add_action('acf/init', [$block_type, 'register']);

        array_push($this->block_types, $block_type);
    }
}
