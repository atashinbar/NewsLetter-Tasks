<?php

add_filter( 'the_posts', 'smn_archive_sticky_posts' );

function smn_archive_sticky_posts( $posts ) {

	// apply it on the archives only
	if ( is_main_query() && is_post_type_archive() ) {
		global $wp_query;

		$sticky_posts = get_option( 'sticky_posts' );
		$num_posts = count( $posts );
		$sticky_offset = 0;

		// Find the sticky posts
		for ( $i = 0; $i < $num_posts; $i++ ) {

			// Put sticky posts at the top of the posts array
			if ( in_array( $posts[ $i ]->ID, $sticky_posts ) ) {
				$sticky_post = $posts[ $i ];

				// Remove sticky from current position
				array_splice( $posts, $i, 1 );

				// Move to front, after other stickies
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
				$sticky_offset++;

				// Remove post from sticky posts array
				$offset = array_search( $sticky_post->ID, $sticky_posts );
				unset( $sticky_posts[ $offset ] );
			}
		}

		// Look for more sticky posts if needed
		if ( !empty( $sticky_posts ) ) {

			$stickies = get_posts( array(
				'post__in' => $sticky_posts,
				'post_type' => $wp_query->query_vars[ 'post_type' ],
				'post_status' => 'publish',
				'nopaging' => true
			) );

			foreach ( $stickies as $sticky_post ) {
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
				$sticky_offset++;
			}
		}
	}

	return $posts;
}