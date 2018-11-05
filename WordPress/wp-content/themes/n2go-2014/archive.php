<?php require_once( 'partials/archive-start.php' ); ?>

	<div class="n2go-grid n2go-teaserGrid" data-num-items-per-row="2">

		<?php

		if ( have_posts() ) :
			// Start the Loop.
			while ( have_posts() ) : the_post();

				global $post;
				global $wp_query;

				$isFirstPost = $wp_query->current_post == 0 && !is_paged();

				switch ( $post->post_type ) {
					case 'whitepaper':
					case 'infographics':
						$isFirstPost = false;
						break;
				}

				$additionalClass = '';
				$gridItem_additionalClass = '';

				if ( $isFirstPost ) {
					$additionalClass = ' n2go-teaser-large';
					$gridItem_additionalClass = ' n2go-gridItem-100';
				}

				?>
				<div class="n2go-gridItem <?php echo $gridItem_additionalClass; ?>">
					<?php

					smk_get_template_part( 'partials/teaser.php', array(
						'post' => $post,
						'additionalClass' => $additionalClass,
						'isFirstPost' => $isFirstPost
					) );

					?>
				</div>

				<?php

				if ( $isFirstPost ) {
					?>
					<div class="n2go-gridItem" style="padding:0;"></div><?php
				}

			endwhile;

		endif;
		?>


	</div>

	<div style="margin: 3rem 0 0;">
		<?php smn_pagination(); ?>
	</div>


<?php require_once( 'partials/archive-end.php' ) ?>