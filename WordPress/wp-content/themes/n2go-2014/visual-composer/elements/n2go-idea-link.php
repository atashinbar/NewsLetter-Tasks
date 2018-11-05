<?php

class WPBakeryShortCode_N2Go_Idea_Link extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'N2Go Idea Link', 'js_composer' ),
	'base' => 'n2go_idea_link',
	'icon' => 'icon-heart',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'params' => array(
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'js_composer' ),
			'param_name' => 'link',
			'description' => __( 'Button link.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Text next to the help icon.', 'js_composer' )
		),
        array(
             'type' => 'css_editor',
             'heading' => __( 'Css', 'js_composer' ),
             'param_name' => 'css',
             // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
             'group' => __( 'Design options', 'js_composer' )
        )
	)
) );

?>