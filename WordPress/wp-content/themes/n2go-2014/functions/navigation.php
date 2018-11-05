<?php

add_action( 'init', 'register_menus' );

function register_menus()
{
	register_nav_menus( array(
		'main-navigation' => 'Main Navigation',
		'all-features-column-1' => 'All Features - Column 1',
		'all-features-column-2' => 'All Features - Column 2',
		'all-features-column-3' => 'All Features - Column 3',
		'all-features-column-4' => 'All Features - Column 4',
		'features-subnavigation' => 'Features Subnavigation',
		'footer-column-1' => 'Footer - Column 1',
		'footer-column-2' => 'Footer - Column 2',
		'footer-column-3' => 'Footer - Column 3',
		'footer-column-4' => 'Footer - Column 4'
	) );
}

function n2go_main_nav_menu_item_output( $title, $url, $attr_title, $is_current, $is_first, $is_last, $is_toplevel_item = false )
{
	$url = str_replace( get_bloginfo('url'), '', $url );
	$current = ( $is_current == true ) ? 'active' : '';

    $output = '<a id="link-mainNavigation-' . $url . '" class="' . $current .'" href="' . $url . '">' . $title . '</a>';

	return  $output;
}

function n2go_mobile_nav_menu_item_output( $title, $url, $attr_title, $is_current, $is_first, $is_last, $is_toplevel_item = false )
{
	$url = str_replace( get_bloginfo('url'), '', $url );
	$current = ( $is_current == true ) ? 'active' : '';

    $output = '<li class="' . $current .'"><a id="link-mobileNavigation-' . $url . '" href="' . $url . '">' . $title . '</a></li>';

	return  $output;
}

function n2go_all_features_nav_menu_item_output( $title, $url, $attr_title, $is_current, $is_first, $is_last, $is_toplevel_item = false )
{
	$url = str_replace( get_bloginfo('url'), '', $url );

	$current = ( $is_current == true ) ? 'selected' : '';
	$first = ( $is_first == true ) ? 'first ' : '';
	$last = ( $is_last == true ) ? 'last ' : '';

    $output = '<a id="link-allFeaturesNavigation-' . $url . '" href="' . $url . '"';

    if ( $is_current )
    {
        $output .= ' class="active"';
    }

    $output .= '>';

    if ( $is_toplevel_item )
    {
        $output .= '<b>';
    }

    $output .= $title;

    if ( $is_toplevel_item )
    {
        $output .= '</b>';
    }

    $output .= '</a>';

	return  $output;
}

function n2go_footer_nav_menu_item_output( $title, $url, $attr_title, $is_current, $is_first, $is_last, $is_toplevel_item = false )
{
	$url = str_replace( get_bloginfo('url'), '', $url );
	$current = ( $is_current == true ) ? 'active' : '';

    $output = '<a id="link-footerNavigation-' . $url . '" href="' . $url . '">' . $title . '</a>';

	return  $output;
}


function n2go_features_subnavigation_nav_menu_item_output( $title, $url, $attr_title, $is_current, $is_first, $is_last, $is_toplevel_item = false, $level = 0 )
{
	$url = str_replace( get_bloginfo('url'), '', $url );

	$current = ( $is_current == true ) ? 'selected' : '';
	$first = ( $is_first == true ) ? 'first ' : '';
	$last = ( $is_last == true ) ? 'last ' : '';

    $output = '<a id="link-features_subNavigation-' . $url . '" href="' . $url . '"';

    if ( $is_current )
    {
        $output .= ' class="active"';
    }

    $output .= '>';

    if ( $level == 1 )
    {
        $output .= '<b>';
    }

    $output .= $title;

    if ( $level == 1 )
    {
        $output .= '</b>';
    }

    $output .= '</a>';

	return  $output;
}