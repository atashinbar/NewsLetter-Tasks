<?php

function smn_get_hierarchy( $menu_location_slug )
{
	$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_root_relative_current;
	$_indexless_current = untrailingslashit( preg_replace( '/index.php$/', '', $current_url ) );

	if ( true )
	{
		// get menu and its items
		$menu = wp_get_nav_menu_object( $menu_location_slug );

		if( !$menu ) {
			$locations = get_nav_menu_locations();
			$menu = wp_get_nav_menu_object( $locations[ $menu_location_slug ] );
		}

		$menu_items = wp_get_nav_menu_items($menu->term_id);
		
		$menu_items = array_reverse( (array) $menu_items );
		
		$mark_parent = false;
		$parent_id = 0;

		$resultObject = new stdClass();

		$items = array();
		$parentChildList = array();
		$activeHierarchy = array();


		foreach ( $menu_items as $key => &$menu_item )
		{
			$menu_item_url = untrailingslashit( strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url );
			
			if ( $mark_parent == true && $menu_item->ID == $parent_id )
			{
				array_push( $activeHierarchy, $menu_item->ID );
				$menu_item->active = true;
				$parent_id = $menu_item->menu_item_parent;
				continue;
			}
			
			// if ( in_array( $menu_item_url, array( $current_url, $_indexless_current, $_root_relative_current ) ) ||
			// 	 ( $menu_item_url != get_bloginfo('url') ) ? strpos($current_url, $menu_item_url) !== false : false )
			// {
			// 	array_push( $activeHierarchy, $menu_item->ID );
			// 	$menu_item->active = true;
			// 	$mark_parent = true;
			// 	$parent_id = $menu_item->menu_item_parent;
			// }
		}

		$menu_items = array_reverse( (array) $menu_items );

		foreach ( $menu_items as $key => &$menu_item )
		{
			$items[ $menu_item->ID ] = $menu_item;

			if ( !array_key_exists( $menu_item->menu_item_parent, $parentChildList ) )
			{
				$parentChildList[$menu_item->menu_item_parent] = array();
			}

			array_push( $parentChildList[$menu_item->menu_item_parent], $menu_item->ID );
		}

		$resultObject->items = $items;
		$resultObject->parentChildList = $parentChildList;
		$resultObject->activeHierarchy = array_reverse( $activeHierarchy );
		
		return $resultObject;
	}

	return false;
}

function smn_full_navigation_hierarchy( $menu_location_slug, $item_callback, $start = '<nav><ul>', $end = '</ul></nav>', $itemStart = '<li>', $itemEnd = '</li>' )
{
	$output = '';
	
	$hierarchy = smn_get_hierarchy( $menu_location_slug );
	
	$items = $hierarchy->items;
	$parentChildList = $hierarchy->parentChildList;
	$activeHierarchy = $hierarchy->activeHierarchy;

	$output = smn_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, 0, $start, $end, $itemStart, $itemEnd, $item_callback, true );
	
	return $output;
}

function smn_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, $parentID, $start, $end, $itemStart, $itemEnd, $itemCallback, $is_toplevel_item = false, $level = 0 )
{
	$output = '';

	if ( is_array( $parentChildList ) )
	{
		if ( array_key_exists( $parentID, $parentChildList ) )
		{
			$output .= $start  . '';

			$children = $parentChildList[ $parentID ];
			$index = 0;
			$last_index = count( $children ) - 1;

			foreach ( $children as $child )
			{
				$output .= $itemStart;

				if ( is_callable( $itemCallback ) )
				{
					$menuItem = $items[ $child ];

					$title = $menuItem->title;
					$url = $menuItem->url;
					$attr_title = $menuItem->attr_title;


					$selected = in_array( $child, $activeHierarchy );

					$output .= call_user_func_array( $itemCallback, array( $title, $url, $attr_title, $selected, ($index == 0), ($index == $last_index), $is_toplevel_item, $level ) );
				}

				if ( array_key_exists( $child, $parentChildList ) )
				{
					$output .= smn_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, $child, '<ul>', '</ul>', '<li>', '</li>', $itemCallback, false, $level + 1 );
				}

				$output .= $itemEnd;

				++$index;
			}

			$output .= $end;
		}
	}

	return $output;
}