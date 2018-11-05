<?php
$output = $content = '';
extract( shortcode_atts( array(
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_features_subnavigation_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$content = n2go_tab_flyout_navigation_hierarchy( 'features-subnavigation', 'n2go_features_subnavigation_nav_menu_item_output', '<div class="n2go-pageTabsNavigation"><ul>', '</ul></div>', '<li>', '</li>' );

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_features_subnavigation_widget' );

echo $output;