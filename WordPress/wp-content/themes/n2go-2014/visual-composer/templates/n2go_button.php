<?php
$output = $content = '';
extract( shortcode_atts( array(
	'title' => '',
	'link' => '',
	'color' => '',
	'size' => '',
	'style' => '',
	'css' => '',
	'show_as_block_on_small_devices' => ''
), $atts ) );

$link = ( $link == '||' ) ? '' : $link;
$link = vc_build_link( $link );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_button_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

//$displayBlockOnSmallDevicesClass = $show_as_block_on_small_devices === 'yes' ? 'n2go-button-showAsDisplayBlockOnSmallDevices' : '';
$displayBlockOnSmallDevicesClass = 'n2go-button-showAsDisplayBlockOnSmallDevices';

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= '<a href="' . $link['url'] . '" title="' . $link['title'] . '" class="n2go-button n2go-button-' . $color . ' n2go-button-' . $size . ' ' . ' n2go-button-' . $style . ' ' . $displayBlockOnSmallDevicesClass . '">' . $title . '</a>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_button_widget' );

echo $output;