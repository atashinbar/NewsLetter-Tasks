<?php

function n2go_searchwp_limit_to_post_types( $clause, $engine ) {
	global $wpdb;

	if ( isset( $_GET[ 'search-in' ] ) ) {
		$search_in = array_map( sanitize_text_field, $_GET[ 'search-in' ] );
		$search_in = array_filter( $search_in, post_type_exists );

		if ( count( $search_in ) ) {
			$in = join( ',', array_fill( 0, count( $search_in ), '%s' ) );
			$clause = $wpdb->prepare( "AND {$wpdb->prefix}posts.post_type IN ({$in})", $search_in );
		}
	}

	return $clause;
}

add_filter( 'searchwp_where', 'n2go_searchwp_limit_to_post_types', 10, 2 );