<?php

namespace Tendril\Traits;

Trait Block
{
    /**
     * Get properly formatted Gutenberg blocks
     *
     * @param $conten Post content
     */
    public function getBlocks($content)
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
