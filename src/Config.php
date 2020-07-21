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


        // Adjust ACF json path to use this plugin instead of theme.
        add_filter('acf/settings/save_json', function($path) {
            return plugin_dir_path( __FILE__ ) . '/acf-json';
        });
         
        add_filter('acf/settings/load_json', function($path) {
            return [plugin_dir_path( __FILE__ ) . '/acf-json'];
        });

        // add_action('acf/init', [$this, 'registerBlocks']);


        // Disable Custom Colors
        add_theme_support('disable-custom-colors');

        // Load brand colors.
        if ($colors = $this->getColors()) {
            $colors_formatted = [];
            foreach ($colors as $name => $code) {
                $colors_formatted[] = [
                    'name' => $name,
                    'slug' => sanitize_title($name),
                    'color' => $code
                ];
            }

            add_theme_support('editor-color-palette', $colors_formatted);
        }

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
     * Get brand colors for the Wordpress backend.
     */
    public function getColors()
    {
      return [];
    }

    /**
    * Add a new post type to the site
    * @param PostType $post_type
    */
    public function registerPostType(PostType $post_type)
    {
        add_action('init', function() use ($post_type) {
            $post_type->register();
            // $post_type->addShortCodes();
        });

        array_push($this->post_types, $post_type);
    }

    /**
     * Register custom Gutenberg blocks.
     */
    public function registerBlock(BlockType $block_type)
    {
        add_action('acf/init', [$block_type, 'register']);
        // acf_register_block_type([
        //     'name'              => 'two-columns',
        //     'title'             => __('Two Columns'),
        //     'description'       => __('Two columns layout.'),
        //     'render_template'   => 'block.php',
        //     'category'          => 'layout',
        //     'icon'              => 'schedule',
        //     'supports' => [
        //         'align' => false
        //     ]
        // ]);

        array_push($this->block_types, $block_type);
    }


    /**
     * Set allowed Gutenberg blocks
     */
    public function allowedBlocks()
    {
        // $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
        // ddd($registered_blocks);

        return [
            'core/paragraph',
            'core/table',
            'core/image',
            'core/shortcode',
            'core/heading',
            'core/quote',
            'core/list',
            'core/separator',
            'core/button',
            'core/html',
            // 'acf/two-columns'
        ];
    }
}
