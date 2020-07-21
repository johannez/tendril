<?php
/**
 * Base class for all controllers.
 */

namespace Tendril\Blocks;

use \Timber;

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
        print 'You have to override the render function for the ' . $this->title() . ' block.';
    }

    public function enqueueAssets()
    {
        
    }
}
