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

    /**
     * Determine the menu item for a given post id
     *
     * @param $menu_items   Array of TimberMenuItem
     * @param $post_id      Wordpress post id
     * 
     * @return TimberMenuItem
     */
    public function getMenuItemByPostId($menu_items, $post_id)
    {
        $result = null;

        foreach ($menu_items as $item) {

            if ($item->object_id == $post_id) {
                $result = $item;
                break;
            }
            else if ($item->children) {
                if ($result = $this->getMenuItemByPostId($item->children, $post_id)) {
                  break;
                }
            }
        }

        return $result;
    }

    /**
     * Get all menu parents for a given post id
     *
     * @param $menu_items   Array of TimberMenuItem
     * @param $post_id      Wordpress post id
     *
     * @return Array of TimberMenuItem
     */
    public function getParents($menu_items, $post_id)
    {
        $parents = [];

        if ($current_item = $this->getMenuItemByPostId($menu_items, $post_id)) {
            
            if ($parent_item = $this->getMenuItemByPostId($menu_items, $current_item->post_parent)) {
                $parents[$parent_item->level] = $parent_item;

                if ($parent_item->post_parent) {
                    $parents = $parents + $this->getParents($menu_items, $parent_item->object_id);
                }
            }
        }

        return $parents;
    }
}
