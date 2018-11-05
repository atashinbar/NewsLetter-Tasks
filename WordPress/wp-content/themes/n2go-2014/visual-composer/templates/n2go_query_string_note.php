<?php

$output = '';
extract( shortcode_atts( array(
	'query_param_1' => '',
	'query_value_1' => '',
	'query_param_2' => '',
	'query_value_2' => '',
	'show_button' => '',
	'button_title' => '',
	'button_link' => '',
	'button_color' => '',
	'button_size' => '',
	'css' => ''
), $atts ) );

$match = false;

if ( !empty( $query_param_1 ) && isset( $_GET[ $query_param_1 ] ) ) {
	$match = sanitize_text_field( $_GET[ $query_param_1 ] ) == $query_value_1;
}

if ( !empty( $query_param_2 ) ) {
	if ( isset( $_GET[ $query_param_2 ] ) ) {
		$match = $match && ( sanitize_text_field( $_GET[ $query_param_2 ] ) == $query_value_2 );
	} else {
		$match = false;
	}
}

if ( $match == false ) {
	return;
}

$button_link = ( $button_link == '||' ) ? '' : $button_link;
$button_link = vc_build_link( $button_link );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_query_string_note_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$button_output = '<a href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" class="n2go-button n2go-button-' . $button_color . ' n2go-button-' . $button_size . '">' . $button_title . '</a>';

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= '<div class="n2go-infoBox n2go-infoBox-queryStringNote">';

if ( $show_button == true ) {
	$output .= '<div class="n2go-infoBox_topButtonContainer">' . $button_output . '</div>';
}

$output .= '<div class="n2go-infoBox_content">' . wpb_js_remove_wpautop($content) . '</div>';

if ( $show_button == true ) {
	$output .= '<div class="n2go-infoBox_bottomButtonContainer">' . $button_output . '</div>';
}

$output .= '</div>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_query_string_note_widget' );

echo $output;