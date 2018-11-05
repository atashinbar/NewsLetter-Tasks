<?php
$output = $content = '';
extract( shortcode_atts( array(
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_blog_post_image_and_metadata_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );

if ( !is_single() || get_post_type() != 'post' )
	return;

ob_start();
?>
<div class="n2go-blogPostImageAndMeta">
	<div class="n2go-blogPostImageAndMeta_meta">
		<div style="float:left;">
			<div class="n2go-blogPostImageAndMeta_avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), '85' ); ?></div>
			<div class="n2go-blogPostImageAndMeta_author">
				<div class="n2go-blogPostImageAndMeta_metaIcon">
					<div class="n2go-svg" data-svg-ref="author"></div>
				</div>
				<?php the_author(); ?></div>
			<div class="n2go-blogPostImageAndMeta_date">
				<div class="n2go-blogPostImageAndMeta_metaIcon">
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
			<div class="n2go-blogPostImageAndMeta_tags">
				<div class="n2go-blogPostImageAndMeta_metaIcon">
					<div class="n2go-svg" data-svg-ref="tags"></div>
				</div>
				<div class="n2go-blogPostImageAndMeta_taglist">

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

	$imageSize = 'blog-teaser';
	$imageOutput = false;

	if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) {
		$imageOutput = get_the_post_thumbnail( $this->post->ID, $imageSize );
	} else {
		$imageOutput = '<div class="n2go-imagePlaceholder"><div class="n2go-svg" data-svg-ref="logo"></div></div>';
	}

	if ( $imageOutput != false ) {
		echo '<div class="n2go-blogPostImageAndMeta_imageWrapper">' . $imageOutput . '</div>';
	}

	?>
</div>
<?php

$content = ob_get_contents();
ob_end_clean();

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_blog_post_image_and_metadata_widget' );

echo $output;