<?php
/*
 * Template Name: Knowledge Base
 */

?>
<?php get_header(); ?>

	<div class="n2go-offCanvasView" data-view="main">

		<div class="n2go-blog n2go-content n2go-wissen">

			<?php

			$subnavigation = get_field( 'n2go_kb_subnavigation', 'option' );

			if ( $subnavigation ) {
				$content = n2go_tab_flyout_navigation_hierarchy( $subnavigation->slug, 'n2go_features_subnavigation_nav_menu_item_output', '<div class="n2go-pageTabsNavigation"><ul>', '</ul></div>', '<li>', '</li>' );
				echo do_shortcode( '[vc_row margin_bottom="0"][vc_column width="1/1"]' . $content . '[/vc_column][/vc_row]' );
			}

			?>

			<?php
			if ( have_posts() ) :
				// Start the Loop.
				while ( have_posts() ) : the_post();

					echo apply_filters( 'the_content', get_the_content() );

				endwhile;

			endif;
			?>

		</div>

		<?php get_footer(); ?>
