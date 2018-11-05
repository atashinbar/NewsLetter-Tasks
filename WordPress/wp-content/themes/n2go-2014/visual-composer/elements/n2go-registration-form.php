<?php

class WPBakeryShortCode_N2Go_Registration_Form extends WPBakeryShortCode {
}

vc_map(array(
	"base" => "n2go_registration_form",
	"name" => __("N2Go Registration Form", "js_composer"),
	"class" => "",
	"icon" => "icon-heart",
	'category' => __('Content', 'js_composer'),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __('Layout', 'js_composer'),
			'param_name' => 'layout',
			'value' => array(
				__('Default', 'js_composer') => 'default',
				__('Minimal', 'js_composer') => 'minimal'
			),
			'description' => __('Select a form layout.', 'js_composer')
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Title (Override)', 'js_composer' ),
			'param_name' => 'button_title',
			'description' => __( 'Overrides the default button title.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __('Css', 'js_composer'),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => __('Design options', 'js_composer')
		)
	)
));


?>