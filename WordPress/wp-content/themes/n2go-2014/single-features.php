<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content">

		<?php echo do_shortcode( '[vc_row css=".vc_custom_1409908762103{margin-bottom: 0px !important;}"][vc_column width="1/1"][n2go_features_subnavigation][/vc_column][/vc_row]' ); ?>

		<?php
			if ( have_posts() ) :
				// Start the Loop.
				while ( have_posts() ) : the_post();

					echo apply_filters( 'the_content', get_the_content() );

				endwhile;

			endif;
		?>

		<?php echo do_shortcode( '[all_features]' ); ?>

	</div>

<?php get_footer(); ?>