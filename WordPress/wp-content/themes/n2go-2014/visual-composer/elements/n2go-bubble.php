<?php

class WPBakeryShortCode_N2Go_Bubble extends WPBakeryShortCode {
}

vc_map( array(
    "base"		=> "n2go_bubble",
    "name"		=> __("N2Go Bubble", "js_composer"),
    "class"		=> "",
    "icon"      => "icon-heart",
    'category'  => __( 'Content', 'js_composer' ),
    "params"	=> array(
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __("Text", "js_composer"),
            "param_name" => "text",
            "value" => "",
            "description" => __("Enter your content.", "js_composer")
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
                __( 'AB Split', 'js_composer' ) => 'absplit',
                __( 'API Documentation', 'js_composer' ) => 'apidocumentation',
                __( 'API Implementations', 'js_composer' ) => 'apiimplementations',
                __( 'API REST', 'js_composer' ) => 'apirest',
                __( 'Attributes', 'js_composer' ) => 'attributes',
                __( 'Autoresponder', 'js_composer' ) => 'autoresponder',
                __( 'Blacklist', 'js_composer' ) => 'blacklist',
                __( 'Bounce Management', 'js_composer' ) => 'bouncemanagement',
                __( 'Bounces and Unsubscribes', 'js_composer' ) => 'bouncesandunsubscribes',
                __( 'Click Map', 'js_composer' ) => 'clickmap',
                __( 'Clients', 'js_composer' ) => 'clients',
                __( 'Clustering', 'js_composer' ) => 'clustering',
                __( 'Collaborative', 'js_composer' ) => 'collaborative',
                __( 'Conversion Tracking', 'js_composer' ) => 'conversiontracking',
                __( 'CSA', 'js_composer' ) => 'csa',
                __( 'CSCRM', 'js_composer' ) => 'cscrm',
                __( 'CSS Inliner', 'js_composer' ) => 'cssinliner',
                __( 'Custom Domain', 'js_composer' ) => 'eigenedomain',
                __( 'Daily Backups', 'js_composer' ) => 'dailybackups',
                __( 'DDV', 'js_composer' ) => 'ddv',
                __( 'Dedicated IPs', 'js_composer' ) => 'dedicatedips',
                __( 'DOI', 'js_composer' ) => 'doi',
                __( 'Duplicates', 'js_composer' ) => 'duplicates',
                __( 'Editor', 'js_composer' ) => 'editor',
                __( 'Export', 'js_composer' ) => 'export',
                __( 'For Free', 'js_composer' ) => 'forfree',
                __( 'Free Support', 'js_composer' ) => 'freesupport',
                __( 'Full Service', 'js_composer' ) => 'fullservice',
                __( 'Geo Tracking', 'js_composer' ) => 'geotracking',
                __( 'Google Analytics', 'js_composer' ) => 'googleanalytics',
                __( 'Image Hosting', 'js_composer' ) => 'imagehosting',
                __( 'Image Maps', 'js_composer' ) => 'imagemaps',
                __( 'Import', 'js_composer' ) => 'import',
                __( 'Inbox Testing', 'js_composer' ) => 'inboxtesting',
                __( 'Lifecycle', 'js_composer' ) => 'lifecycle',
                __( 'List Management', 'js_composer' ) => 'listmanagement',
                __( 'List Segmentation', 'js_composer' ) => 'listsegmentation',
                __( 'Magento', 'js_composer' ) => 'magento',
                __( 'Open and Click', 'js_composer' ) => 'openandclick',
                __( 'Pay on Demand', 'js_composer' ) => 'payondemand',
                __( 'Personalized', 'js_composer' ) => 'personalized',
                __( 'Plenty Markets', 'js_composer' ) => 'plentymarkets',
                __( 'Prices', 'js_composer' ) => 'preise',
                __( 'Privacy Protection', 'js_composer' ) => 'privacyprotection',
                __( 'Return Path', 'js_composer' ) => 'returnpath',
                __( 'SAAS', 'js_composer' ) => 'saas',
                __( 'Safe Addresses', 'js_composer' ) => 'safeaddresses',
                __( 'Schedule', 'js_composer' ) => 'schedule',
                __( 'Server', 'js_composer' ) => 'server',
                __( 'SMS', 'js_composer' ) => 'sms',
                __( 'SMS from Address', 'js_composer' ) => 'smsfromaddress',
                __( 'SMS Scheduled', 'js_composer' ) => 'smsscheduled',
                __( 'SMS World Wide', 'js_composer' ) => 'smsworldwide',
                __( 'SSL', 'js_composer' ) => 'ssl',
                __( 'Template Programming', 'js_composer' ) => 'templateprogramming',
                __( 'Templates', 'js_composer' ) => 'templates',
                __( 'Text Version', 'js_composer' ) => 'textversion',
                __( 'Thresholds', 'js_composer' ) => 'schwellwerte',
                __( 'Unsubscribes', 'js_composer' ) => 'unsubscribes',
                __( 'Web Version', 'js_composer' ) => 'webversion'
            ),
            'description' => __( 'Select bubble image/icon.', 'js_composer' )
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