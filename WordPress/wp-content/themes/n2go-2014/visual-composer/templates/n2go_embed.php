<?php
$output = $title = $link = $size = $el_class = '';
extract( shortcode_atts( array(
	'display_in_screen' => '',
	'css' => ''

), $atts ) );

$embedClass = '';

if ( $display_in_screen == 'yes' ) {
    $embedClass = ' n2go-embed-inScreen';
}

$content = rawurldecode(base64_decode(strip_tags($content)));

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_embed_widget wpb_content_element' . $el_class . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= '<div class="n2go-embed' . $embedClass . '"><div class="n2go-embed_content">' . $content . '</div></div>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_embed_widget' );

echo $output;