<?php

if ( isset( $_GET[ 'search-type' ] ) ) {
	$type = $_GET[ 'search-type' ];
}

if ( $type == 'help-topics' ) {
	load_template( TEMPLATEPATH . '/search-help-topics.php' );
	exit;
}

get_header(); ?>

	<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content n2go-wissen">

		<div class="n2go-centeredContent n2go-centeredContent-page">
			<main class="n2go-searchResults">
				<h1><?php _ex( 'Knowledge Base', 'search results - headline', 'n2go-theme' ); ?></h1>

				<form method="get" class="n2go-searchForm n2go-searchForm-large" id="searchform" action="<?php bloginfo( 'home' ); ?>/">
					<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'search results - search field input placeholder', 'n2go-theme' ); ?>">
					<button type="submit">
						<div class="n2go-svg" data-svg-ref="search-glass"></div>
					</button>
				</form>

				<?php

				global $post;

				$swp_query = new SWP_Query( array(
					's' => get_search_query(),
					'engine' => 'default',
					'posts_per_page' => 100
				) );


				if ( !empty( $swp_query->posts ) ) : ?>

					<h2><?php printf( _nx( 'We\'ve found %1$d result for the term \'%2$s\'', 'We\'ve found %1$s results for the term \'%2$s\'', $swp_query->found_posts, 'search results - number of results (%1$s) for search term (%2$s)', 'n2go-theme' ), $swp_query->found_posts, get_search_query() ); ?></h2>

				<?php else: ?>

					<h2 class="n2go-blogPostTeaser_headline" style="margin-bottom:5rem;">
						<b><?php printf( _x( 'No matches found. <br>Please contact our support team.', 'search results - no results for search term (%1$s)', 'n2go-theme' ), get_search_query() ); ?></b>
					</h2>

				<?php endif; ?>

				<?php

				$searchwp_settings = get_option( SEARCHWP_PREFIX . 'settings' );
				$engines = $searchwp_settings[ 'engines' ];

				if ( isset( $engines[ 'default' ] ) ) {
					?>
					<div class="n2go-searchResults_filter">
						<?php

						foreach ( $engines[ 'default' ] as $post_type => $settings ) {
							if ( $settings[ 'enabled' ] === false ) {
								continue;
							}

							$post_type_object = get_post_type_object( $post_type );

							$search_in = isset( $_GET[ 'search-in' ] ) ? $_GET[ 'search-in' ] : false;
							$checked = ( $search_in === false || in_array( $post_type, $_GET[ 'search-in' ] ) ) ? 'checked' : '';

							?>
							<label><div class="n2go-checkbox n2go-checkbox-round"><div class="n2go-svg n2go-checkbox_checkmark" data-svg-ref="checkmark"></div><input class="n2go-roundCheckbox_input" form="searchform" type="checkbox" name="search-in[]" <?php echo $checked; ?> value="<?php echo $post_type ?>"/></div> <?php echo $post_type_object->labels->name; ?> (<?php echo n2go_get_search_result_count( get_search_query(), $post_type ); ?>)</label>
							<?php
						}

						?>
					</div>
					<?php
				}

				?>

				<?php

				if ( !empty( $swp_query->posts ) ) :

					// Start the Loop.
					foreach ( $swp_query->posts as $post ) : setup_postdata( $post ); ?>

						<a id="link-searchResult-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-searchResult">
							<h3 class="n2go-searchResult_title"><?php the_title() ?></h3>

							<div class="n2go-searchResult_text"><?php echo word_count( get_the_excerpt(), 30 ); ?>...</div>
						</a>

					<?php endforeach; ?>
					<?php

					wp_reset_postdata();

				endif;

				?>
				<?php

				smn_pagination( $swp_query );
				?>
			</main>
		</div>

	</div>

<?php get_footer(); ?>