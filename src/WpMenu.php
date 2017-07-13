<?php

namespace Onvardgmbh\WpHelper;

class WpMenu
{
    /**
     * Modification of "Build a tree from a flat array in PHP"
     *
     * Authors: @DSkinner, @ImmortalFirefly and @SteveEdson
     *
     * @link https://stackoverflow.com/a/28429487/2078474
     */
    public static function buildTree(array &$elements, int $parentId = 0): array
    {
        $branch = [];
        foreach ($elements as &$element) {
            if ($element->menu_item_parent == $parentId) {
                $children = static::buildTree($elements, $element->ID);
                if ($children) {
                    $element->children = $children;
                }

                $branch[$element->ID] = $element;
                unset($element);
            }
        }

        return $branch;
    }

    /**
     * Transform a navigational menu to it's tree structure
     *
     * @uses  buildTree()
     *
     * @param  String $location slug of menu
     *
     * @return array $items
     */
    public static function getMenuAsArray(string $location): array
    {
        $locations = get_nav_menu_locations();
        if(empty($locations[$location])) {
            return [];
        }
        $menu      = get_term($locations[$location], 'nav_menu');
        $items     = wp_get_nav_menu_items($menu->term_id) ?: [];

        return static::buildTree($items, 0);
    }
}
