<?php

class WPBakeryShortCode_N2Go_Embed extends WPBakeryShortCode {
}

vc_map( array(
    "base"		=> "n2go_embed",
    "name"		=> __("N2Go Embed", "js_composer"),
    "class"		=> "",
    "icon"      => "icon-wpb-film-youtube",
    'category'  => __( 'Content', 'js_composer' ),
    "params"	=> array(
        array(
            'type' => 'textarea_raw_html',
            'heading' => __( 'Embed HTML', 'js_composer' ),
            'param_name' => 'content'
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Display in computer screen?', 'js_composer' ),
            'param_name' => 'display_in_screen',
            'description' => __( 'If selected, the embedded video will be displayed within a computer screen.', 'js_composer' ),
            'value' => array( __( 'Yes, please', 'js_composer' ) => 'yes' )
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