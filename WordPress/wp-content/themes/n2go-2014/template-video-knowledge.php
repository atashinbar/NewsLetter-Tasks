<?php /*Template Name: Video-Knowledge Archiv Template */ ?>

<?php require_once( 'partials/archive-start.php' ); ?>

	<div class="n2go-grid n2go-teaserGrid" data-num-items-per-row="2">

		<?php
		
		$args = array(
			'posts_per_page'   => 5,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'video-knowledge',
			'post_status'      => 'publish'
		);
		$posts = get_posts( $args );

		foreach ($posts as $post) {


			$isFirstPost = ($post == $posts[0] ? true : false);


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


		}


		?>


	</div>

	<div style="margin: 3rem 0 0;">
		<?php smn_pagination(); ?>
	</div>


<?php require_once( 'partials/archive-end.php' ) ?>