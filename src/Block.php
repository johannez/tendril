<?php

namespace Tendril;

use Timber\Core;
use Timber\CoreInterface;

class Block extends Core implements CoreInterface {

    public $PostClass = 'Timber\Block';
    public $TermClass = 'Block';

    public $block;

    public function __construct( $block ) {
        if( $block ) {
            $this->block = $block;
            $this->id = $this->ID = $block['id'] ?? '';
            $this->object_type = 'block';
        }
    }
    
    public function __toString() {
        return $this->block_data['name'];
    }

    public function meta( $key ) {
        if($key != '' && !empty($this->id) ) {
            return get_field( $key, $this->id );
        }
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
