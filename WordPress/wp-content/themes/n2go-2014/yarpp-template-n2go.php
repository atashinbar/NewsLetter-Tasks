<?php
/*
YARPP Template: N2Go
*/

?>
<?php if ( have_posts() ) : ?>
	<div class="n2go-relatedPosts">
		<div class="n2go-relatedPosts_headline"><?php _ex( 'Related Articles', 'single post - related articles headline', 'n2go-theme' ); ?></div>
		<hr>
		<ol>
			<?php while ( have_posts() ) : the_post(); ?>
				<li>
					<a id="link-relatedPost-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>">

						<?php

						$imageSize = 'teaser-landscape';
						$imageOutput = false;

						if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) {
							$imageOutput = get_the_post_thumbnail( $post->ID, $imageSize );
						} else {
							$imageOutput = '<div class="n2go-imagePlaceholder"><div class="n2go-svg" data-svg-ref="logo"></div></div>';
						}

						if ( $imageOutput != false ) {
							echo '<div class="imageWrapper">' . $imageOutput . '</div>';
						}

						?>

						<div class="content">
							<div class="title"><?php get_the_title() ? the_title() : the_ID(); ?></div>
						</div>
					</a>
				</li>
			<?php endwhile; ?>
		</ol>
	</div>
<?php endif; ?>
