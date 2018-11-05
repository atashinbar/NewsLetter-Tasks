<?php
$output = '';
extract( shortcode_atts( array(
	'post_type' => 'post',
	'number_of_posts' => 1,
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_latest_posts_grid_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );

$args = array(
	'posts_per_page' => intval( $number_of_posts ),
	'post_type' => $post_type,
);

global $post;
$posts_array = get_posts( $args );

echo "\n\t" . '<div class="' . $css_class . '">';
echo "\n\t\t" . '<div class="wpb_wrapper">';

if ( $posts_array && count( $posts_array ) ) {

	echo '<div class="n2go-latestPostsGrid n2go-latestPostsGrid-' . $post_type . '">';

	foreach ( $posts_array as $post ) {
		setup_postdata( $post );

		$imageSize = 'teaser-landscape';

		switch ( $post->post_type ) {
			case 'whitepaper':
			case 'infographics':
				$imageSize = 'teaser-portrait';
				break;
		}

		$imageOutput = false;

		if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) {
			$imageOutput = get_the_post_thumbnail( $post->ID, $imageSize );
		} else {
			$imageOutput = '<div class="n2go-imagePlaceholder"><div class="n2go-svg" data-svg-ref="logo"></div></div>';
		}

		?>
		<div class="n2go-gridItem">
			<a id="link-<?php echo $post->post_type ?>-teaser-<?php echo $post->ID ?>" href="<?php the_permalink(); ?>" class="n2go-latestPost n2go-latestPost-<?php echo $post->post_type ?>">
				<?php

				if ( $imageOutput != false ) {
					echo '<div class="n2go-latestPost_imageWrapper">';
					echo $imageOutput;

					if ( $post->post_type == 'video-knowledge' ) {
						echo '<div class="n2go-svg n2go-latestPost_imageOverlay" data-svg-ref="video"></div>';
					}

					echo '</div>';
				}

				switch ( $post->post_type ) {
					case 'post':
					case 'video-knowledge':
						?>
						<div class="n2go-latestPost_details">
							<div class="n2go-latestPost_headline"><?php the_title() ?></div>
							<div class="n2go-latestPost_text"><?php echo word_count( get_the_excerpt(), 30 ); ?>... </div>
						</div>
						<?php
						break;

					case 'infographics':
					case 'whitepaper':
						?>

						<?php


						break;

				}

				?>
			</a>
		</div>
		<?php
	}

	echo '</div>';

	wp_reset_postdata();
}

echo "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
echo "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_latest_posts_grid_widget' );