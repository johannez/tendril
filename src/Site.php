<?php

namespace Tendril;

use Timber\Menu;
use Timber\Site as TimberSite;

class Site extends TimberSite
{

    public function __construct() 
    {
        parent::__construct();

        add_action('after_setup_theme', [$this, 'themeSupports']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

        add_filter('timber/context', [$this, 'addToContext']);
        add_filter('timber/twig', [$this, 'addToTwig']);
        add_filter('render_block', [$this, 'renderBlock'], 10, 2);

        $this->addImageSizes();
    }

    /**
    * Enqueue scripts and stylesheets.
    */
    public function enqueueScripts()
    {
        wp_enqueue_style('vendor_css', get_template_directory_uri() .  '/public/css/vendor.css');
        wp_enqueue_style('app_css', get_template_directory_uri() .  '/public/css/app.css');

        // Load main script with PHP variables.
        wp_register_script('vendor_js', get_template_directory_uri() .  '/public/js/vendor.js' );
        wp_register_script('app_js', get_template_directory_uri() .  '/public/js/app.js' );
        wp_localize_script('app_js', 'Wordpress', $this->getJsVars());
        wp_enqueue_script('app_js', '', [], false, true);
    }

    /**
    * Sent Javascript variables to the front end.
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

    public function renderBlock($block_content, $block)
    {
        if (stristr($block['blockName'], 'acf')) {
            return $block_content;
        }
        else if (stristr($block['blockName'], 'core') && $block['innerHTML']) {
            $output = $block_content;
            
            if ($this->templateExists('block/core.twig')) {
                $context = Timber::context();

                $classes = [
                    'block',
                    'block--' . sanitize_title($block['blockName'])
                ];

                $context['block'] = [
                    'name' => $block['blockName'],
                    'classes' => $classes,
                    'attributes' => $block['attrs'],
                    'content' => $block['innerHTML']
                ];

                $output = Timber::compile('block/core.twig', $context);
            }

            return $output;
        }
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
    // public function allowedBlocks()
    // {
    //     // $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    //     // ddd($registered_blocks);

    //     return [
    //         'core/paragraph',
    //         'core/table',
    //         'core/image',
    //         'core/shortcode',
    //         'core/heading',
    //         'core/quote',
    //         'core/list',
    //         'core/separator',
    //         'core/button',
    //         'core/html',
    //         // 'acf/two-columns'
    //     ];
    // }

    /** 
     * This is where you can add your own functions to twig.
     * @param string $twig get extension.
     */
    public function addToTwig($twig) 
    {
        // $twig->addExtension( new \Twig\Extension\StringLoaderExtension() );
        $twig->addFunction(new \Twig\TwigFunction('get_blocks', [$this, 'getBlocks']));
        $twig->addFilter( new \Twig\TwigFilter( 'relative_links', [$this, 'relativeLinks'] ) );
        return $twig;
    }

    /**
     * Get properly formatted Gutenberg blocks
     *
     * @param $conten Post content
     */
    public static function getBlocks($content)
    {   
        $blocks = [];
        $blocks_raw = parse_blocks($content);

        foreach ($blocks_raw as $block) {
            if (!empty($block['blockName'])) {
                $classes = [
                    'block',
                    'block--' . sanitize_title($block['blockName'])
                ];
                $content = render_block($block);

                if (count($block['attrs'])) {
                    foreach ($block['attrs'] as $key => $value) {
                        if ($key == 'className') {
                            $classes[] = $value;
                        }
                    }
                }

                $b = [
                    'name' => $block['blockName'],
                    'classes' => $classes,
                    'attributes' => $block['attrs'],
                    'content' => $content
                ];

                $blocks[] = $b;
            }
        }

        return $blocks;
    }
}
