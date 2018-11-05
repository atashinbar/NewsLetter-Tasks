<?php
$output = $content = '';
extract( shortcode_atts( array(
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_breadcrumbs_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

if ( function_exists('yoast_breadcrumb') ) {
    $content = yoast_breadcrumb( '<div class="n2go-breadcrumbs">', '</div>', false );
}

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_breadcrumbs_widget' );

echo $output;