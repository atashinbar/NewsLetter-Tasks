<?php

class WPBakeryShortCode_N2Go_Latest_Posts_Grid extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'N2Go Latest Posts Grid', 'js_composer' ),
	'base' => 'n2go_latest_posts_grid',
	'icon' => 'icon-heart',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Post Type', 'js_composer' ),
			'param_name' => 'post_type',
			'value' => array(
				'Blog Posts' => 'post',
				'Video Knowledge' => 'video-knowledge',
				'Infographics' => 'infographics',
				'Whitepaper' => 'whitepaper',
			),
			'description' => __( 'Select the type of the posts to display.', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of Posts', 'js_composer' ),
			'param_name' => 'number_of_posts'
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