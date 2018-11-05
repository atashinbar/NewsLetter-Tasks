<?php
$output = $content = '';
extract( shortcode_atts( array(
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_search_form_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );

$content = '
	<form method="get" class="n2go-searchForm n2go-searchForm-large" id="searchform" action="'. get_bloginfo('home') . '">
		<input type="text" value="" name="s" id="s" placeholder="' . _x( 'Enter your search term', 'search form - search field input placeholder', 'n2go-theme' ) . '">
		<button type="submit"><div class="n2go-svg" data-svg-ref="search-glass"></div></button>
	</form>
';

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_search_form_widget' );

echo $output;