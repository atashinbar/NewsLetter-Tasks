<?php
$output = $title = $image = $tab_id = '';
extract(shortcode_atts($this->predefined_atts, $atts));

$img_id = preg_replace( '/[^\d]/', '', $image );
$imgSource = wp_get_attachment_image_src( $img_id, 'full' );

wp_enqueue_script('jquery_ui_tabs_rotate');

$backgroundColorStyle = '';

if ( $background_color != '' ) {
  $backgroundColorStyle = 'background-color: ' . $background_color . ';';
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'n2go-sliderTab ui-tabs-panel wpb_ui-tabs-hide', $this->settings['base'], $atts );

$output .= "\n\t\t\t" . '<div id="tab-'. (empty($tab_id) ? sanitize_title( $title ) : $tab_id) .'" class="'.$css_class.'" style="background-image:url(' . $imgSource[0] . ');' . $backgroundColorStyle . '">';
$output .= "\n\t\t\t\t" . '<div class="n2go-sliderTab_content">';
$output .= ($content=='' || $content==' ') ? '' : wpb_js_remove_wpautop($content);
$output .= "\n\t\t\t\t" . '</div>';
$output .= "\n\t\t\t" . '</div> ' . $this->endBlockComment('.wpb_tab');

echo $output;