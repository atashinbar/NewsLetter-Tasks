<?php
$output = $content = '';
extract( shortcode_atts( array(
	'title' => '',
	'link' => '',
	'css' => ''
), $atts ) );

$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_help_link_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= '<a href="' . $link['url'] . '" title="' . $link['title'] . '" class="n2go-helpLink">' . $title . '</a>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_help_link_widget' );

echo $output;