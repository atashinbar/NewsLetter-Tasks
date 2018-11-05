<?php

class WPBakeryShortCode_N2Go_Company_Type extends WPBakeryShortCode {
}

vc_map( array(
    "base"		=> "n2go_company_type",
    "name"		=> __("N2Go Company Type", "js_composer"),
    "class"		=> "",
    "icon"      => "icon-heart",
    'category'  => __( 'Content', 'js_composer' ),
    "params"	=> array(
        array(
          'type' => 'textfield',
          'heading' => __( 'Title', 'js_composer' ),
          'param_name' => 'title',
          'description' => __( 'Text below the icon.', 'js_composer' )
        ),
        array(
            'type' => 'vc_link',
            'heading' => __( 'Link', 'js_composer' ),
            'param_name' => 'link',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Image/Icon', 'js_composer' ),
            'param_name' => 'icon',
            'value' => array(
                __( 'E-Commerce', 'js_composer' ) => 'ecommerce',
                __( 'KMU', 'js_composer' ) => 'kmu',
                __( 'Agency', 'js_composer' ) => 'agency',
                __( 'NGO', 'js_composer' ) => 'ngo',
                __( 'Big Firm', 'js_composer' ) => 'bigfirm',
            ),
            'description' => __( 'Select company type image/icon.', 'js_composer' )
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