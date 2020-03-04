<?php

namespace Tendril\Traits;

Trait Menu
{
    /**
     * Convert Timber menu tree into simplified array.
     * Can be converted into Json for the front end.
     *
     * @param $item Array of TimberMenu items.
     */
    public function getMenuTree($items)
    {
        $menu_items = [];

        foreach ($items as $it) {

            $mi = [
                'id' => $it->id,
                'text' => $it->name,
                'url' => $it->url,
                'children' => []
            ];

            if ($it->children) {
                $mi['children'] = $this->getMenuTree($it->children);
            }


            $menu_items[] = $mi;
        }

        return $menu_items;
    }
}
