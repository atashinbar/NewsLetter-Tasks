<?php

if ( !function_exists( 'n2go_get_search_result_count_where_filter' ) ) {
	function n2go_get_search_result_count_where_filter( $default_args, $custom_args ) {
		$clause = $default_args[ 0 ];
		$engine = $default_args[ 1 ];
		$post_type = $custom_args[ 0 ];

		global $wpdb;

		if ( isset( $post_type ) ) {
			$post_type = sanitize_text_field( $post_type );
			if ( post_type_exists( $post_type ) ) {
				$clause = $wpdb->prepare( "AND {$wpdb->prefix}posts.post_type = %s", $post_type );
			}
		}

		return $clause;
	}
}

if ( !function_exists( 'n2go_get_search_result_count' ) ) {
	function n2go_get_search_result_count( $search_query, $post_type ) {
		$fn = array(
			new WPSE_Filter_Storage( array( $post_type ) ),
			'n2go_get_search_result_count_where_filter'
		);

		add_filter( 'searchwp_where', $fn, 99, 2 );

		$swp_query = new SWP_Query( array(
			's' => $search_query,
			'engine' => 'default',
			'load_posts' => false,
			'posts_per_page' => -1
		) );

		remove_filter( 'searchwp_where', $fn, 99, 2 );

		return $swp_query->found_posts;
	}
}