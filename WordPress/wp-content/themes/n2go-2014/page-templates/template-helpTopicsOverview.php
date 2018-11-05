<?php
/*
 * Template Name: Help Topics Overview
 */

?>

<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content">
		<div class="n2go-centeredContent n2go-centeredContent-page" style="padding-top: 35px; padding-bottom: 35px;">
			<div class="n2go-grid n2go-grid-faq" data-num-items-per-row="2">

				<div class="n2go-gridItem">
					<h1 style="margin-bottom: 3rem;"><?php the_title(); ?></h1>
				</div>

				<div class="n2go-gridItem">
					<form method="get" class="n2go-searchForm n2go-searchForm-helpTopics" id="searchform" action="<?php bloginfo('home'); ?>/">
						<input type="hidden" name="search-type" value="help-topics">
						<input type="text" value="" name="s" id="s" placeholder="<?php _ex( 'Enter your search term', 'common search input placeholder', 'n2go-theme' ); ?>">
						<button type="submit"><div class="n2go-svg" data-svg-ref="search-glass"></div></button>
					</form>
				</div>

			</div>


			<div class="n2go-columns n2go-columns-faq" data-column-count="2" style="margin: 0 -8px 2.5rem -8px;">

				<?php

				$categories = get_terms( 'help_topic_category', array(
					'orderby' => 'name',
					'hide_empty' => 0,
					'parent' => 0
				) );

				if ( !empty( $categories ) && !is_wp_error( $categories ) )
				{
					foreach ( $categories as $category )
					{
						// Sanitize the term, since we will be displaying it.
						$category = sanitize_term( $category, 'help_topic_category' );

						$category_link = get_term_link( $category, 'help_topic_category' );

						?>

						<a href="<?php echo $category_link; ?>" class="n2go-faqCategory">
							<h3 class="n2go-faqCategory_headline"><span><?php echo $category->name; ?></span></h3>
							<div class="n2go-faqCategory_description"><?php echo $category->description; ?></div>
						</a>

						<?php
					}
				}

				?>

			</div>

			
		</div>
	</div>

	<?php get_footer(); ?>

</div>