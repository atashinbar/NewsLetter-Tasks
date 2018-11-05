<?php
$output = $title = $interval = $height = $el_class = '';
extract( shortcode_atts( array(
	'title' => '',
	'interval' => 0,
	'height' => '370px',
	'el_class' => ''
), $atts ) );

wp_enqueue_script( 'jquery-ui-tabs' );

$el_class = $this->getExtraClass( $el_class );

$element = 'n2go_slider';

// Extract tab titles
preg_match_all( '/n2go_slider_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();
/**
 * n2go_slider
 *
 */
if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}


$tabs_nav = '';

if ( count( $tab_titles ) > 1 )
{
	$tabs_nav .= '<div class="n2go-sliderNavigation"><div class="n2go-sliderNavigation_inner"><ul class="ui-tabs-nav">';
	foreach ( $tab_titles as $tab ) {
		$tab_atts = shortcode_parse_atts($tab[0]);
		if(isset($tab_atts['title'])) {
			$tabs_nav .= '<li><span class="progress"></span><a href="#tab-' . ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) ) . '">' . $tab_atts['title'] . '</a></li>';
		}
	}
	$tabs_nav .= '</ul></div></div>' . "\n";
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( 'n2go-slider wpb_content_element ' . $el_class ), $this->settings['base'], $atts );

$output .= "\n\t" . '<div class="' . $css_class . '" data-interval="' . $interval . '" style="height:' . $height . '">';
$output .= "\n\t\t" . '<div class="wpb_slider_wrapper ui-tabs vc_clearfix">';
$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => $element . '_heading' ) );
$output .= "\n\t\t\t" . $tabs_nav;
$output .= "\n\t\t\t" . wpb_js_remove_wpautop( $content );
if ( 'vc_tour' == $this->shortcode ) {
	$output .= "\n\t\t\t" . '<div class="wpb_tour_next_prev_nav vc_clearfix"> <span class="wpb_prev_slide"><a href="#prev" title="' . __( 'Previous tab', 'js_composer' ) . '">' . __( 'Previous tab', 'js_composer' ) . '</a></span> <span class="wpb_next_slide"><a href="#next" title="' . __( 'Next tab', 'js_composer' ) . '">' . __( 'Next tab', 'js_composer' ) . '</a></span></div>';
}
$output .= "\n\t\t" . '</div> ';
$output .= "\n\t\t" . '<div class="n2go-slider_navigationArrows"><div class="n2go-navigationArrow n2go-navigationArrow-left"></div><div class="n2go-navigationArrow n2go-navigationArrow-right"></div></div>';
$output .= "\n\t" . '</div> ' . $this->endBlockComment( $element );

echo $output;