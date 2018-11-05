<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<?php

	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$helpTopicsPage = get_field( 'help_topics_page', 'option' );

	?>

	<div class="n2go-content">

		<div class="n2go-centeredContent n2go-centeredContent-page" style="padding-top: 20px; padding-bottom: 35px;">

			<a id="link-helpTopicsPage-<?php echo $helpTopicsPage->ID ?>" href="<?php echo get_permalink( $helpTopicsPage->ID ); ?>">
				<div class="n2go-breadcrumbs" style="margin-bottom: 35px;">◂ <?php _ex( 'Back to overview', 'help topics - back to overview page link label', 'n2go-theme' ); ?></div>
			</a>

			<div class="n2go-grid n2go-grid-faq" data-num-items-per-row="2">

				<div class="n2go-gridItem">
					<h1 style="margin-bottom: 35px;"><?php echo apply_filters( 'the_title', $term->name ); ?></h1>
				</div>

				<div class="n2go-gridItem">
					<form method="get" class="n2go-searchForm n2go-searchForm-helpTopics" id="searchform" action="<?php bloginfo('home'); ?>/">
						<input type="hidden" name="search-type" value="help-topics">
						<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
						<button type="submit"><div class="n2go-svg" data-svg-ref="search-glass"></div></button>
					</form>
				</div>

			</div>

			<div class="n2go-columns n2go-columns-faq" data-column-count="2" style="margin: 0 -8px;">

				<?php

				$categories = get_terms( 'help_topic_category', array(
					'orderby' => 'name',
					'hide_empty' => 0,
					'parent' => $term->term_id
				) );

				if ( !empty( $categories ) && !is_wp_error( $categories ) )
				{
					foreach ( $categories as $category )
					{
						// Sanitize the term, since we will be displaying it.
						$category = sanitize_term( $category, 'help_topic_category' );

						?>

						<div class="n2go-faqCategory">

							<h3 class="n2go-faqCategory_headline"><span><?php echo $category->name; ?></span></h3>

							<?php

							$args = array(
								'posts_per_page' => -1,
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'post_type' => 'help_topic',
								'help_topic_category' => $category->slug,
								'post_status' => 'publish'
							);

							$topics = get_posts( $args );

							if ( !empty( $topics ) && !is_wp_error( $topics ) )
							{
								foreach ( $topics as $post )
								{
									setup_postdata( $post );
									?><a id="link-faqCategory_topic-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-faqCategory_topic"><?php the_title(); ?></a><?php
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

<?php get_footer(); ?>