<?php /*Template Name: Whitepaper Loop Template 3 */ ?>

<?php require_once( 'partials/archive-start.php' ); ?>

	<div class="n2go-grid n2go-teaserGrid" data-num-items-per-row="2">

		<?php


		$args = array(
			'posts_per_page'   => 5,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'whitepaper',
			'post_status'      => 'publish'
		);
		$posts = get_posts( $args );

		foreach ($posts as $post) {
			echo "ergtergddabc: " . $post->ID;


			$isFirstPost = false;

			$additionalClass = '';
			$gridItem_additionalClass = '';

			?>
			<div class="n2go-gridItem <?php echo $gridItem_additionalClass; ?>">
				<?php

				smk_get_template_part( '../partials/teaser.php', array(
					'post' => $post,
					'additionalClass' => $additionalClass,
					'isFirstPost' => $isFirstPost
				) );

				?>
			</div>

			<?php

		}


		?>


	</div>

	<div style="margin: 3rem 0 0;">
		<?php smn_pagination(); ?>
	</div>


<?php require_once( 'partials/archive-end.php' ) ?>