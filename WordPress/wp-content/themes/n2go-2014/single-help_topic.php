<?php get_header(); ?>

	<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content">

		<div class="n2go-centeredContent n2go-centeredContent-page" style="padding-top: 20px; padding-bottom: 35px;">

			<?php

			global $post;
			$terms = get_the_terms( $post->ID, 'help_topic_category' );

			$rootTerms = array_filter( $terms, function ( $term ) {
				return $term->parent == 0;
			} );
			$subTerms = array_filter( $terms, function ( $term ) {
				return $term->parent != 0;
			} );

			$rootTerm = ( count( $rootTerms ) ) ? array_shift( $rootTerms ) : false;
			$term = ( count( $subTerms ) ) ? array_shift( $subTerms ) : false;

			if ( $rootTerm ) {
				$rootTermURL = get_term_link( $rootTerm );

				?>
				<a id="link-breadcrumbs_rootTerm" href="<?php echo $rootTermURL; ?>">
					<div class="n2go-breadcrumbs" style="margin-bottom: 35px;">◂ <?php printf( _x( 'Back to \'%1$s\'', 'help topics - back to parent (%1$s) link label', 'n2go-theme' ), $rootTerm->name ); ?></div>
				</a>
				<?php
			}

			?>

			<div class="n2go-grid n2go-grid-faq" data-num-items-per-row="2">

				<div class="n2go-gridItem n2go-faq">

					<h3 class="n2go-faq_question"><?php the_title(); ?></h3>

					<div class="n2go-faq_answer"><?php
						if ( have_posts() ) :
							// Start the Loop.
							while ( have_posts() ) : the_post();

								echo apply_filters( 'the_content', get_the_content() );

							endwhile;

						endif;
						?></div>

					<?php

					$images = get_field( 'help_topic_images' );

					if ( $images ) {
						foreach ( $images as $image ) {
							?>
							<img src="<?php echo $image[ 'url' ]; ?>" style="margin-bottom:20px;"/>
							<?php
						}
					}

					?>

				</div>

				<div class="n2go-gridItem n2go-faq_sidebar">
					<form method="get" class="n2go-searchForm n2go-searchForm-singleHelpTopic" id="searchform" action="<?php bloginfo( 'home' ); ?>/" style="margin-bottom: 1em;">
						<input type="hidden" name="search-type" value="help-topics">
						<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
						<button type="submit">
							<div class="n2go-svg" data-svg-ref="search-glass"></div>
						</button>
					</form>

					<?php

					if ( $term ) {
						$args = array(
							'posts_per_page'      => -1,
							'orderby'             => 'menu_order',
							'order'               => 'ASC',
							'post_type'           => 'help_topic',
							'help_topic_category' => $term->slug,
							'post_status'         => 'publish',
							'exclude'             => array( $post->ID )
						);

						$topics = get_posts( $args );

						if ( count( $topics ) ) {

							?>
							<div class="n2go-faqCategory">
								<h3 class="n2go-faqCategory_headline">
									<span><?php printf( _x( 'Other questions about %1$s', 'help topics - other questions for this topic (%1$s)', 'n2go-theme' ), $term->name ); ?></span>
								</h3>

								<?php

								if ( !empty( $topics ) && !is_wp_error( $topics ) ) {
									foreach ( $topics as $post ) {
										setup_postdata( $post );
										?>
										<a id="link-faqCategory_topic-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-faqCategory_topic"><?php the_title(); ?></a><?php
									}

									wp_reset_postdata();
								}

								?>
							</div>
							<?php

						}
					}

					?>

				</div>

			</div>
		</div>

	</div>

<?php get_footer(); ?>