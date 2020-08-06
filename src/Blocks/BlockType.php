<?php
/**
 * Base class for all controllers.
 */

namespace Tendril\Blocks;

use Timber\Timber;
use Tendril\Traits\TimberHelper;

abstract class BlockType
{
    use TimberHelper;

    /**
    * Get the name.
    */
    abstract public function name();
    abstract public function icon();

    public function title()
    {
        return __(ucwords(str_replace(['-', '_'], ' ', $this->name())));
    }

    /**
    * Register the block type.
    */
    public function register() 
    {
        acf_register_block_type([
            'name'              => $this->name(),
            'title'             => $this->title(),
            'render_callback'   => [$this, 'render'],
            'category'          => 'common',
            'icon'              => $this->icon(),
            'enqueue_assets'    => [$this, 'enqueueAssets'],
            'supports' => [
                'align' => false
            ]
        ]);
    }

    public function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $template = 'block/' . str_replace('_', '-', $this->name()) . '.twig';

        if ($this->templateExists($template)) {
            $tendril_block = new Block($block);
            $context = Timber::context();
            $context['block'] = $tendril_block;
            
            $classes = [
                'block',
                'block--' . sanitize_title($block['name'])
            ];

            if (isset($block['className'])) {
                $classes[] = $block['className'];
            }

            $context['block']->classes = $classes;

            Timber::render($template, $context);
        }
        else {
            print "<p>Twig template $template doesn't exist</p>";
        }
    }

    public function enqueueAssets() {}
}
