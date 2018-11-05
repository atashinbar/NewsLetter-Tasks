<?php

function n2go_tab_flyout_navigation_hierarchy( $menu_location_slug, $item_callback, $start = '<div class="n2go-pageTabsNavigation"><ul>', $end = '</ul></div>', $itemStart = '<li>', $itemEnd = '</li>' )
{
	$output = '';
	
	$hierarchy = smn_get_hierarchy( $menu_location_slug );
	
	$items = $hierarchy->items;
	$parentChildList = $hierarchy->parentChildList;
	$activeHierarchy = $hierarchy->activeHierarchy;

	$output = n2go_tab_flyout_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, 0, $start, $end, $itemStart, $itemEnd, $item_callback, true );
	
	return $output;
}

function n2go_tab_flyout_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, $parentID, $start, $end, $itemStart, $itemEnd, $itemCallback, $is_toplevel_item = false, $level = 0 )
{
	$output = '';

	if ( is_array( $parentChildList ) )
	{
		if ( array_key_exists( $parentID, $parentChildList ) )
		{
			if ( $level == 1 ) {
				$output .= '<div class="n2go-flyout">';

				// reset flyout column index
				$flyout_column = 1;
				$flyout_columns_created = 0;
			}

			if ( $level != 1 ) {
				$output .= $start  . '';
			}

			$children = $parentChildList[ $parentID ];
			$index = 0;
			$last_index = count( $children ) - 1;

			foreach ( $children as $child )
			{
				if ( $level == 1 ) {
					$menuItem = $items[ $child ];
					$column = intval( $menuItem->attr_title, 10 );

					if ( $flyout_columns_created == 0 ) {
						$output .= '<div class="n2go-flyout_column">';
						$output .= $start  . '';
						$flyout_columns_created++;
					}

					if ( $column > $flyout_column ) {
						$output .= $end;
						$output .= '</div>';
						$output .= '<div class="n2go-flyout_column">';
						$output .= $start  . '';

						$flyout_columns_created++;
						$flyout_column = $column;
					}
				}

				if ( $level == 2 ) {
					$menuItem = $items[ $child ];
					$column = intval( $menuItem->attr_title, 10 );

					if ( $column > $flyout_column ) {
						$output .= $itemEnd;
						//$output .= $end;
						$output .= '</div>';
						$output .= '<div class="n2go-flyout_column">';
						$output .= $start  . '';
						$output .= $itemStart  . '';
						$output .= $start  . '';
						$output .= $itemStart  . '';

						$flyout_columns_created++;
						$flyout_column = $column;
					}
				}

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
					$output .= n2go_tab_flyout_wrap_navigation_item( $items, $parentChildList, $activeHierarchy, $child, '<ul>', '</ul>', '<li>', '</li>', $itemCallback, false, $level + 1 );
				}

				$output .= $itemEnd;

				if ( $level == 1 ) {
					if ( $index == $last_index ) {
						$output .= $end;
						$output .= '</div>';
					}
				}

				++$index;
			}

			if ( $level != 1 ) {
				$output .= $end;
			}

			if ( $level == 1 ) {
				$output .= '</div>';
			}
		}
	}

	return $output;
}