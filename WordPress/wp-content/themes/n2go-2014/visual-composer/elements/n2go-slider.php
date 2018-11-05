<?php
/**
 */


class WPBakeryShortCode_N2Go_Slider extends WPBakeryShortCode {
	static $filter_added = false;
	protected $controls_css_settings = 'out-tc vc_controls-content-widget';
	protected $controls_list = array('edit', 'clone', 'delete');
	public function __construct( $settings ) {
		parent::__construct( $settings );
		if ( ! self::$filter_added ) {
			$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
			self::$filter_added = true;
		}
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $custom_markup = '';
		$shortcode_attributes = array( 'width' => '1/1' );
		foreach ( $this->settings['params'] as $param ) {
			if ( $param['param_name'] != 'content' ) {
				//$shortcode_attributes[$param['param_name']] = $param['value'];
				if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
					$shortcode_attributes[$param['param_name']] = __( $param['value'], "js_composer" );
				} elseif ( isset( $param['value'] ) ) {
					$shortcode_attributes[$param['param_name']] = $param['value'];
				}
			} else if ( $param['param_name'] == 'content' && $content == NULL ) {
				//$content = $param['value'];
				$content = __( $param['value'], "js_composer" );
			}
		}
		extract( shortcode_atts(
			$shortcode_attributes
			, $atts ) );

		// Extract tab titles

		preg_match_all( '/n2go_slider_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
		/*
$tab_titles = array();
if ( isset($matches[1]) ) { $tab_titles = $matches[1]; }
*/
		$output = '';
		$tab_titles = array();

		if ( isset( $matches[0] ) ) {
			$tab_titles = $matches[0];
		}
		$tmp = '';
		if ( count( $tab_titles ) ) {
			$tmp .= '<ul class="clearfix tabs_controls">';
			foreach ( $tab_titles as $tab ) {
				preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $tab_matches[1][0] ) ) {
					$tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0] . '</a></li>';

				}
			}
			$tmp .= '</ul>' . "\n";
		} else {
			$output .= do_shortcode( $content );
		}


		/*
if ( count($tab_titles) ) {
	$tmp .= '<ul class="clearfix">';
	foreach ( $tab_titles as $tab ) {
		$tmp .= '<li><a href="#tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
	}
	$tmp .= '</ul>';
} else {
	$output .= do_shortcode( $content );
}
*/
		$elem = $this->getElementHolder( $width );

		$iner = '';
		foreach ( $this->settings['params'] as $param ) {
			$custom_markup = '';
			$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
			if ( is_array( $param_value ) ) {
				// Get first element from the array
				reset( $param_value );
				$first_key = key( $param_value );
				$param_value = $param_value[$first_key];
			}
			$iner .= $this->singleParamHtmlHolder( $param, $param_value );
		}
		//$elem = str_ireplace('%wpb_element_content%', $iner, $elem);

		if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
			if ( $content != '' ) {
				$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
			} else if ( $content == '' && isset( $this->settings["default_content_in_template"] ) && $this->settings["default_content_in_template"] != '' ) {
				$custom_markup = str_ireplace( "%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"] );
			} else {
				$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
			}
			//$output .= do_shortcode($this->settings["custom_markup"]);
			$iner .= do_shortcode( $custom_markup );
		}
		$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
		$output = $elem;

		return $output;
	}

	public function getTabTemplate() {
		return '<div class="wpb_template">' . do_shortcode( '[n2go_slider_tab title="Tab" tab_id=""][/n2go_slider_tab]' ) . '</div>';
	}

	public function setCustomTabId( $content ) {
		return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
	}
}



/* N2Go Slider Tabs
---------------------------------------------------------- */
$slider_tab_id_1 = time() . '-1-' . rand( 0, 100 );
$slider_tab_id_2 = time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'N2Go Slider', 'js_composer' ),
	'base' => 'n2go_slider',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Tabbed content', 'js_composer' ),
	'params' => array(
	  array(
      'type' => 'textfield',
      'heading' => __( 'Height', 'js_composer' ),
      'param_name' => 'height',
      'value' => '370px',
      'admin_label' => true,
      'description' => __( 'Enter the height for the slider.', 'js_composer' ),
    ),
	  array(
      'type' => 'dropdown',
      'heading' => __( 'Auto rotate tabs', 'js_composer' ),
      'param_name' => 'interval',
      'value' => array( __( 'Disable', 'js_composer' ) => 0, 3, 5, 10, 15 ),
      'std' => 0,
      'description' => __( 'Auto rotate tabs each X seconds.', 'js_composer' )
    ),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[n2go_slider_tab title="' . __( 'Tab 1', 'js_composer' ) . '" tab_id="' . $slider_tab_id_1 . '"][/n2go_slider_tab]
[n2go_slider_tab title="' . __( 'Tab 2', 'js_composer' ) . '" tab_id="' . $slider_tab_id_2 . '"][/n2go_slider_tab]
',
	'js_view' => 'N2GoSliderView'
) );

vc_map( array(
	'name' => __( 'N2Go Slider Tab', 'js_composer' ),
	'base' => 'n2go_slider_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Tab title.', 'js_composer' )
		),
		array(
      'type' => 'attach_image',
      'heading' => __( 'Background Image', 'js_composer' ),
      'param_name' => 'image',
      'value' => '',
      'description' => __( 'Select image from media library.', 'js_composer' )
    ),
    array(
      'type' => 'colorpicker',
      'heading' => __( 'Background Color', 'js_composer' ),
      'param_name' => 'background_color',
      'description' => __( 'Set custom background color for this tab.', 'js_composer' )
    ),
		array(
			'type' => 'tab_id',
			'heading' => __( 'Tab ID', 'js_composer' ),
			'param_name' => "tab_id"
		)
	),
	'js_view' => 'N2GoSliderTabView'
) );
