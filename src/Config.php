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

        // Wrap core blocks into something useful and remove empyt blocks.
        add_filter('render_block', function($block_content, $block) {
            
            if (stristr($block['blockName'], 'acf')) {
                return $block_content;
            }
            else if (stristr($block['blockName'], 'core') && $block['innerHTML']) {
                return '<div class="block block--' . sanitize_title($block['blockName']) . '">'
                         . $block_content . '</div>';
            }
        }, 10, 2 );

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
