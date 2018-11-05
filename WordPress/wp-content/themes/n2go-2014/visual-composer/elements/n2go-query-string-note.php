<?php

class WPBakeryShortCode_N2Go_Query_String_Note extends WPBakeryShortCode {
}

vc_map( array(
	'name'     => __( 'N2Go Query-String Note', 'js_composer' ),
	'base'     => 'n2go_query_string_note',
	'icon'     => 'icon-heart',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'params'   => array(
		array(
			'type'       => 'textfield',
			'heading'    => 'Query-String Parameter #1',
			'holder'     => 'button',
			'class'      => 'vc_btn',
			'param_name' => 'query_param_1',
			'value'      => '',
		),

		array(
			'type'       => 'textfield',
			'heading'    => 'Expected Value',
			'holder'     => 'button',
			'class'      => 'vc_btn',
			'param_name' => 'query_value_1',
			'value'      => '',
		),

		array(
			'type'       => 'textfield',
			'heading'    => 'Query-String Parameter #2',
			'holder'     => 'button',
			'class'      => 'vc_btn',
			'param_name' => 'query_param_2',
			'value'      => '',
		),

		array(
			'type'       => 'textfield',
			'heading'    => 'Expected Value',
			'holder'     => 'button',
			'class'      => 'vc_btn',
			'param_name' => 'query_value_2',
			'value'      => '',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => 'Show action button?',
			'param_name'  => 'show_button',
			'description' => 'If selected, an action button will be shown in the info box.',
			'value'       => array( 'Yes, please' => true ),
		),

		array(
			'type'        => 'vc_link',
			'heading'     => 'Button Link',
			'param_name'  => 'button_link',
			'description' => __( 'Button link.', 'js_composer' ),
			'dependency'  => array(
				'element'   => 'show_button',
				'not_empty' => true
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => 'Button Text',
			'holder'      => 'button',
			'class'       => 'vc_btn',
			'param_name'  => 'button_title',
			'value'       => __( 'Text on the button', 'js_composer' ),
			'description' => __( 'Text on the button.', 'js_composer' ),
			'dependency'  => array(
				'element'   => 'show_button',
				'not_empty' => true
			),
		),
		array(
			'type'               => 'dropdown',
			'heading'            => 'Button Color',
			'param_name'         => 'button_color',
			'value'              => array(
				__( 'Orange', 'js_composer' ) => 'orange',
				__( 'Grey', 'js_composer' )   => 'grey'
			),
			'description'        => __( 'Button color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
			'dependency'         => array(
				'element'   => 'show_button',
				'not_empty' => true
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => 'Button Size',
			'param_name'  => 'button_size',
			'value'       => array(
				__( 'Default', 'js_composer' ) => 'default',
				__( 'Small', 'js_composer' )   => 'small'
			),
			'std'         => 'md',
			'description' => __( 'Button size.', 'js_composer' ),
			'dependency'  => array(
				'element'   => 'show_button',
				'not_empty' => true
			),
		),

		array(
			'type'       => 'textarea_html',
			'holder'     => 'div',
			'heading'    => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
			'value'      => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group'      => __( 'Design options', 'js_composer' )
		)
	)
) );

?>