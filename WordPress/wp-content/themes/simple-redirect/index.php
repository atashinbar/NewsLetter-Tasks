<?php

$default_lang = 'en';
$allowed_langs = array( 'de' );

$lang = smn_get_language_from_browser( $allowed_langs, $default_lang, NULL, FALSE );

if ( !empty( $lang ) )
{
	global $wpdb;

    $blog = $wpdb->get_row( $wpdb->prepare( "SELECT blog_id, domain, path FROM wp_blogs where path = %s;", '/' . $lang . '/' ) );

    // check whether a blog for the requested language exists
    if ( $blog !== false )
    {
    	wp_redirect( 'http://' . $blog->domain . $blog->path );
    	exit;
    }
}


// redirect to default blog
$blog = $wpdb->get_row( $wpdb->prepare( "SELECT blog_id, domain, path FROM wp_blogs where path = %s;", '/' . $default_lang . '/' ) );

if ( $blog !== false )
{
	wp_redirect( 'http://' . $blog->domain . $blog->path );
	exit;
}