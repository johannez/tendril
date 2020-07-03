<?php

namespace Tendril;

use \Timber\Menu;

use Tendril\PostTypes\PostType;

use Tendril\Traits\Menu as MenuTrait;

class Site extends \Timber\Site
{
    use MenuTrait;

    protected $post_types = [];

    public function __construct() 
    {
        parent::__construct();
    }

    public function init()
    {
        add_action('after_setup_theme', [$this, 'themeSupports']);

        add_action('init', function() {
          if (class_exists('ACF')) {
            \acf_add_options_page();
          }
        });

        add_action('acf/init', [$this, 'registerBlocks']);

        // Remove link to default post type from menu.
        add_action('admin_menu', function() {
            remove_menu_page( 'edit.php' );
        });


        add_action('wp_enqueue_scripts', function() {

            wp_enqueue_style('vendor_css', get_template_directory_uri() .  '/public/css/vendor.css');
            wp_enqueue_style('app_css', get_template_directory_uri() .  '/public/css/app.css');

            // Load main script with PHP variables.
            wp_register_script('vendor_js', get_template_directory_uri() .  '/public/js/vendor.js' );
            wp_register_script('app_js', get_template_directory_uri() .  '/public/js/app.js' );
            wp_localize_script('app_js', 'Wordpress', $this->getJsVars());
            wp_enqueue_script('app_js', '', [], false, true);
        });

        add_filter('timber/context', [$this, 'addToContext']);
        add_filter('timber/twig', [$this, 'addToTwig']);
        add_filter('allowed_block_types', [$this, 'allowedBlocks']);
        
        // Disable Wordpress threshold for large images.
        add_filter( 'big_image_size_threshold', '__return_false' );

        // Allow SVG file uploads.
        add_filter('upload_mimes', function($upload_mimes) {
            $upload_mimes['svg'] = 'image/svg+xml'; 
            $upload_mimes['svgz'] = 'image/svg+xml'; 
            return $upload_mimes; 
        }, 10, 1 );

        $this->addImageSizes();
    }

    /**
    * Sent Javascript variables to the front end.
    * @param Controller $controller
    */
    public function getJsVars()
    {
        return [];
    }

    /**
     * Get brand colors for the Wordpress backend.
     */
    public function getColors()
    {
      return [];
    }

    /**
     * Make all links in the text relative to the site,
     * if they match the pattern.
     */
    public function relativeLinks($text) 
    {
        // $targets = [
        //   'https://live-NAME.pantheonsite.io',
        //   'https://test-NAME.pantheonsite.io',
        //   'https://dev-NAME.pantheonsite.io',
        //   'https://NAME.lndo.site',
        // ];

        // $text = str_replace($targets, '', $text);

        return $text;
    }

    /**
    * Add a new post type to the site
    * @param PostType $post_type
    */
    public function registerPostType(PostType $post_type)
    {
        add_action('init', function() use($post_type) {
            $post_type->register();
            $post_type->addShortCodes();
        });

        array_push($this->post_types, $post_type);
    }

    /**
     * Register custom Gutenberg blocks.
     */
    public function registerBlocks()
    {
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
    }

    /**
     * Get a specific controller object by its label
     *
     * @param label - Short name of the controller
     */
    public function getController($label)
    {
        $controller = null;

        foreach($this->controllers as $con) {
            if ($con->label() == $lable) {
                $controller = $con;
                break;
            }
        }

        return $controller;
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function addToContext($context) 
    {
        $context['menu']  = new Menu();
        $context['site']  = $this;

        $context['options'] = get_fields('option');

        return $context;
    }

    public function themeSupports() 
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

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

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support( 'menus' );
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

    /** 
     * This is where you can add your own functions to twig.
     * @param string $twig get extension.
     */
    public function addToTwig($twig) 
    {
        // $twig->addExtension( new \Twig\Extension\StringLoaderExtension() );
        $twig->addFunction(new \Twig\TwigFunction('get_blocks', ['Tendril\Blocks\Block', 'getBlocks']));
        $twig->addFilter( new \Twig\TwigFilter( 'relative_links', [$this, 'relativeLinks'] ) );
        return $twig;
    }
}
