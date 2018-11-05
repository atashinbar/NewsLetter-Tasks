<?php

class WPBakeryShortCode_N2Go_Info_Box extends WPBakeryShortCode {
}

vc_map( array(
	'name' => __( 'N2Go Info Box', 'js_composer' ),
	'base' => 'n2go_info_box',
	'icon' => 'icon-heart',
	'category' => array( __( 'Content', 'js_composer' ) ),
	'params' => array(
		array(
      'type' => 'textarea_html',
      'holder' => 'div',
      'heading' => __( 'Text', 'js_composer' ),
      'param_name' => 'content',
      'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
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