<?php

class WPBakeryShortCode_N2Go_Login_Form extends WPBakeryShortCode {
}

vc_map( array(
    "base"		=> "n2go_login_form",
    "name"		=> __("N2Go Login Form", "js_composer"),
    "class"		=> "",
    'show_settings_on_create' => false,
    "icon"      => "icon-heart",
    'category'  => __( 'Content', 'js_composer' ),
    "params"	=> array(
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
