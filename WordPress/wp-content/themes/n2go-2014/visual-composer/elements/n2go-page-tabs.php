<?php
/**
 */


class WPBakeryShortCode_N2Go_Page_Tabs extends WPBakeryShortCode {
	static $filter_added = false;
	protected $controls_css_settings = 'out-tc vc_controls-content-widget';
	protected $controls_list = array('edit', 'clone', 'delete');
	public function __construct( $settings ) {
		parent::__construct( $settings );
		// WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'n2go_page_tab' ) );
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

		preg_match_all( '/n2go_page_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
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
		return '<div class="wpb_template">' . do_shortcode( '[n2go_page_tab title="Tab" tab_id=""][/n2go_page_tab]' ) . '</div>';
	}

	public function setCustomTabId( $content ) {
		return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
	}
}



/* N2Go Page Tabs
---------------------------------------------------------- */
$page_tab_id_1 = time() . '-1-' . rand( 0, 100 );
$page_tab_id_2 = time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'N2Go Page Tabs', 'js_composer' ),
	'base' => 'n2go_page_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
	'category' => __( 'Content', 'js_composer' ),
	'description' => __( 'Tabbed content', 'js_composer' ),
	'params' => array(
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
[n2go_page_tab title="' . __( 'Tab 1', 'js_composer' ) . '" tab_id="' . $page_tab_id_1 . '"][/n2go_page_tab]
[n2go_page_tab title="' . __( 'Tab 2', 'js_composer' ) . '" tab_id="' . $page_tab_id_2 . '"][/n2go_page_tab]
',
	'js_view' => 'N2GoPageTabsView'
) );

vc_map( array(
	'name' => __( 'N2Go Page Tab', 'js_composer' ),
	'base' => 'n2go_page_tab',
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
			'type' => 'tab_id',
			'heading' => __( 'Tab ID', 'js_composer' ),
			'param_name' => "tab_id"
		)
	),
	'js_view' => 'N2GoPageTabView'
) );
