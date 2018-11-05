<?php

global $wp_query;

$args = array( 'post_type' => 'help_topic' );
$args = array_merge( $args, $wp_query->query );
query_posts( $args );

get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content">
		<div class="n2go-centeredContent n2go-centeredContent-page" style="padding-top: 35px; padding-bottom: 35px;">
			<div class="n2go-grid n2go-grid-search" data-num-items-per-row="2">

				<div class="n2go-gridItem">
					<h1 style="margin-bottom: 35px;"><?php _ex( 'Search results', 'help topics - search results headline', 'n2go-theme' ); ?></h1>
				</div>

				<div class="n2go-gridItem">
					<form method="get" class="n2go-searchForm n2go-searchForm-helpTopics" id="searchform" action="<?php bloginfo( 'home' ); ?>/">
						<input type="hidden" name="search-type" value="help-topics">
						<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
						<button type="submit">
							<div class="n2go-svg" data-svg-ref="search-glass"></div>
						</button>
					</form>
				</div>

			</div>


			<div class="n2go-columns n2go-columns-search" data-column-count="2" style="margin: 0 -8px;">

				<div class="n2go-faqCategory">

					<h3 class="n2go-faqCategory_headline">
						<span><?php printf( _nx( 'Search term: \'%2$s\'', 'Search term: \'%2$s\'', $wp_query->found_posts, 'help topics - number of results (%1$s) for search term (%2$s)', 'n2go-theme' ), $wp_query->found_posts, get_search_query() ); ?></span>
					</h3>

					<?php

					if ( have_posts() ) :

						while ( have_posts() ) : the_post();

							global $post;

							?>
							<a id="link-faqCategory_topic-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-faqCategory_topic"><?php the_title(); ?></a><?php

						endwhile;

					else:

						?>
						<p><?php printf( _x( 'No matches found. <br>Please contact our support team.', 'search results - no results for search term (%1$s)', 'n2go-theme' ), get_search_query() ); ?></p>
						<?php

					endif;

					?>

				</div>

			</div>

			<?php

			$form = get_field( 'help_topics_search_contact_form', 'option' );

			echo '<div style="margin-top: 4em">';
			echo do_shortcode( '[contact-form-7 id="' . $form->ID . '"]' );
			echo '</div>';

			?>
		</div>
	</div>


	<?php get_footer(); ?>
