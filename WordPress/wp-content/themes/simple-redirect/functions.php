<?php

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

function slicemenice_manipulate_admin_menu() 
{
	/* Default Admin Menu
	5 - Posts
	10 - Media
	15 - Links
	20 - Pages
	25 - comments
	60 - first separator
	65 - Plugins
	70 - Users
	75 - Tools
	80 - Settings
	100 - second separator
	*/

	global $menu;
	
	// Stretch menu item positions
	$menu[1000] = $menu[100]; unset($menu[100]); // 1000 - second separator
	$menu[800] = $menu[80]; unset($menu[80]); // 800 - Settings
	$menu[750] = $menu[75]; unset($menu[75]); // 750 - Tools
	$menu[700] = $menu[70]; unset($menu[70]); // 700 - Users
	$menu[650] = $menu[65]; unset($menu[65]); // 650 - Plugins
	$menu[600] = $menu[60]; unset($menu[60]); // 600 - first separator
	$menu[250] = $menu[25]; unset($menu[25]); // 250 - comments
	$menu[200] = $menu[20]; unset($menu[20]); // 200 - Pages
	$menu[150] = $menu[15]; unset($menu[15]); // 150 - Links
	$menu[102] = $menu[10]; unset($menu[10]); // 102 - Media
	$menu[101] = $menu[5]; unset($menu[5]); // 101 - Posts
	
	// Disable unnecessary menu items
	
	unset($menu[101]); // Posts
	unset($menu[102]); // Media
	unset($menu[150]); // Links
	unset($menu[200]); // Pages
	unset($menu[250]); // Comments
}

add_action( 'admin_menu', 'slicemenice_manipulate_admin_menu', 1 );


function slicemenice_manipulate_admin_bar() 
{
	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'new-content' );
	$wp_admin_bar->remove_menu( 'comments' );
}

add_action( 'wp_before_admin_bar_render', 'slicemenice_manipulate_admin_bar' );



require_once( TEMPLATEPATH . '/includes/smn-browser-language.php' );