<?php get_header(); ?>

<div class="n2go-offCanvasView" data-view="main">

	<div class="n2go-content">

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