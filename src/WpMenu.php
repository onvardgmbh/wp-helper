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
     * @uses  wp_get_nav_menu_items()
     *
     * @param  String $menud_id
     *
     * @return array $tree
     */
    public static function getMenuAsArray($location): array
    {
        $locations = get_nav_menu_locations();
        $menu      = get_term($locations[$location], 'nav_menu');
        $items     = wp_get_nav_menu_items($menu->term_id) ?: [];

        return static::buildTree($items, 0);
    }
}
