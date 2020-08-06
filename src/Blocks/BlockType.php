<?php
/**
 * Base class for all controllers.
 */

namespace Tendril\Blocks;

use \Timber\Timber;

abstract class BlockType
{
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
        $tendril_block = new Block($block);
        $context = Timber::context();
        $context['block'] = $tendril_block;
        

        Timber::render('block/two-column.twig', $context);
    }

    public function enqueueAssets()
    {
        
    }
}
