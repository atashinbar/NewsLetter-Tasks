<?php

global $post;

$post = $this->post;
setup_postdata( $post );

?>
<a id="link-<?php echo $this->post->post_type ?>-teaser-<?php echo $this->post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-teaser n2go-teaser-<?php echo $this->post->post_type ?> <?php echo $this->additionalClass; ?>">
	<?php

	$imageSize = 'teaser-landscape';

	switch ( $this->post->post_type ) {
		case 'whitepaper':
		case 'infographics':
			$imageSize = 'teaser-portrait';
			break;
	}

	$imageOutput = false;

	if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) {
		$imageOutput = get_the_post_thumbnail( $this->post->ID, $imageSize );
	} else {
		$imageOutput = '<div class="n2go-imagePlaceholder"><div class="n2go-svg" data-svg-ref="logo"></div></div>';
	}

	if ( $imageOutput != false ) {
		echo '<div class="n2go-teaser_imageWrapper">';
		echo $imageOutput;

		if ( $this->post->post_type == 'video-knowledge' ) {
			echo '<div class="n2go-svg n2go-teaser_imageOverlay" data-svg-ref="video"></div>';
		}

		echo '</div>';
	}

	?>

	<div class="n2go-teaser_details">
		<div class="n2go-teaser_meta">
			<div style="float:left;">
				<div class="n2go-teaser_avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), '85' ); ?></div>
				<div class="n2go-teaser_author">
					<div class="n2go-teaser_metaIcon">
						<div class="n2go-svg" data-svg-ref="author"></div>
					</div>
					<?php the_author(); ?></div>
				<div class="n2go-teaser_date">
					<div class="n2go-teaser_metaIcon">
						<div class="n2go-svg" data-svg-ref="calendar"></div>
					</div>
					<?php echo get_the_date() ?>
				</div>
			</div>
			<?php

			/*

			$tags = get_the_tags();

			if ( $tags ):

				?>
				<div class="n2go-teaser_tags">
					<div class="n2go-teaser_metaIcon">
						<div class="n2go-svg" data-svg-ref="tags"></div>
					</div>
					<div class="n2go-teaser_taglist">

						<?php
						foreach( $tags as $tag ) {
							echo $tag->name . ', ';
						}
						?>

					</div>
				</div>
			<?php

			endif;

			*/

			?>
		</div>

		<?php

		$headline_tag = $this->isFirstPost === true ? 'h2' : 'h3';

		?>

		<<?php echo $headline_tag; ?> class="n2go-teaser_headline"><?php the_title() ?></<?php echo $headline_tag; ?>>

		<?php

		switch ( $this->post->post_type ) {
			case 'whitepaper':
			case 'infographics':
				break;

			default:
				?>
				<div class="n2go-teaser_text"><?php echo word_count( get_the_excerpt(), 30 ); ?>... </div>
				<?php
		}

		?>
		<div class="n2go-teaser_bottomGroup">
			<span class="n2go-button n2go-button-small n2go-button-inverted"><?php _ex( 'Read more', 'post teaser - read more button label', 'n2go-theme' ); ?></span>
			<?php

			$comments_count = wp_count_comments( $this->post->ID );
			$numComments = $comments_count->approved;

			if ( function_exists( 'pssc_facebook' ) ) {
				$numComments += pssc_facebook();
			}

			if ( function_exists( 'pssc_twitter' ) ) {
				$numComments += pssc_twitter();
			}

			if ( function_exists( 'pssc_gplus' ) ) {
				$numComments += pssc_gplus();
			}

			if ( $numComments > 0 ) {

				?>
				<div class="n2go-teaser_commentCount">
					<div class="n2go-teaser_metaIcon">
						<div class="n2go-svg" data-svg-ref="comments"></div>
					</div>
					<?php printf( _nx( '%s Comment', '%s Comments', $numComments, 'post teaser - comments / share activity count', 'n2go-theme' ), $numComments ); ?>
				</div>
				<?php

			}

			?>
		</div>
	</div>
</a>
<?php

wp_reset_postdata();

?>