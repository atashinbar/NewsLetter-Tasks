<?php

if ( !function_exists( 'smn_pagination' ) ) {
	function smn_pagination( $query ) {
		$prev_arrow = is_rtl() ? '&rarr;' : '&larr;';
		$next_arrow = is_rtl() ? '&larr;' : '&rarr;';

		if ( !$query ) {
			global $wp_query;
			$query = $wp_query;
		}


		$total = $query->max_num_pages;
		$big = 999999999; // need an unlikely integer
		if ( $total > 1 ) {
			if ( !$current_page = get_query_var( 'paged' ) )
				$current_page = 1;
			if ( get_option( 'permalink_structure' ) ) {
				$format = 'page/%#%/';
			} else {
				$format = '&paged=%#%';
			}
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => $format,
				'current' => max( 1, get_query_var( 'paged' ) ),
				'total' => $total,
				'end_size' => 2,
				'mid_size' => 3,
				'type' => 'list',
				'prev_text' => $prev_arrow,
				'next_text' => $next_arrow,
			) );
		}
	}
}