<?php

class WPBakeryShortCode_N2Go_Button extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'N2Go Button', 'js_composer' ),
	'base' => 'n2go_button',
	'icon' => 'icon-wpb-call-to-action',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'description' => __( 'Eye catching button', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Button link.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', 'js_composer' ),
			'holder' => 'button',
			'class' => 'vc_btn',
			'param_name' => 'title',
			'value' => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Text on the button.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Orange', 'js_composer' ) => 'orange',
				__( 'Grey', 'js_composer' ) => 'grey',
				__( 'Blue', 'js_composer' ) => 'blue'
			),
			'description' => __( 'Button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'js_composer' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Default', 'js_composer' ) => 'default',
				__( 'Small', 'js_composer' ) => 'small'
			),
			'std' => 'md',
			'description' => __( 'Button size.', 'js_composer' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Solid', 'js_composer' ) => 'solid',
				__( 'Outline', 'js_composer' ) => 'inverted'
			),
			'std' => 'md',
			'description' => __( 'Button style.', 'js_composer' )
		),
		/*
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show as block on small devices', 'js_composer' ),
			'param_name' => 'show_as_block_on_small_devices',
			'value' => array( __( 'Yes, please', 'js_composer' ) => 'yes' )
		),
		*/
	)
) );

?>