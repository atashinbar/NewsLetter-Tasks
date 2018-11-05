<?php

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

add_action( 'init', 'visual_composer_meta_override', 100 );

function visual_composer_meta_override() {
	if ( class_exists( 'Vc_Manager' ) ) {

		global $vc_manager;
		remove_action( 'wp_head', array( $vc_manager->vc(), 'addMetaData' ) );
	}
}

add_action( 'after_setup_theme', 'n2go_theme_setup' );

function n2go_theme_setup() {
	load_theme_textdomain( 'n2go-theme', get_template_directory() );
}

add_action( 'parse_query', 'n2go_set_affiliate_cookie' );

function n2go_set_affiliate_cookie() {
	$affiliate = 'mtALLall';
	$ref = (isset($_GET['ref']) ? $_GET['ref'] : '');
	$_ref = (isset($_GET['_ref']) ? $_GET['_ref'] : '');

	if($_ref != '') {
		$affiliate = $_ref;
	}
	if($ref != '') {
		$affiliate = $ref;
	}

	$site_url = get_option( 'siteurl' );

	$path = '/';
	$host = parse_url( $site_url, PHP_URL_HOST);

	$expiry = strtotime( '+60 days' );

	if(!isset($_COOKIE['ref'])) {
		setcookie( 'ref', $affiliate, $expiry, $path, $host );
	}
}

function n2g_add_editor_styles() {
	add_editor_style( '/styles/css/bootstrap.css' );
	add_editor_style( '/styles/apprise.css' );
	add_editor_style( '/styles/start.css' );
	add_editor_style( '/styles/uservoices.css' );
	add_editor_style( '/styles/faqs.css' );
	add_editor_style( '/styles/features.css' );
	add_editor_style( '/styles/main.css' );
	add_editor_style( '/styles/whitelabel/newsletter2go.css' );
}

add_action( 'init', 'n2g_add_editor_styles' );

add_action( 'admin_bar_menu', 'n2go_admin_bar_my_sites_menu', 25 );

function n2go_admin_bar_my_sites_menu( $wp_admin_bar ) {
	// Don't show for logged out users or single site mode.
	if ( !is_user_logged_in() || !is_multisite() )
		return;

	// Show only when the user has at least one site, or they're a super admin.
	if ( count( $wp_admin_bar->user->blogs ) < 1 && !is_super_admin() )
		return;

	foreach ( (array)$wp_admin_bar->user->blogs as $blog ) {
		switch_to_blog( $blog->userblog_id );

		$blavatar = '<div class="blavatar"></div>';

		$blogname = empty( $blog->blogname ) ? $blog->domain : $blog->blogname;
		$menu_id = 'blog-' . $blog->userblog_id;

		$node = $wp_admin_bar->get_node( $menu_id );

		$node->title = str_replace( 'Newsletter2Go - ', '', $node->title );

		$wp_admin_bar->remove_node( $menu_id );
		$wp_admin_bar->add_node( $node );

		restore_current_blog();
	}
}

function change_mce_options( $init ) {
	$init[ 'forced_root_block' ] = false;
	$init[ 'force_br_newlines' ] = true;
	$init[ 'force_p_newlines' ] = false;
	$init[ 'convert_newlines_to_brs' ] = true;

	return $init;
}

add_filter( 'tiny_mce_before_init', 'change_mce_options' );


// Customize mce editor font sizes
function n2go_mce_text_sizes( $initArray ) {
	$initArray[ 'fontsize_formats' ] = "9px 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px";
	return $initArray;
}

add_filter( 'tiny_mce_before_init', 'n2go_mce_text_sizes' );


// Add custom Fonts to the Fonts list
function n2go_mce_google_fonts_array( $initArray ) {
	$initArray[ 'font_formats' ] = 'Open Sans=Open Sans;Kiro=Kiro';
	return $initArray;
}

add_filter( 'tiny_mce_before_init', 'n2go_mce_google_fonts_array' );


function n2go_mce_google_fonts_styles() {
	$font_url = 'http://fonts.googleapis.com/css?family=Lato:300,400,700';
	add_editor_style( str_replace( ',', '%2C', $font_url ) );
}

add_action( 'init', 'n2go_mce_google_fonts_styles' );


add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

require_once( 'functions/utils/functions.php' );
require_once( 'functions/seo/functions.php' );

require_once( 'functions/navigation.php' );
require_once( 'functions/smn-firstImageFromPost.php' );
require_once( 'functions/smn-navigation.php' );
require_once( 'functions/smn-pagination.php' );
require_once( 'functions/smn-wordCount.php' );
require_once( 'functions/smk-theme-view.php' );
require_once( 'functions/shortcodes/all-features.php' );
require_once( 'functions/shortcodes/menu-shortcode.php' );
require_once( 'functions/shortcodes/n2go-pricing.php' );
require_once( 'functions/shortcodes/n2go-individual-pricing.php' );
require_once( 'functions/shortcodes/yarpp.php' );
require_once( 'functions/widgets/author.php' );
require_once( 'functions/widgets/newsletterForm.php' );
require_once( 'functions/widgets/postTags.php' );
require_once( 'functions/widgets/recentPosts.php' );
require_once( 'functions/n2go-tabFlyoutNavigation.php' );

require_once( 'functions/content/functions.php' );
require_once( 'functions/search/functions.php' );


function n2go_excerpt_length( $length ) {
	return 9999;
}

add_filter( 'excerpt_length', 'n2go_excerpt_length', 999 );

function n2go_widgets_init() {
	register_sidebar( array(
		'name'         => 'Blog Sidebar',
		'id'           => 'sidebar-blog',
		'before_title' => '<div class="n2go-blogSidebarWidget_headline">',
		'after_title'  => '</div><hr>',
	) );

	register_sidebar( array(
		'name'         => 'Post Sidebar',
		'id'           => 'post-sidebar',
		'before_title' => '<div class="n2go-blogSidebarWidget_headline">',
		'after_title'  => '</div><hr>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #1',
		'id'            => 'footer-title-1',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Section #1',
		'id'            => 'footer-section-1',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #2',
		'id'            => 'footer-title-2',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #2',
		'id'           => 'footer-section-2',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #3',
		'id'            => 'footer-title-3',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #3',
		'id'           => 'footer-section-3',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #4',
		'id'            => 'footer-title-4',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #4',
		'id'           => 'footer-section-4',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #5',
		'id'            => 'footer-title-5',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #5',
		'id'           => 'footer-section-5',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'          => 'Footer Title #6-9',
		'id'            => 'footer-title-6-9',
		'before_title'  => '<div>',
		'after_title'   => '</div>',
		'before_widget' => '<div class="n2go-footerSection_headline">',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #6',
		'id'           => 'footer-section-6',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #7',
		'id'           => 'footer-section-7',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #8',
		'id'           => 'footer-section-8',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Section #9',
		'id'           => 'footer-section-9',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Bottom Left',
		'id'           => 'footer-bottom-left',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

	register_sidebar( array(
		'name'         => 'Footer Bottom Right',
		'id'           => 'footer-bottom-right',
		'before_title'  => '<div class="n2go-footerSection_headline">',
		'after_title'   => '</div>',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );
}

add_action( 'widgets_init', 'n2go_widgets_init' );

add_rewrite_rule(
	'blog(/page/([0-9]+)?)?/?$',
	'index.php?pagename=blog&paged=$matches[2]',
	'top'
);

function n2go_msls_head_hreflang( $language ) {
	switch( $language ) {
		case 'us':
			$language = 'en_US';
			break;
	}

	return $language;
}

add_filter( 'msls_head_hreflang', 'n2go_msls_head_hreflang', 10, 1 );

remove_action( 'wp_head', 'msls_head' );
//add_action( 'wp_head', 'n2go_msls_head' );


function n2go_msls_output_get( $url, $link, $current ) {
	return sprintf( '<option value="%s"%s data-flag-url="%s">%s</option>', $url, ( $current ? ' selected="selected"' : '' ), $link->src, $link->txt );
}

add_filter( 'msls_output_get', 'n2go_msls_output_get', 10, 3 );

require_once( 'functions/msls/N2GoMultisiteLanguageSwitcher.php' );


// Add options page for "Theme Configuration"
if ( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page( array(
		'title'      => 'Theme Configuration',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Header',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Footer',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Registration Form',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Login Form',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'All Features Shortcode',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Help Topics',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Blog',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Knowledge Base',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );

	acf_add_options_sub_page( array(
		'title'      => 'Social Sharing',
		'parent'     => 'themes.php',
		'capability' => 'manage_options'
	) );
}

if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
// Visual Composer Configuration
	vc_set_shortcodes_templates_dir( get_template_directory() . '/visual-composer/templates/' );
}

if ( function_exists( 'vc_map' ) ) {

	require_once( 'visual-composer/elements/n2go-bubble.php' );
	require_once( 'visual-composer/elements/n2go-button.php' );
	require_once( 'visual-composer/elements/n2go-breadcrumbs.php' );
	require_once( 'visual-composer/elements/n2go-company-type.php' );
	require_once( 'visual-composer/elements/n2go-embed.php' );
	require_once( 'visual-composer/elements/n2go-features-subnavigation.php' );
	require_once( 'visual-composer/elements/n2go-help-link.php' );
	require_once( 'visual-composer/elements/n2go-idea-link.php' );
	require_once( 'visual-composer/elements/n2go-info-box.php' );
	require_once( 'visual-composer/elements/n2go-latest-posts-grid.php' );
	require_once( 'visual-composer/elements/n2go-login-form.php' );
	require_once( 'visual-composer/elements/n2go-registration-form.php' );
	require_once( 'visual-composer/elements/n2go-page-tab-anchor.php' );
	require_once( 'visual-composer/elements/n2go-page-tab-anchors.php' );
	require_once( 'visual-composer/elements/n2go-page-tab.php' );
	require_once( 'visual-composer/elements/n2go-page-tabs.php' );
	require_once( 'visual-composer/elements/n2go-query-string-note.php' );
	require_once( 'visual-composer/elements/n2go-search-form.php' );
	require_once( 'visual-composer/elements/n2go-slider.php' );
	require_once( 'visual-composer/elements/n2go-slider-tab.php' );
	require_once( 'visual-composer/elements/n2go-blog-post-image-and-metadata.php' );
	require_once( 'visual-composer/elements/n2go-integrations.php' );

	function n2go_custom_vc_scripts() {

		if ( in_array( get_post_type(), vc_editor_post_types() ) ) {

			wp_register_script( 'n2go_js_composer_js_custom_views_n2go_page_tabs', get_template_directory_uri() . '/visual-composer/scripts/n2go-page-tabs-admin.js', array( 'wpb_js_composer_js_custom_views' ), WPB_VC_VERSION, true );
			wp_enqueue_script( 'n2go_js_composer_js_custom_views_n2go_page_tabs' );

			wp_register_style( 'n2go_js_composer_n2go_page_tabs', get_template_directory_uri() . '/visual-composer/styles/n2go-page-tabs.css', array( 'js_composer' ), WPB_VC_VERSION, false );
			wp_enqueue_style( 'n2go_js_composer_n2go_page_tabs' );


			wp_register_script( 'n2go_js_composer_js_custom_views_n2go_page_tab_anchors', get_template_directory_uri() . '/visual-composer/scripts/n2go-page-tab-anchors-admin.js', array( 'wpb_js_composer_js_custom_views' ), WPB_VC_VERSION, true );
			wp_enqueue_script( 'n2go_js_composer_js_custom_views_n2go_page_tab_anchors' );

			wp_register_style( 'n2go_js_composer_n2go_page_tab_anchors', get_template_directory_uri() . '/visual-composer/styles/n2go-page-tab-anchors.css', array( 'js_composer' ), WPB_VC_VERSION, false );
			wp_enqueue_style( 'n2go_js_composer_n2go_page_tab_anchors' );


			wp_register_script( 'n2go_js_composer_js_custom_views_n2go_slider', get_template_directory_uri() . '/visual-composer/scripts/n2go-slider-admin.js', array( 'wpb_js_composer_js_custom_views' ), WPB_VC_VERSION, true );
			wp_enqueue_script( 'n2go_js_composer_js_custom_views_n2go_slider' );

			wp_register_style( 'n2go_js_composer_n2go_slider', get_template_directory_uri() . '/visual-composer/styles/n2go-slider.css', array( 'js_composer' ), WPB_VC_VERSION, false );
			wp_enqueue_style( 'n2go_js_composer_n2go_slider' );
		}
	}

	add_action( 'admin_print_scripts-post.php', 'n2go_custom_vc_scripts' );
	add_action( 'admin_print_scripts-post-new.php', 'n2go_custom_vc_scripts' );

	add_filter( 'vc_load_default_templates', 'n2go_custom_template_modify_array' );

	function n2go_custom_template_modify_array( $data ) {
		$templates = array();

		$template = array();
		$template[ 'name' ] = __( 'Feature (Single)', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-feature-single';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1409132761054{margin-top: 10px !important;margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row css=".vc_custom_1409133723335{margin-bottom: 15px !important;}"][vc_column width="1/2"][vc_column_text css=".vc_custom_1409132168694{margin-bottom: 20px !important;}"]
                            <h1>1.000 Newsletter
                            gratis versenden</h1>
                            [/vc_column_text][vc_column_text]
                            <h2>Versenden Sie komplett kostenfrei.
                            Jetzt sofort und Monat für Monat.</h2>
                            [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<span style="font-weight: 600;">Vorteile der Newsletter2Go Software:</span>
                            <ul>
                            	<li>1.000 Newsletter kostenlos verschicken</li>
                            	<li>Accountsetup in nur 5 Minuten</li>
                            	<li>Alle Funktionen inklusive</li>
                            	<li>Keine Empfängerbegrenzung im Adressbuch</li>
                            </ul>
                            [/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][vc_column_text]<span style="font-weight: 600;">Newsletter2Go-Software im Detail:</span>

                            Profitieren Sie vom Einsatz professioneller E-Mail-Marketingsoftware – kostenlos und ohne Werbung und mit vollem Funktionsumfang.
                            Jeder Account bei Newsletter2Go verfügt über ein monatliches Freikontingent von 1.000 E-Mail-Credits.
                            Das heißt, Sie können Monat für Monat bis zu 1.000 E-Mails gratis mit der 100% kostenlosen Newsletter Software versenden!

                            Für Nutzer mit wenigen Empfängern oder geringer Versandhäufigkeit ist die Nutzung von Newsletter2Go deshalb komplett kostenlos – und das bei vollem Funktionsumfang und ohne Begrenzung der Adressbuchgröße.

                            Newsletter kostenlos versenden war noch nie einfacher. Keine teure Software, kein Abonnement und keine Verpflichtungen nötig. Nutzen Sie die professionelle Newsletter Software kostenlos. komplett kostenfrei.

                            Über das kostenlose Kontingent hinaus benötigte E-Mail- oder SMS-Credits können einfach und bequem in verschiedenen Paketgrößen direkt in Ihrem Account bestellt werden.[/vc_column_text][/vc_column][vc_column width="1/2"][vc_single_image border_color="grey" img_link_target="_self" img_size="full" image="3463" css=".vc_custom_1408979042436{margin-bottom: 20px !important;}"][vc_column_text]Sie werden benachrichtigt, sobald Sie Ihr Freikontingent überschreiten, und können weitere Credits hinzubuchen.

                            Es werden keine automatischen Bestellungen getätigt und alle weiteren Funktionen sind uneingeschränkt nutzbar.

                            Dank des Whitelisting von Newsletter2Go können Sie außerdem besonders zuverlässig Ihre Newsletter verschicken. So werden Ihre versendeten Newsletter nicht auf Spamverdacht geprüft, sondern direkt in das Postfach des Empfängers zugestellt.

                            Mit dieser Newsletter Software Freeware kommen Sie immer an.

                            Detaillierte Informationen zu den buchbaren Versandpaketen finden Sie in unserer Preisübersicht.[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2" css=".vc_custom_1409133630227{padding-top: 7px !important;}"][n2go_help_link link="url:https%3A%2F%2Fwww.newsletter2go.de%2Fde%2Fhilfe%2F1000-e-mails-monatlich-gratis|title:Warum%20gibt%20es%201.000%20E-Mails%20im%20Monat%20gratis%3F|" title="Hilfe Artikel - Warum gibt es 1.000 E-Mails im Monat gratis?"][/vc_column][vc_column width="1/2"][n2go_idea_link link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fblog%2Fnewsletter-freeware%2F|title:Newsletter-Freeware|" title="Wissenswertes zur Newsletter-Freeware"][/vc_column][/vc_row][vc_row][vc_column width="1/4"][n2go_bubble text="Kostenloser Support" icon="freesupport" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fkostenloser-support%2F|title:Kostenloser%20Support|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Newsletter Editor" icon="editor" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-editor%2F|title:Newsletter-Editor|"][/vc_column][vc_column width="1/4"][n2go_bubble text="CSA Whitelisting" icon="csa" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Femail-marketing-whitelisting%2F|title:Whitelisting|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Umfangreiche Listenverwaltung" icon="listmanagement" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Femail-marketing-extras%2F|title:E-Mail-Marketing%20mit%20Extras|"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Feature (Overview)', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-feature-overview';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408975950915{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1408975963927{margin-bottom: 20px !important;}"]
                            <h1>H1 Das ist die H1-Überschrift</h1>
                            [/vc_column_text][vc_column_text]
                            <h2>H2 Das ist die H2 Überschrift</h2>
                            [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<strong>Ihre Vorteile:</strong>
                            <ul>
                            	<li>Bullet-Point #1</li>
                            	<li>Bullet-Point #2</li>
                            	<li>Bullet-Point #3</li>
                            	<li>Bullet-Point #4</li>
                            </ul>
                            [/vc_column_text][vc_column_text css=".vc_custom_1408976120723{margin-bottom: 12px !important;}"]
                            <h3>H3 Bullet-Point #1</h3>
                            [/vc_column_text][vc_column_text css=".vc_custom_1408976949289{margin-bottom: 25px !important;}"]Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1[/vc_column_text][vc_single_image border_color="grey" img_link_target="_self" image="2046" img_size="full"][vc_column_text css=".vc_custom_1409046264198{margin-bottom: 12px !important;}"]
                            <h3>H3 Bullet-Point #2</h3>
                            [/vc_column_text][vc_column_text css=".vc_custom_1408976112774{margin-bottom: 25px !important;}"]Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1[/vc_column_text][vc_single_image border_color="grey" img_link_target="_self" image="3436" img_size="full"][vc_column_text css=".vc_custom_1409046271096{margin-bottom: 12px !important;}"]
                            <h3>H3 Bullet-Point #3</h3>
                            [/vc_column_text][vc_column_text css=".vc_custom_1408976112774{margin-bottom: 25px !important;}"]Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1
                            Textblock Bulletpoint #1[/vc_column_text][vc_single_image border_color="grey" img_link_target="_self" image="3436" img_size="full"][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column width="1/3" css=".vc_custom_1408970923693{padding-right: 20px !important;padding-left: 10px !important;}"][n2go_registration_form][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Features (Home)', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-features-home';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408952542614{padding-top: 20px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text]
                            <h1>Funktionen unserer E-Mail-Marketingsoftware</h1>
                            [/vc_column_text][vc_column_text]
                            <h2>Newsletter erstellen</h2>
                            [/vc_column_text][vc_column_text]Mit unserem intuitiven Newsletter-Editor erstellen Sie in wenigen Minuten professionelle Newsletter in Ihrem Corporate Design und Ihren Inhalten.

                            Passen Sie dafür einfach eine unserer vielzähligen kostenlosen Vorlagen an, bringen Sie Ihre eigene HTML-Datei mit oder lassen Sie von uns eine Vorlage nach Ihren Wünschen gestalten.

                            Mit Hilfe unserer Personalisierungstools bekommt dabei jeder Ihrer Empfänger seinen eigenen, individuellen Newsletter.
                            <a title="Newsletter erstellen –  einfach und professionell" href="http://185.21.103.80/de/newsletter-erstellen/">[mehr zum Erstellen von Newslettern]</a>[/vc_column_text][n2go_button title="Kostenlose anmelden und testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column width="1/2"][n2go_embed display_in_screen="yes" css=".vc_custom_1408951834396{margin-left: 30px !important;}"]JTNDaWZyYW1lJTIwaWQlM0QlMjJ5dHBsYXllciUyMiUyMHR5cGUlM0QlMjJ0ZXh0JTJGaHRtbCUyMiUyMHdpZHRoJTNEJTIyMzcwJTIyJTIwaGVpZ2h0JTNEJTIyMjA4JTIyJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZ3d3cueW91dHViZS5jb20lMkZlbWJlZCUyRkFud181b0lLT1ljJTNGcmVsJTNEMCUyNmFtcCUzQmNvbnRyb2xzJTNEMSUyNmFtcCUzQnNob3dpbmZvJTNEMCUyNmFtcCUzQmxvb3AlM0QxJTI2YW1wJTNCaGQlM0QxJTI2YW1wJTNCbW9kZXN0YnJhbmRpbmclM0QxJTI2YW1wJTNCZW5hYmxlanNhcGklM0QxJTI2YW1wJTNCcGxheWVyYXBpaWQlM0R5dHBsYXllciUyNmFtcCUzQmF1dG9wbGF5JTNEMSUyMiUyMGZyYW1lYm9yZGVyJTNEJTIyMCUyMiUyMGFsbG93ZnVsbHNjcmVlbiUzRCUyMiUyMiUzRSUzQyUyRmlmcmFtZSUzRQ==[/n2go_embed][/vc_column][vc_column css=".vc_custom_1408952884495{margin-bottom: 35px !important;}" width="1/1"][/vc_column][vc_column width="1/4"][n2go_bubble text="Newsletter Editor" icon="editor" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-editor%2F|title:Newsletter-Editor|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Kostenlose
                            Vorlagen" icon="templates" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fkostenlose-newsletter-templates%2F|title:Kostenlose%20HTML-Newsletter-Vorlagen|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Personalisierte
                            Newsletter" icon="personalized" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-personalisieren%2F|title:Personalisierter%20Newsletter-Versand|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Automatische
                            Webversion" icon="webversion" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-webversion-erstellen%2F|title:Automatische%20Webversion|"][/vc_column][/vc_row][vc_row css=".vc_custom_1408953000389{padding-top: 32px !important;padding-bottom: 32px !important;background-color: #f5f5f5 !important;}"][vc_column width="1/2"][vc_single_image image="1450" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/2"][vc_column_text]
                            <h2>Newsletter versenden</h2>
                            [/vc_column_text][vc_column_text]Unsere von der Certified Senders Alliance gewhitelisteten Server verschicken Ihre Newsletter zuverlässig und sicher zu Ihrem gewünschten Versandzeitpunkt.

                            Richten Sie auf Wunsch Follow-Up und Lifecycle-Mailings ein, verschicken Sie vor dem Versand an bis zu 10 Empfänger einen Test und testen Sie Newsletter2Go kosten- und risikolos mit monatlich 1.000 kostenlosen Inklusiv-E-Mails.
                            <a title="Professioneller Newsletter-Versand" href="http://185.21.103.80/de/newsletter-versand/">[mehr zum Versand von Newslettern]</a>[/vc_column_text][n2go_button title="Kostenlose anmelden und testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column css=".vc_custom_1408952884495{margin-bottom: 35px !important;}" width="1/1"][/vc_column][vc_column width="1/4"][n2go_bubble text="Zeitgesteuerter
                            Versand" icon="schedule" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fsms-newsletter-zeitversetzt-versenden%2F|title:Zeitgesteuerter%20Versand|"][/vc_column][vc_column width="1/4"][n2go_bubble text="CSA
                            Whitelisting" icon="csa" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-whitelisting%2F|title:Certified%20Senders%20Alliance|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Autoresponder &amp; Follow-Ups" icon="autoresponder" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fautoresponder-follow-up-mailings%2F|title:Autoresponder%20%26%20Follow-up%20Emails|"][/vc_column][vc_column width="1/4"][n2go_bubble text="1.000 Newsletter gratis" icon="forfree" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Ffeatures%2Fnewsletter-kostenlos-versenden%2F|title:1.000%20Newsletter%20kostenlos%20versenden|"][/vc_column][/vc_row][vc_row css=".vc_custom_1408957116810{padding-top: 32px !important;padding-bottom: 32px !important;}"][vc_column width="1/2"][vc_column_text]
                            <h2>Empfänger verwalten</h2>
                            [/vc_column_text][vc_column_text]Laden Sie einfach Ihre Empfängerdaten mit einem Klick per Excel-, Open Office- oder CSV-Datei in Ihr Newsletter2Go-Adressbuch, importieren Sie beliebige Empfängerfelder wie Name, Geschlecht oder Geburtsdatum und segmentieren Sie Ihre Empfänger innerhalb weniger Sekunden in Gruppen, die Sie dann gezielt ansprechen.Passen Sie dafür einfach eine unserer vielzähligen kostenlosen Vorlagen an, bringen Sie Ihre eigene HTML-Datei mit oder lassen Sie von uns eine Vorlage nach Ihren Wünschen gestalten.

                            Mit unserem Abmelde- und Bouncemanagement halten wir dabei vollautomatisch Ihre Empfängerliste immer auf dem aktuellsten Stand und ermöglichen Ihnen sich auf das Wesentliche zu konzentrieren.
                            <a title="Newsletter-Programm  von Newsletter2Go" href="http://185.21.103.80/de/newsletter-programm/">[mehr zum Newsletter Programm]</a>[/vc_column_text][n2go_button title="Kostenlose anmelden und testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column width="1/2"][vc_single_image image="1451" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column css=".vc_custom_1408952884495{margin-bottom: 35px !important;}" width="1/1"][/vc_column][vc_column width="1/4"][n2go_bubble text="Umfangreiche
                            Listenverwaltung" icon="listmanagement" link="||"][/vc_column][vc_column width="1/4"][n2go_bubble text="Bounce-Management" icon="bouncemanagement" link="||"][/vc_column][vc_column width="1/4"][n2go_bubble text="Abmelde-Management" icon="unsubscribes" link="||"][/vc_column][vc_column width="1/4"][n2go_bubble text="1-Klick Importfunktion" icon="import" link="||"][/vc_column][/vc_row][vc_row css=".vc_custom_1408953000389{padding-top: 32px !important;padding-bottom: 32px !important;background-color: #f5f5f5 !important;}"][vc_column width="1/2"][vc_single_image image="1452" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/2"][vc_column_text]
                            <h2>Newsletter auswerten</h2>
                            [/vc_column_text][vc_column_text]<span style="color: #666666;">Mit unserem umfangreichen Tracking erhalten Sie alle Informationen, die Sie für die stetige Optimierung Ihres Newsletters benötigen.</span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Von Öffnungs- über Klick- und Bouncerate, Geotracking und Clickmap bis hin zum multivariaten Clustering finden Sie alles, was Ihr Analytiker-Herz begehrt. </span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Und wenn Sie Ihre Ergebnisse präsentieren, teilen oder archivieren möchten, exportieren Sie einfach alle Daten als anschauliche PDF-Datei.</span>[/vc_column_text][n2go_button title="Kostenlose anmelden und testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column css=".vc_custom_1408952884495{margin-bottom: 35px !important;}" width="1/1"][/vc_column][vc_column width="1/4"][n2go_bubble text="Öffnungsrate &amp;
                            Klickrate" icon="openandclick" link="url:https%3A%2F%2Fwww.newsletter2go.de%2Fde%2Ffeatures%2Fnewsletter-tracking|title:%C3%96ffnungsrate%20%26%20Klickrate|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Visuelles
                            Geotracking" icon="geotracking" link="url:https%3A%2F%2Fwww.newsletter2go.de%2Fde%2Ffeatures%2Fnewsletter-geotracking|title:Visuelles%20Geotracking|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Multivariates
                            Clustering" icon="clustering" link="url:https%3A%2F%2Fwww.newsletter2go.de%2Fde%2Ffeatures%2Fnewsletter-multivariates-tracking|title:Multivariates%20Clustering|"][/vc_column][vc_column width="1/4"][n2go_bubble text="Google Analytics
                            Integration" icon="googleanalytics" link="url:https%3A%2F%2Fwww.newsletter2go.de%2Fde%2Ffeatures%2Fnewsletter-google-analytics-integration|title:Google%20Analytics%20Integration|"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );

		$template = array();
		$template[ 'name' ] = __( 'Home', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-home';
		$template[ 'content' ] = '[vc_row el_class="n2go-fullRow"][vc_column width="1/1"][n2go_slider height="370px" interval="10"][n2go_slider_tab title="Newsletter Software" tab_id="1410096700567-8" image="4376"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410097351800{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                Professionelle Newsletter
                                einfach verschicken.
                                [/vc_column_text][vc_column_text css=".vc_custom_1410097453302{margin-bottom: 30px !important;}"]Mit unserer leistungsstarken Newsletter-Software erstellen,
                                versenden und evaluieren Sie Ihren Newsletter einfacher,
                                schneller und besser als jemals zuvor.
                                Direkt im Browser und innerhalb weniger Minuten![/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="E-Commerce-Plugon" tab_id="1410096700662-3" image="4378"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410097607612{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                E-Shop und CRM Integrationen
                                für Ihr E-Mail-Marketing
                                [/vc_column_text][vc_column_text css=".vc_custom_1410097635082{margin-bottom: 30px !important;}"]Newsletter2Go bietet Ihnen umfangreiche Integrationen
                                in CRM- und E-Commerce-Systeme sowie eine ausführlich
                                dokumentierte API. Integrieren Sie so unser zertifiziertes
                                Versandsystem direkt in Ihre Anwendungen.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="Zertifizierungen" tab_id="1410096973505-2-2" image="4380"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410097882947{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                99,5% unserer Kunden
                                bewerten uns mit sehr gut.
                                [/vc_column_text][vc_column_text css=".vc_custom_1410097906036{margin-bottom: 30px !important;}"]Nicht nur der Service von Newsletter2Go ist für seine Qualität und
                                Zuverlässigkeit ausgezeichnet - auch unser Produkt ist für einen sicheren
                                Versand bei der DDV, CSA und Return-Path- zertifiziert. Damit können wir
                                ohne Spam-Prüfung Ihre E-Mails direkt in die Inbox Ihrer Empfänger zustellen.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="Reports" tab_id="1410097009027-3-6" image="4381"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410097988985{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                Reports, mit denen Sie Ihre
                                Zahlen im Griff haben.
                                [/vc_column_text][vc_column_text css=".vc_custom_1410098017461{margin-bottom: 30px !important;}"]Unsere umfangreichen Statistiken von Öffnungsrate,
                                über Clickmap bis hin zum multivariaten Clustering
                                ermöglichen Ihnen genaue Aussagen über den Erfolg
                                und Optimierungsmöglichkeiten Ihrer E-Mail-Kampagnen.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="Responsive Design" tab_id="1410097034262-4-0" image="4382"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410098123454{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                Responsive Design
                                für Ihren Newsletter
                                [/vc_column_text][vc_column_text css=".vc_custom_1410098151404{margin-bottom: 30px !important;}"]Bereits rund 50% aller E-Mails werden auf mobilen Endgeräten
                                gelesen. Bei Newsletter2Go werden Ihre E-Mail-Kampagnen
                                automatisch mit responsive Design angelegt. Dies passt
                                die Auflösung automatisch an die Größe des Endgerätes an.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="Life Cycle Mailings" tab_id="1410097069486-5-7" image="4384"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410098642572{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                Trigger- und Lifecycle
                                Kampagnen in nur 5 Minuten
                                [/vc_column_text][vc_column_text css=".vc_custom_1410098672190{margin-bottom: 30px !important;}"]Newsletter2Go macht Trigger E-Mails endlich einfach.
                                Versenden Sie anlassbezogene E-Mails nach vorab
                                definierten Regeln. Geburtstagsmailings oder ähnliche um
                                den ROI Ihres E-Mail-Marketing Kanals zu steigern.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][n2go_slider_tab title="Risikolos testen" tab_id="1410097109749-6-9" image="4385"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1410097360806{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1410098730608{margin-bottom: 0px !important;padding-top: 32px !important;padding-bottom: 4px !important;}"]

                                1.000 Newsletter gratis
                                und kostenloser Support
                                [/vc_column_text][vc_column_text css=".vc_custom_1410098748594{margin-bottom: 30px !important;}"]Bei Newsletter2Go kaufen Sie nicht die Katze im Sack.
                                Melden Sie sich unverbindlich an und testen Sie unsere
                                Newsletter-Software. Sollten Sie Fragen haben, stehen wir
                                Ihnen kostenlos im Support zu Verfügung.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/n2go_slider_tab][/n2go_slider][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]

                                Unsere E-Mail Marketing Software und Newsletter Software bringt Ihr Unternehmen voran
                                [/vc_column_text][/vc_column][/vc_row][vc_row el_class="n2go-fiveColumnRow"][vc_column width="1/6"][n2go_company_type icon="ecommerce" title="Für E-Commerce Shops" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fecommerce-newsletter-software%2F|title:Die%20Software%20f%C3%BCr%20E-Commerce|"][/vc_column][vc_column width="1/6"][n2go_company_type icon="kmu" title="Für KMU" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fnewsletter-fuer-kmu%2F|title:E-Mail-Marketing%20f%C3%BCr%20KMU|"][/vc_column][vc_column width="1/6"][n2go_company_type icon="agency" title="Für Agenturen" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fnewsletter-fuer-agenturen%2F|title:E-Mail-Marketing%20f%C3%BCr%20Agenturen|"][/vc_column][vc_column width="1/6"][n2go_company_type icon="ngo" title="Für Vereine" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fnewsletter-fuer-vereine%2F|title:E-Mail-Marketing%20f%C3%BCr%20Vereine%20und%20NGO|"][/vc_column][vc_column width="1/6"][n2go_company_type icon="bigfirm" title="Für Großunternehmen" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fnewsletter-fuer-unternehmen%2F|title:E-Mail-Marketing%20f%C3%BCr%20Gro%C3%9Funternehmen|"][/vc_column][/vc_row][vc_row css=".vc_custom_1410104418084{padding-top: 20px !important;padding-bottom: 20px !important;background-color: #f8f8f8 !important;}"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]

                                Mehr als 31.000 Kunden vertrauen uns

                                [/vc_column_text][vc_single_image image="1665" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/referenzen"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]

                                Auszeichnungen und Zertifizierungen

                                [/vc_column_text][vc_single_image image="1666" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/features/email-marketing-whitelisting"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text]

                                Die wohl beste Newsletter Software

                                Die Newsletter Software von Newsletter2Go erfreut sich höchster Kundenzufriedenheit. Mehr als 99,5% unserer Kunden empfehlen unsere Software für einen professionellen Versand von Newslettern. Mit über 31.000 zufriedenen Kunden, sind wir bei Service und Technologie führend.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default"][/vc_column][vc_column width="1/2"][vc_row_inner][vc_column_inner width="1/2"][vc_single_image image="1668" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="1/2"][vc_raw_html]JTNDaW1nJTIwYm9yZGVyJTNEJTIyMCUyMiUyMHNyYyUzRCUyMmh0dHBzJTNBJTJGJTJGY29ubmVjdC5la29taS5kZSUyRndpZGdldCUyRjQyMkEyMEE1RDFDRjI2RS03LmdpZiUyMiUyMGFsdCUzRCUyMmVLb21pJTIwLSUyMFRoZSUyMEZlZWRiYWNrJTIwQ29tcGFueSUzQSUyMEVpbmZhY2hlcyUyMFN5c3RlbSUyQyUyMHdlbGNoZXMlMjBhYmVyJTIwaW1tZXIlMjB3ZWl0ZXJlbnR3aWNrZWx0JTIwd2lyZCUyMiUzRQ==[/vc_raw_html][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_row_inner][vc_column_inner width="1/1"][vc_single_image image="1669" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/2"][vc_column_text]

                                Professionelle E-Mail Marketing Software

                                Erstellen und gestalten Sie anspruchsvolle E-Mail Marketing Kampagnen direkt in Ihrem Browser. Mit unserer Software ist dies problemlos möglich. Verwalten Sie Ihre Empfänger und versenden Sie zielgerichtete Lifecycle Mailings mit unserer Newsletter Software. Newsletter2Go steht für ein verkaufsstarkes E-Mail Marketing Tool.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text]

                                Kostenlose Responsive Designs

                                Mit der E-Mail Marketing Software von Newsletter2Go stehen Ihnen über 50 kostenlose und getestete Newsletter Vorlagen zu Verfügung. Diese sind außerdem responsive und werden bei Smartphones optimiert dargestellt. Auf Wunsch erstellen wir Ihnen auch Ihre individuelle Newsletter Vorlage.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default"][/vc_column][vc_column width="1/2"][vc_row_inner][vc_column_inner width="1/1"][vc_single_image image="1671" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_row_inner][vc_column_inner width="1/1"][vc_single_image image="1672" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/2"][vc_column_text]

                                Newsletter erstellen, versenden
                                und den Erfolg messen

                                Erstellen und versenden Sie professionelle Newsletter in nur wenigen Minuten. Im Anschluss an den Versand können Sie den Erfolg Ihres Newsletters einfach auswerten. Sie können Öffnungsrate, Klickrate, Conversion-Rate und vieles mehr zielgruppengenau analysieren. Auch eine Verknüpfung mit Google Analytics oder econda ist mit nur einem Klick möglich.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text]

                                Nahtlose E-Commerce Anbindung

                                Ihre Empfänger aus dem Shopsystem synchronisieren automatisch mit der E-Mail Marketing Software von Newsletter2Go. Produkte aus Ihrem Webshop übernehmen Sie mit nur einem Klick in Ihren Newsletter. Erfahren Sie mehr zu den beliebten E-Commerce Newsletter-Integrationen.[/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default"][/vc_column][vc_column width="1/2"][vc_row_inner][vc_column_inner width="1/1"][vc_single_image image="1673" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]

                                Ekomi-Siegel Newsletter2GoNewsletter2Go E-Mail Marketing Software: 4.8 von 5 basierend auf 424 Bewertungen
                                [/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1409064595536{margin-bottom: 0px !important;padding-bottom: 15px !important;background-color: #f5f5f5 !important;}"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]Was ist Newsletter2Go?

                                Newsletter2Go ist eine Online Newsletter Software, mit der Unternehmen, Vereine, öffentliche Einrichtungen, Eventveranstalter, Online-Shops uvm. professionelle und personalisierte SMS-Newsletter und Email-Newsletter versenden können. Die Email Newsletter erstellen Sie direkt im Browser, d.h. Sie müssen keine Email Newsletter Softwareinstallieren, sondern können Newsletter2Go von jedem Computer der Welt aus bedienen.
                                Nach dem Newsletter-Versand können Sie mit Hilfe der detaillierten Reports den Erfolg des Newsletters analysieren und die Klickrate optimieren und die Öffnungsrate optimieren.

                                [/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]Wer nutzt Newsletter2Go?

                                Unsere E-Mail Marketing Software ist sowohl bei erfahrenen Marketing Experten als auch bei Startups und mittelständischen Unternehmen beliebt. Weltweit haben sich bereits tausende von Unternehmen für Newsletter2Go entschieden.
                                Probieren auch Sie unsere Software zum Newsletterversand! Jeder Kunde erhält jeden Monat 1000 Newsletter kostenlos!
                                Und sollten Sie einmal Fragen zu unserer Software Newsletter oder zu dem Thema Newsletter-Versand haben, steht Ihnen unser professionelles Support-Team jederzeit gerne per Email oder telefonisch (kostenlos) zur Verfügung.

                                [/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );

		$template = array();
		$template[ 'name' ] = __( 'Pricing', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-pricing';
		$template[ 'content' ] = '[vc_row][vc_column width="1/1"][n2go_page_tabs][n2go_page_tab title="E-Mail Preise" tab_id="1409571825188-5"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409574228598{padding-top: 14px !important;}"][n2go_breadcrumbs][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1"][vc_column_text]
                                <h2>E-Mail-Preise</h2>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409316506351{margin-bottom: 12px !important;}"][vc_column_inner width="1/2"][vc_column_text]
                                <h3>Prepaid-Tarif</h3>
                                [/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
                                <h3>Abonnement-Tarif</h3>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/2"][vc_column_text el_class="n2go-textContainingCheckmarkList" css=".vc_custom_1409909927807{margin-bottom: 25px !important;}"]
                                <div class="check" style="color: #666666;">
                                <ul>
                                	<li>Keine Vertragsbindung</li>
                                	<li>Kein monatlicher Mindestumsatz</li>
                                	<li>Keine Grundgebühr</li>
                                	<li>Keine Zusatzkosten</li>
                                	<li>Emails verfallen nie</li>
                                	<li>Image-Hosting kostenlos und unbegrenzt</li>
                                </ul>
                                </div>
                                [/vc_column_text][n2go_info_box css=".vc_custom_1409909578663{padding-right: 20px !important;}"]Ideal, wenn Sie selten, unregelmäßig oder mit stark schwankendem Volumen versenden[/n2go_info_box][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text el_class="n2go-textContainingCheckmarkList" css=".vc_custom_1409909936901{margin-bottom: 25px !important;}"]
                                <ul>
                                	<li>1 Monat Vertragslaufzeit</li>
                                	<li>Zum Laufzeitende kündbar / wechselbar ohne Kündigungsfrist</li>
                                	<li>Automatische Verlängerung um 1 Monat</li>
                                	<li>Keine Zusatzkosten</li>
                                	<li>Nicht verbrauchte Emails verfallen am Monatsende</li>
                                	<li>Image-Hosting kostenlos und unbegrenzt</li>
                                </ul>
                                [/vc_column_text][n2go_info_box css=".vc_custom_1409909587173{padding-right: 20px !important;}"]Ideal, wenn Sie häufig, regelmäßig und mit gleichbleibendem Volumen versenden[/n2go_info_box][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/2"][vc_table vc_table_theme="classic"][b;18px]E-Mail-Pakete,[18px;b;bg#e9f6fb;align-center]TKP,[18px;b;bg#def2f9;align-center]Paketpreis|[b;bg#ffffff]1.000%20Emails%20jeden%20Monat,[bg#e8f9eb;b;align-center;12px]0%2C00%20%E2%82%AC,[bg#d4f4d7;b;align-center]Gratis|[bg#ffffff]Dar%C3%BCber%20hinausgehende%20Emails%3A,[bg#e9f6fb;align-center;12px],[bg#def2f9;b;align-center]|[bg#ffffff]1.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]10%2C00%20%E2%82%AC,[bg#def2f9;b;align-center]10%2C00%20%E2%82%AC|[bg#ffffff]5.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]6%2C00%20%E2%82%AC,[bg#def2f9;b;align-center]30%2C00%20%E2%82%AC|[bg#ffffff]10.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]5%2C00%20%E2%82%AC,[bg#def2f9;b;align-center]50%2C00%20%E2%82%AC|[bg#ffffff]25.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]4%2C80%20%E2%82%AC,[bg#def2f9;b;align-center]120%2C00%20%E2%82%AC|[bg#ffffff]50.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]4%2C40%20%E2%82%AC,[bg#def2f9;b;align-center]220%2C00%20%E2%82%AC|[bg#ffffff]100.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]3%2C50%20%E2%82%AC,[bg#def2f9;b;align-center]350%2C00%20%E2%82%AC|[bg#ffffff]500.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]2%2C80%20%E2%82%AC,[bg#def2f9;b;align-center]1.400%2C00%20%E2%82%AC|[bg#ffffff]1.000.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]2%2C24%20%E2%82%AC,[bg#def2f9;b;align-center]2.240%2C00%20%E2%82%AC|[bg#ffffff]1.500.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]1%2C79%20%E2%82%AC,[bg#def2f9;b;align-center]2.690%2C00%20%E2%82%AC|[bg#ffffff]3.000.000%20Emails,[bg#e9f6fb;align-center;12px;c#5e5e70]1%2C48%20%E2%82%AC,[bg#def2f9;b;align-center]4.440%2C00%20%E2%82%AC[/vc_table][vc_column_text]
                                <h3>Prepaid-Rechner</h3>
                                Wir bieten Ihnen im Prepaid-Tarif auch beliebige Zwischengrößen an.

                                Geben Sie einfach ein, wieviele E-Mail-Credits Sie bestellen möchten, wir rechnen für Sie den Preis aus:[/vc_column_text][vc_raw_html]JTNDaW5wdXQlMjB0eXBlJTNEJTIydGV4dCUyMiUyMG5hbWUlM0QlMjJpbmRpdmlkdWFsUHJlcGFpZFNpemUlMjIlMjBpZCUzRCUyMmluZGl2aWR1YWxQcmVwYWlkU2l6ZSUyMiUyMCUyRiUzRSUyMCUzQ2J1dHRvbiUyMGNsYXNzJTNEJTI3YnRuJTI3JTIwaWQlM0QlMjJidG5JbmRpdmlkdWFsUHJlcGFpZFNpemUlMjIlMjAlM0VCZXJlY2huZW4lM0MlMkZidXR0b24lM0UlM0NiciUyRiUzRSUwQSUwOSUwOSUwOSUwOSUzQ2JyJTJGJTNFJTBBJTA5JTA5JTA5JTA5JTNDYiUzRUJlcmVjaG5ldGVyJTIwUHJlaXMlM0MlMkZiJTNFJTNBJTIwJTNDc3BhbiUyMGlkJTNEJTIycmVzdWx0SW5kaXZpZHVhbFByZXBhaWRTaXplJTIyJTNFMCUyMEVVUiUzQyUyRnNwYW4lM0U=[/vc_raw_html][/vc_column_inner][vc_column_inner width="1/2"][vc_table vc_table_theme="classic"][b;18px]Abo-Pakete,[18px;b;bg#e9f6fb]TKP,[18px;b;bg#def2f9]Paketpreis|[b;bg#ffffff]1.000%20Emails%20jeden%20Monat,[bg#e8f9eb;align-center;b;12px]0%2C00%20%E2%82%AC,[bg#d4f4d7;align-center;b]Gratis|[bg#ffffff]Dar%C3%BCber%20hinausgehende%20Emails%3A,[bg#e9f6fb;align-center;12px],[bg#def2f9;align-center;b]|[bg#ffffff]1.000%20Emails,[bg#e9f6fb;align-center;12px]8%2C00%20%E2%82%AC,[bg#def2f9;align-center;b]8%2C00%20%E2%82%AC|[bg#ffffff]5.000%20Emails,[bg#e9f6fb;align-center;12px]4%2C00%20%E2%82%AC,[bg#def2f9;align-center;b]20%2C00%20%E2%82%AC|[bg#ffffff]10.000%20Emails,[bg#e9f6fb;align-center;12px]4%2C00%20%E2%82%AC,[bg#def2f9;align-center;b]40%2C00%20%E2%82%AC|[bg#ffffff]25.000%20Emails,[bg#e9f6fb;align-center;12px]3%2C20%20%E2%82%AC,[bg#def2f9;align-center;b]80%2C00%20%E2%82%AC|[bg#ffffff]50.000%20Emails,[bg#e9f6fb;align-center;12px]3%2C00%20%E2%82%AC,[bg#def2f9;align-center;b]150%2C00%20%E2%82%AC|[bg#ffffff]100.000%20Emails,[bg#e9f6fb;align-center;12px]2%2C70%20%E2%82%AC,[bg#def2f9;align-center;b]270%2C00%20%E2%82%AC|[bg#ffffff]500.000%20Emails,[bg#e9f6fb;align-center;12px]0%2C96%20%E2%82%AC,[bg#def2f9;align-center;b]480%2C00%20%E2%82%AC|[bg#ffffff]1.000.000%20Emails,[bg#e9f6fb;align-center;12px]0%2C57%20%E2%82%AC,[bg#def2f9;align-center;b]570%2C00%20%E2%82%AC|[bg#ffffff]1.500.000%20Emails,[bg#e9f6fb;align-center;12px]0%2C47%20%E2%82%AC,[bg#def2f9;align-center;b]710%2C00%20%E2%82%AC|[bg#ffffff]3.000.000%20Emails,[bg#e9f6fb;align-center;12px]0%2C40%20%E2%82%AC,[bg#def2f9;align-center;b]1.200%2C00%20%E2%82%AC[/vc_table][vc_column_text]
                                <h3>Abo-Rechner</h3>
                                Wir bieten Ihnen im Abonnement-Tarif Zwischengrößen in 5000er-Schritten an.
                                Geben Sie einfach ein, wieviele E-Mails Sie monatlich versenden möchten, wir rechnen für Sie den Preis aus:[/vc_column_text][vc_raw_html]JTNDaW5wdXQlMjB0eXBlJTNEJTIydGV4dCUyMiUyMG5hbWUlM0QlMjJpbmRpdmlkdWFsUHJlcGFpZFNpemUlMjIlMjBpZCUzRCUyMmluZGl2aWR1YWxQcmVwYWlkU2l6ZSUyMiUyMCUyRiUzRSUyMCUzQ2J1dHRvbiUyMGNsYXNzJTNEJTI3YnRuJTI3JTIwaWQlM0QlMjJidG5JbmRpdmlkdWFsUHJlcGFpZFNpemUlMjIlMjAlM0VCZXJlY2huZW4lM0MlMkZidXR0b24lM0UlM0NiciUyRiUzRSUwQSUwOSUwOSUwOSUwOSUzQ2JyJTJGJTNFJTBBJTA5JTA5JTA5JTA5JTNDYiUzRUJlcmVjaG5ldGVyJTIwUHJlaXMlM0MlMkZiJTNFJTNBJTIwJTNDc3BhbiUyMGlkJTNEJTIycmVzdWx0SW5kaXZpZHVhbFByZXBhaWRTaXplJTIyJTNFMCUyMEVVUiUzQyUyRnNwYW4lM0U=[/vc_raw_html][/vc_column_inner][/vc_row_inner][/n2go_page_tab][n2go_page_tab title="SMS Preise" tab_id="1409571825290-2"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409574228598{padding-top: 14px !important;}"][n2go_breadcrumbs][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1"][vc_column_text]
                                <h2>SMS-Preise</h2>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409316530070{margin-bottom: 12px !important;}"][vc_column_inner width="1/2"][vc_column_text]
                                <h3>SMS Basic</h3>
                                [/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
                                <h3>SMS-Plus</h3>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/2"][vc_column_text el_class="n2go-textContainingCheckmarkList" css=".vc_custom_1409910116522{margin-bottom: 58px !important;}"]
                                <ul>
                                	<li>Versand deutschlandweit</li>
                                	<li>deutsche Routen</li>
                                	<li>30 Sekunden maximale Versanddauer</li>
                                	<li>1530 maximale Anzahl Zeichen</li>
                                </ul>
                                [/vc_column_text][n2go_info_box]1 SMS = 1 Credit[/n2go_info_box][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                <ul>
                                	<li>Versand weltweit</li>
                                	<li>High Quality Routen</li>
                                	<li>10 Sekunden maximale Versanddauer</li>
                                	<li>1530 maximale Anzahl Zeichen</li>
                                	<li>eigene Absenderkennung</li>
                                </ul>
                                [/vc_column_text][n2go_info_box]1 SMS = 2 Credits[/n2go_info_box][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/2"][vc_table vc_table_theme="classic"][b;14px]SMS%20Basic%20Pakete,[14px;b]pro%20St%C3%BCck,[14px;b]Paketpreis|100%20SMS%20Credits%20%3D%20100%20SMS,0%2C040%20%E2%82%AC,4%2C00%20%E2%82%AC|1.000%20SMS%20Credits%20%3D%201.000%20SMS,0%2C034%20%E2%82%AC,34%2C00%20%E2%82%AC|5.000%20SMS%20Credits%20%3D%205.000%20SMS,0%2C032%20%E2%82%AC,160%2C00%20%E2%82%AC|10.000%20SMS%20Credits%20%3D%2010.000%20SMS,0%2C030%20%E2%82%AC,300%2C00%20%E2%82%AC|50.000%20SMS%20Credits%20%3D%2050.000%20SMS,0%2C029%20%E2%82%AC,1.450%2C00%20%E2%82%AC|100.000%20SMS%20Credits%20%3D%20100.000%20SMS,0%2C027%20%E2%82%AC,2.700%2C00%20%E2%82%AC[/vc_table][/vc_column_inner][vc_column_inner width="1/2"][vc_table vc_table_theme="classic"][b;14px]SMS-Plus,[14px;b]pro%20St%C3%BCck,[14px;b]Paketpreis|100%20SMS%20Credits%20%3D%2050%20SMS,0%2C080%20%E2%82%AC,4%2C00%20%E2%82%AC|1.000%20SMS%20Credits%20%3D%20500%20SMS,0%2C068%20%E2%82%AC,34%2C00%20%E2%82%AC|5.000%20SMS%20Credits%20%3D%202.500%20SMS,0%2C064%20%E2%82%AC,160%2C00%20%E2%82%AC|10.000%20SMS%20Credits%20%3D%205.000%20SMS,0%2C060%20%E2%82%AC,300%2C00%20%E2%82%AC|50.000%20SMS%20Credits%20%3D%2025.000%20SMS,0%2C058%20%E2%82%AC,1.450%2C00%20%E2%82%AC|100.000%20SMS%20Credits%20%3D%2050.000%20SMS,0%2C054%20%E2%82%AC,2.700%2C00%20%E2%82%AC[/vc_table][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409317353895{margin-bottom: 0px !important;}"][vc_column_text]

                                Für größere Pakete kontaktieren Sie uns bitte unter <a title="Kontakt aufnehmen" href="mailto:support@newsletter2go.de">support@newsletter2go.de</a>
                                Angebote richten sich an gewerbliche Kunden. Alle Preise in Euro und zzgl. 19% ges. MwSt.

                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][/vc_column_inner][vc_column_inner width="1/3"][n2go_button title="Kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][vc_column_inner width="1/3"][/vc_column_inner][/vc_row_inner][/n2go_page_tab][n2go_page_tab title="Newsletter-Vorlagen" tab_id="1409571880206-2-7"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409574228598{padding-top: 14px !important;}"][n2go_breadcrumbs][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1"][vc_column_text css=".vc_custom_1409316793512{margin-bottom: 12px !important;}"]
                                <h2>HTML-Newsletter-Vorlage</h2>
                                [/vc_column_text][vc_column_text]Wenn Sie als Alternative zu unseren <a href="https://www.newsletter2go.de/de/features/kostenlose-newsletter-templates">kostenlosen Newsletter-Vorlagen</a> eine Vorlage in Ihrem eigenen Layout (corporate design) wünschen, bieten wir Ihnen die Programmierung dieser Vorlage an:[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1"][vc_column_text el_class="n2go-textContainingCheckmarkList" css=".vc_custom_1409910193616{margin-bottom: 25px !important;}"]
                                <div class="check" style="color: #666666;">
                                <ul>
                                	<li><span>Newsletteroptimiertes HTML</span></li>
                                	<li><span>100% handgecodet</span></li>
                                	<li><span>Getestet in 7 Web Clients (AOL, Comcast, Gmail, GMX, Libero, Outlook.com, Yahoo)</span></li>
                                	<li><span>Getestet in 13 Desktop-Clients (Apple Mail 4/5/6/7, Live Mail, Lotus Notes 8/8.5, Outlook 2003/2007/2010/2013, Thunderbird 13, Windows Mail)</span></li>
                                	<li><span>Getestet in 9 Mobile-Clients (Android 2.3/4, BlackBerry 9930, Gmail App iOS 7, iPad 5.0, iPhone iOS 6/6.1/7, Kindle Fire 2.3)</span></li>
                                </ul>
                                </div>
                                [/vc_column_text][n2go_info_box]Die Newsletter-Vorlage wird in Ihrem Design (z.B. aus einer Photoshop-Datei oder einem Bild Ihrer Homepage) in HTML umgesetzt und in Ihrem Newsletter2Go-Account hinterlegt. Sie können dann Inhalte wie Bilder, Texte und Links individuell anpassen.[/n2go_info_box][vc_table vc_table_theme="classic"][b;14px]HTML-Newsletter-Vorlage,[14px;b]einmalig,[14px;b]249%2C00%20%E2%82%AC|[b]HTML-Newsletter-Vorlage%20mit%20Webshop-Integration,einmalig,349%2C00%20%E2%82%AC|[b]Responsive%20Design%20(optimiert%20f%C3%BCr%20Smartphones%20und%20Tablets),[]zus%C3%A4tzlich%20einmalig,%2B%2099%2C00%20%E2%82%AC[/vc_table][vc_column_text]

                                Bitte buchen Sie die HTML-Newsletter-Vorlage über Ihren Newsletter2Go-Account und schicken Ihren Designvorschlag an <a href="mailto:templates@newsletter2go.de">templates@newsletter2go.de</a> .
                                Die Umsetzung Ihrer Vorlage dauert normalerweise werktags maximal 48 Stunden.
                                Angebote richten sich an gewerbliche Kunden. Alle Preise in Euro und zzgl. 19% ges. MwSt.

                                [/vc_column_text][/vc_column_inner][/vc_row_inner][/n2go_page_tab][n2go_page_tab title="Dedizierte IPs" tab_id="1409571897446-3-4"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409574228598{padding-top: 14px !important;}"][n2go_breadcrumbs][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409317055330{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1409317475731{margin-bottom: 12px !important;}"]
                                <h2>Exklusive dedizierte IPs</h2>
                                [/vc_column_text][vc_column_text]Wir bieten Ihnen auch optional die Möglichkeit an, Ihre E-Mail-Marketing-Kampagnen über dedizierte IPs zu versenden. Das heißt, wir können Ihnen beliebig viele IPs zur Verfügung stellen, die nur für Ihren Newsletterversand verwendet werden. Damit erreichen Sie noch bessere Zustellraten und maximieren den Erfolg Ihrer Newsletter.
                                <a title="Exklusive dedizierte IPs" href="http://185.21.103.80/de/features/newsletter-versand-dedizierte-ips/">Erfahren Sie mehr über dedizierte IP\'s</a>[/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                <ul>
                                	<li>Exklusive dedizierte IPs</li>
                                	<li>24/7 IP-Reputation Monitoring</li>
                                	<li>Verwendung von DKIM, SPF und DMARC</li>
                                	<li>CSA-Zertifizierung</li>
                                	<li>Return-Path-Zertifizierung mit über 2.500.000.000 gewhitelisteten Inboxes möglich</li>
                                	<li>vorheriges Aufwärmen der IPs</li>
                                </ul>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][/vc_column_inner][vc_column_inner width="1/3"][n2go_button title="Bei Interesse sprechen Sie uns bitte an" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fkontakt%2F|title:Kontakt|"][/vc_column_inner][vc_column_inner width="1/3"][/vc_column_inner][/vc_row_inner][/n2go_page_tab][n2go_page_tab title="Whitelabel Lösung" tab_id="1409571918718-4-5"][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409574228598{padding-top: 14px !important;}"][n2go_breadcrumbs][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/1" css=".vc_custom_1409317055330{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1409317665058{margin-bottom: 12px !important;}"]
                                <h2>Whitelabel Lösung</h2>
                                [/vc_column_text][vc_column_text]Newsletter2Go bietet Ihnen die Möglichkeit unsere professionelle E-Mail Marketing Software zu white labeln. Sie möchten beispielsweise als Agentur Ihren Kunden eine einfache Software zur Newslettererstellung anbieten? Dann können Sie unsere Newsletter Software unter Ihrem Namen vermarkten. Tauschen Sie das Newsletter2Go Logo gegen Ihr eigenes und verwenden Sie Ihr individuelles Farbschema. Legen Sie einfach Benutzer an und verwalten Sie deren Rechte.
                                <a title="Newsletter für Agenturen" href="http://185.21.103.80/de/newsletter-fuer-agenturen/">Erfahren Sie mehr zum White Labeling</a>.[/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                <ul>
                                	<li>Eigenes Logo einfügen</li>
                                	<li>Farben selbst anpassen</li>
                                	<li>Benutzerrechte verwalten</li>
                                	<li>Eigenes Impressum</li>
                                </ul>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][/vc_column_inner][vc_column_inner width="1/3"][n2go_button title="Bei Interesse sprechen Sie uns bitte an" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fkontakt%2F|title:Kontakt|"][/vc_column_inner][vc_column_inner width="1/3"][/vc_column_inner][/vc_row_inner][/n2go_page_tab][/n2go_page_tabs][/vc_column][/vc_row][vc_row css=".vc_custom_1409315993667{margin-bottom: 0px !important;padding-bottom: 25px !important;background-color: #f5f5f5 !important;}"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]<span style="font-weight: 600;">Was kostet der Newsletterversand?</span>

                                Bei der webbasierten <span style="font-weight: 600;">Software von Newsletter2Go</span> bezahlen Sie nur für die E-Mails und SMS, die Sie auch tatsächlich benötigen.
                                Es gibt weder Grundgebühr, noch Einrichtungsgebühr, noch versteckte Kosten. Sie bezahlen nur, wenn Sie einen <a title="Professioneller Newsletter Versand" href="http://185.21.103.80/de/features/newsletter-versand/"><span style="font-weight: 600;">Newsletter versenden</span>.</a> Das schafft Transparenz und Vertrauen. Deswegen entscheiden sich immer mehr Unternehmen für unsere <a title="Professionelle Newsletter Software von Newsletter2Go" href="http://185.21.103.80/de/sea/newsletter-software-service/"><span style="font-weight: 600;">Newsletter Software</span></a>.
                                Probieren auch Sie Newsletter2Go aus, Sie werden es lieben unser <span style="font-weight: 600;">Newsletter-Tool</span> zu benutzen, um Ihre Newsletter zu versenden![/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]<span style="font-weight: 600;">Welche Bezahlmöglichkeiten gibt es bei Newsletter2Go</span>

                                Um Ihr Konto bei unserem <span style="font-weight: 600;">Newsletter-Service</span> mit Credits aufzuladen, stehen Ihnen Überweisung, Paypal und Sofortüberweisung zur Verfügung.
                                Bei den beiden letztgenannten Bezahlarten werden Ihre Credits dabei direkt nach Zahlungsabschluss gutgeschrieben.
                                Bei allen Bezahlvorgängen werden dabei höchste Sicherheitsmechanismen (256-Bit SSL-Verschlüsselung) verwendet, so dass Ihre Daten sicher sind und absolut vertraulich behandelt werden.[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Career', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-career';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408982020665{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row css=".vc_custom_1409040368785{padding-right: 20px !important;padding-left: 10px !important;}"][vc_column width="2/3"][vc_column_text css=".vc_custom_1409041828913{margin-bottom: 20px !important;}"]
                                <h1>Starte deine Karriere bei Newsletter2Go</h1>
                                [/vc_column_text][vc_column_text]
                                <h2>Arbeite in einem jungen, ambitionierten
                                und zielstrebigen Team. Wir freuen uns auf dich!</h2>
                                [/vc_column_text][vc_column_text]Newsletter2Go ist ein junges und wachsendes Internet-Unternehmen, das 2009 in Berlin gegründet wurde. Wir bieten eine onlinebasierte Newsletter-Software an, mit der unsere Kunden ohne großen Aufwand Newsletter gestalten und versenden können.

                                Mit ein paar Klicks können Unternehmen, E-Commerce-Shops, Vereine, Agenturen u.v.m. bei uns komplette E-Mail-Marketingkampagnen erstellen und so den Erfolg ihres Online Marketings erheblich steigern. Unsere Software soll es unseren Kunden ermöglichen, E-Mails zu verschicken, die Neugierde wecken und gelesen werden.[/vc_column_text][vc_column_text css=".vc_custom_1409041664748{margin-bottom: 12px !important;}"]<strong>Aktuelle Jobangebote:</strong>[/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                <ul>
                                	<li><a title="Stellenanzeige - PHP Entwickler" href="https://www.newsletter2go.de/pr/140702_PHP_Entwickler.pdf">PHP Entwickler</a></li>
                                	<li><a title="Stellenanzeige - Marketing Praktikant" href="https://www.newsletter2go.de/pr/140702_Online_Marketing_Praktikant.pdf">Marketing Praktikant</a></li>
                                	<li>Keine passende Stelle dabei? Wir freuen <a title="Kontakt" href="http://185.21.103.80/de/kontakt/">uns</a> auf deine Initiativ-Bewerbung!</li>
                                </ul>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409041874028{margin-bottom: 12px !important;}"]
                                <h3>Warum wir? 5 Gründe</h3>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409041979274{margin-bottom: 20px !important;}"]<span style="font-weight: 600; color: #666666;">1. Mehr als nur To-Do</span><span style="color: #666666;"> </span>

                                <span style="color: #666666;">Wir sind ein junges Team mit flachen Hierarchien. Hier kannst du deine Ideen einbringen und eigene Projekte übernehmen. Newsletter2Go möchte sich weiterentwickeln und noch mehr an Wachstum zulegen. Du bist hier nicht nur ein unbedeutendes Rädchen im Getriebe, sondern kannst dein Wissen, deine Fähigkeiten und deine Motivation dazu einsetzen, zum Erfolg des Unternehmens beizutragen. </span>[/vc_column_text][vc_column_text css=".vc_custom_1409041971523{margin-bottom: 20px !important;}"]<span style="font-weight: 600; color: #666666;">2. Challenge Accepted</span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Du suchst Aufgaben, die dich begeistern statt bloß beschäftigen? Bei uns kannst du Sie finden! Programmiere neue Features für unsere Newsletter-Software, konzipiere neue Marketingstrategien oder finde Lösungen für die Probleme unserer Kunden – Langeweile ausgeschlossen!</span>[/vc_column_text][vc_column_text css=".vc_custom_1409042029956{margin-bottom: 20px !important;}"]<span style="font-weight: 600; color: #666666;">3. Zusammen mehr erreichen</span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">In unserem Team bist du nicht nur dabei, sondern mittendrin. Eine offene Kommunikation und ein enger Zusammenhalt sorgen für eine angenehme Arbeitsatmosphäre. Darüber hinaus veranstalten für regelmäßig Teamevents, die für jede Menge Spaß und Abwechslung sorgen. </span>[/vc_column_text][vc_column_text css=".vc_custom_1409054518248{margin-bottom: 20px !important;}"]<span style="font-weight: 600; color: #666666;">4. www – Was wir wollen</span>
                                <ul class="default" style="color: #666666;">
                                	<li>Wir wollen effizient, schnell und zielgerichtet arbeiten.</li>
                                	<li>Wir wollen unsere Arbeit nachhaltig erledigen.</li>
                                	<li>Wir wollen Spaß an der Arbeit haben.</li>
                                	<li>Wir wollen, dass wir ehrlich und offen miteinander umgehen.</li>
                                </ul>
                                [/vc_column_text][vc_column_text]<span style="font-weight: 600; color: #666666;">5. Nur ein Katzensprung entfernt</span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Unser Büro liegt in der Nähe des Ernst-Reuter-Platzes. Du kannst uns leicht mit den öffentlichen Verkehrsmitteln erreichen. Für die Mittagspause sind zahlreiche Restaurants und Imbisse in der fußläufigen Umgebung vorhanden und für Getränke brauchst du dich übrigens nur ein paar Schritte von deinen Schreibtisch entfernen. </span>[/vc_column_text][/vc_column][vc_column width="1/3"][vc_single_image border_color="grey" img_link_target="_self" image="3522" img_size="full"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Contact', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-contact';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408952542614{padding-top: 20px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]
                                <h1 style="text-align: center;">Kontakt</h1>
                                [/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2" css=".vc_custom_1409059450746{background-color: #f5f5f5 !important;}"][vc_raw_html]JTNDZm9ybSUyMG1ldGhvZCUzRCUyMnBvc3QlMjIlMjBhY3Rpb24lM0QlMjIlMkZkZSUyRmtvbnRha3QlMjIlMjBpZCUzRCUyMmNvbnRhY3RGb3JtJTIyJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0NpbnB1dCUyMG5hbWUlM0QlMjJuYW1lJTIyJTIwdHlwZSUzRCUyMlRFWFQlMjIlMjBpZCUzRCUyMm5hbWUlMjIlMjBwbGFjZWhvbGRlciUzRCUyMiUyMElociUyME5hbWUlMkElMjIlMjBjbGFzcyUzRCUyMnNwYW41JTIyJTJGJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0NiciUyMCUyRiUzRSUwQSUwOSUwOSUzQ2lucHV0JTIwbmFtZSUzRCUyMmNvbXBhbnklMjIlMjB0eXBlJTNEJTIydGV4dCUyMiUyMGlkJTNEJTIyY29tcGFueSUyMiUyMHBsYWNlaG9sZGVyJTNEJTIyJTIwSWhyZSUyMEZpcm1hJTJBJTIyJTIwY2xhc3MlM0QlMjJzcGFuNSUyMiUyRiUzRSUwQSUwOSUwOSUzQ2JyJTIwJTJGJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0NpbnB1dCUyMG5hbWUlM0QlMjJlbWFpbCUyMiUyMHR5cGUlM0QlMjJ0ZXh0JTIyJTIwaWQlM0QlMjJlbWFpbCUyMiUyMHBsYWNlaG9sZGVyJTNEJTIyJTIwSWhyZSUyMEVtYWlsJTJBJTIyJTIwY2xhc3MlM0QlMjJzcGFuNSUyMiUyRiUzRSUwQSUwOSUwOSUzQ2JyJTIwJTJGJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0NzZWxlY3QlMjBuYW1lJTNEJTIyY2F0ZWdvcnklMjIlMjBpZCUzRCUyMmNhdGVnb3J5JTIyJTIwY2xhc3MlM0QlMjJzcGFuNSUyMiUzRSUwQSUwOSUwOSUwOSUzQ29wdGlvbiUyMHZhbHVlJTNEJTIybm9fY2F0ZWdvcnklMjIlM0UlMjAtJTIwYml0dGUlMjBLYXRlZ29yaWUlMjB3JUMzJUE0aGxlbiUyMC0lMjAlM0MlMkZvcHRpb24lM0UlMEElMDklMDklMDklM0NvcHRpb24lMjBkYXRhLWNhdF9pZHMlM0QlMjczMiUyNyUzRUFjY291bnQlMjBGcmVpc2NoYWx0dW5nJTJGWnVnYW5nc2RhdGVuJTNDJTJGb3B0aW9uJTNFJTNDb3B0aW9uJTIwZGF0YS1jYXRfaWRzJTNEJTI3OSUyNyUzRU5ld3NsZXR0ZXItVm9ybGFnZW4lMjBmJUMzJUJDciUyMFdlYnNob3AlM0MlMkZvcHRpb24lM0UlM0NvcHRpb24lMjBkYXRhLWNhdF9pZHMlM0QlMjcxNiUyQzI0JTJDNTMlMjclM0VJbnRlZ3JhdGlvbmVuJTJGQVBJJTNDJTJGb3B0aW9uJTNFJTNDb3B0aW9uJTIwZGF0YS1jYXRfaWRzJTNEJTI3MzAlMjclM0VQcmVpc2UlMkZUYXJpZmUlMkZaYWhsdW5nJTNDJTJGb3B0aW9uJTNFJTNDb3B0aW9uJTIwZGF0YS1jYXRfaWRzJTNEJTI3JTI3JTNFVGVjaG5pc2NoZXIlMjBTdXBwb3J0JTNDJTJGb3B0aW9uJTNFJTNDb3B0aW9uJTIwZGF0YS1jYXRfaWRzJTNEJTI3MzQlMkMyMiUyNyUzRVNNUyUyMFZlcnNhbmQlM0MlMkZvcHRpb24lM0UlM0NvcHRpb24lMjBkYXRhLWNhdF9pZHMlM0QlMjc0MyUyNyUzRVNQQU0tTWVsZHVuZyUzQyUyRm9wdGlvbiUzRSUzQ29wdGlvbiUyMGRhdGEtY2F0X2lkcyUzRCUyNyUyNyUzRVNvbnN0aWdlcyUzQyUyRm9wdGlvbiUzRSUwOSUwOSUzQyUyRnNlbGVjdCUzRSUwQSUwOSUwOSUzQ2JyJTIwJTJGJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0N0ZXh0YXJlYSUyMG5hbWUlM0QlMjJjb21tZW50JTIyJTIwaWQlM0QlMjJjb21tZW50JTIyJTIwcm93cyUzRCUyMjMlMjIlMjBzdHlsZSUzRCUyMmZvbnQtc2l6ZSUzQTE0cHglM0IlMjIlMjBwbGFjZWhvbGRlciUzRCUyMiUyMElocmUlMjBOYWNocmljaHQlMkElMjIlMjBjbGFzcyUzRCUyMnNwYW41JTIyJTNFJTNDJTJGdGV4dGFyZWElM0UlMEElMDklMDklM0NpbWclMjBhbHQlM0QlMjJjYXB0Y2hhJTIyJTIwc3JjJTNEJTIyJTJGZGUlMkZkZWZhdWx0JTJGaW5kZXglMkZjYXB0Y2hhJTIyJTIwaWQlM0QlMjJjYXB0Y2hhJTIyJTIwc3R5bGUlM0QlMjJ2ZXJ0aWNhbC1hbGlnbiUzQW1pZGRsZSUzQiUyMG1hcmdpbiUzQSUyMDEwcHglM0IlMjIlMkYlM0UlMDklMDklMEElMDklMDklM0NpbnB1dCUyMHR5cGUlM0QlMjJ0ZXh0JTIyJTIwbmFtZSUzRCUyMmNhcHRjaGElMjIlMjBpZCUzRCUyMmNhcHRjaGF0ZXh0JTIyJTIwcGxhY2Vob2xkZXIlM0QlMjJDYXB0Y2hhJTJBJTIyJTJGJTNFJTBBJTA5JTA5JTNDYnIlMjAlMkYlM0UlMEElMDklMDklM0NiciUyMCUyRiUzRSUwQSUwOSUwOSUzQyUyMS0tJTIwJTNDYnV0dG9uJTIwdHlwZSUzRCUyMnN1Ym1pdCUyMiUyMG5hbWUlM0QlMjJzdWJtaXQlMjIlMjBpZCUzRCUyMmFjdGlvbiUyMiUyMGNsYXNzJTNEJTIybHBidXR0b24lMjIlM0UlMEElMDklMDklMDlBYnNjaGlja2VuJTNDJTJGYnV0dG9uJTNFJTIwLS0lM0UlMEElMEElMDklMDklMDklMjAlM0NidXR0b24lMjB0eXBlJTNEJTIyc3VibWl0JTIyJTIwbmFtZSUzRCUyMnN1Ym1pdCUyMiUyMGlkJTNEJTIyYWN0aW9uJTIyJTIwY2xhc3MlM0QlMjJjdGElMjIlM0UlMEElMDklMDklMDlKZXR6dCUyMGFic2NoaWNrZW4lM0MlMkZidXR0b24lM0UlMEElMDklM0MlMkZmb3JtJTNF[/vc_raw_html][/vc_column][vc_column width="5/12" css=".vc_custom_1409060830568{margin-left: 10px !important;}"][vc_row_inner css=".vc_custom_1409061019767{margin-bottom: 10px !important;background-color: #f5f5f5 !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409060780592{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_single_image image="3365" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="3/4" css=".vc_custom_1409060772407{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_column_text]
                                <h2><span style="color: #1cbaff;">Per Telefon
                                </span></h2>
                                0800 778 776 77
                                <div class="normal" style="color: #333333;">+49 (030) 59 00 833 83</div>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409061031895{margin-bottom: 10px !important;background-color: #f5f5f5 !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409060780592{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_single_image image="3363" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="3/4" css=".vc_custom_1409060772407{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_column_text]
                                <h2><span style="color: #1cbaff;">Per Email</span></h2>
                                <div class="normal" style="color: #333333;"><a href="mailto:support@newsletter2go.de">support@newsletter2go.de</a></div>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409059492017{background-color: #f5f5f5 !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409060780592{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_single_image image="3364" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="3/4" css=".vc_custom_1409060772407{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_column_text]
                                <h2><span style="color: #1cbaff;">Häufig gestellte Fragen</span></h2>
                                <div class="normal" style="color: #333333;">Häufig gestellte Fragen: <a href="https://www.newsletter2go.de/de/hilfe"><span style="text-decoration: underline;">Hilfe</span></a></div>
                                [/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Partner', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-partner';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408982020665{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row css=".vc_custom_1409040368785{padding-right: 20px !important;padding-left: 10px !important;}"][vc_column width="2/3"][vc_column_text css=".vc_custom_1409043157706{margin-bottom: 25px !important;}"]
                                <h1>Das Partnernetzwerk von Newsletter2Go</h1>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409043169346{margin-bottom: 25px !important;}"]Schließen Sie sich jetzt dem Partnernetzwerk von Newsletter2Go an und profitieren
                                Sie von einem E-Mail Marketing-Partner, der Ihnen und Ihren Kunden mit langjährigem Know-How zur Seite steht.

                                Hier erfahren Sie mehr zu unserem Partnerprogramm. Wir freuen uns über Ihre Kontaktaufnahme.[/vc_column_text][n2go_button title="Mehr zum Partnerprogramm" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fpartner-werden%2F|title:Partner%20werden|"][vc_column_text css=".vc_custom_1409043021331{margin-bottom: 20px !important;}"]
                                <h2>Agenturen:</h2>
                                [/vc_column_text][vc_row_inner css=".vc_custom_1409043043327{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="1702" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="color: #666666;">Die </span><span style="font-weight: 600; color: #666666;">Sell It Smart GmbH</span><span style="color: #666666;"> gehört zu den führenden E-Commerce Agenturen in Deutschland und vereint E-Commerce Beratung und Webdesign unter einem Dach. Als Spezialist für Shopsystem, Middleware und Marktplätze entwickelt Sell It Smart individuelle B2C und B2B E-Commerce Lösungen. Projektschwerpunkt ist dabei die Realisierung von Onlineshops.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043057862{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="1703" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="font-weight: 600; color: #666666;">Agentur Webfox</span><span style="color: #666666;"> ist eine innovative Full Service Internet Agentur mit Sitz in Berlin Charlottenburg. Zu den angebotenen Dienstleistungen gehören unter anderem Konzeption, Webdesign, Entwicklung und Online Marketing. Agentur Webfox ist ausgewiesener Spezialist für Entwicklungen mit dem CMS TYPO3 und für Responsive Webdesign.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][vc_single_image image="1704" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="font-weight: 600; color: #666666;">Jarlssen</span><span style="color: #666666;"> realisiert als E-Commerce-Agentur in München seit über 7 Jahren individuelle Lösungen für Online Shops, ERP und IT-Qualitätssicherung.</span><br style="color: #666666;" /><span style="color: #666666;">Als Magento-Gold Partner und PHP-Dienstleister bieten wir Beratung, Programmierung und Betrieb, sowie Optimierung und Marketing für das B2C- und B2B-Business.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_column_text css=".vc_custom_1409043012076{margin-bottom: 20px !important;}"]
                                <h2>Zertifikate</h2>
                                [/vc_column_text][vc_row_inner css=".vc_custom_1409043083301{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="1706" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="color: #666666;">Der Service von Newsletter2Go ist von der </span><a title="Partner von Newsletter2Go - CSA" href="https://www.newsletter2go.de/de/features/newsletter-whitelisting" target="_blank"><span style="font-weight: 600;">Certified Senders Alliance (CSA)</span></a><span style="color: #666666;"> zertifiziert. Davon profitieren in erster Linie Sie, da dadurch die versendeten E-Mails nicht auf Spamverdacht geprüft werden sondern direkt zugestellt werden.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043090308{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="2899" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="color: #666666;">Newsletter2Go erfreut sich dank der professionellen Software bei </span><span style="font-weight: 600; color: #666666;">Ekomi</span><span style="color: #666666;"> einer Kundenzufriedenheit von mehr als 99%. Lassen auch Sie sich von unserer verkaufsstarken E-Mail Marketing Software überzeugen und registrieren Sie sich jetzt kostenlos.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043097837{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="1709" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="color: #666666;">Newsletter2Go ist Partner von </span><a title="Partner von Newsletter2Go - Return Path" href="https://www.newsletter2go.de/de/features/return-path-zertifizierung" target="_blank"><span style="font-weight: 600;">Return Path</span></a><span style="color: #666666;">. Damit können wir den Zugriff auf mehr als 2,4 Milliarden gewhitelistete Postfächer anbieten. Vor allem für die Zustellraten von internationalem Versand ist dies ein großer Vorteil.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043111277{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image image="1710" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<span style="color: #666666;">Als Mitglied im </span><span style="font-weight: 600;"><a style="color: #306080;" title="Partner von Newsletter2Go - DDV" href="https://www.newsletter2go.de/de/features/ddv-ehrenkodex-email-marketing" target="_blank">Deutschen Dialogmarketing Verband (DDV)</a> </span><span style="color: #666666;">verpflichtet sich Newsletter2Go zu den strengen Richtlinien des DDV Ehrenkodex. Diese dienen der Vermeidung von Spam und einem seriösen Versand.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][vc_single_image image="1711" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<a title="Partner von Newsletter2Go - Geo Trust" href="https://www.newsletter2go.de/de/features/ssl-verschluesselung-abhoersicher" target="_blank"><span style="font-weight: 600;">Geotrust</span></a><span style="color: #666666;"> ist ein weltweit agierendes Unternehmen für SSL Zertifizierung und Verschlüsselung. Dies dient der Sicherheit und dem Schutz Ihrer Daten. Die Software von Newsletter2Go ist natürlich SSL zertifiziert.</span>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][vc_single_image border_color="grey" img_link_target="_self" image="1700" img_size="full"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );

		$template = array();
		$template[ 'name' ] = __( 'Partner Landingpage', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-partner-landingpage';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1409646960516{margin-top: 10px !important;margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column][/vc_column][/vc_row][vc_row css=".vc_custom_1409133723335{margin-bottom: 15px !important;}"][vc_column width="1/2"][vc_column_text css=".vc_custom_1409643744844{margin-bottom: 20px !important;}"]
                                    <h1>Die 5 besten Tipps für perfekte eCommerce Newsletter</h1>
                                    [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]Newsletter Software Anbieter Newsletter2Go wird von hunderten Webshops für erfolgreiches Email Marketing genutzt.

                                    Diese Expertise können Sie nun nutzen, um Ihr Email Marketing noch effektiver zu machen. Newsletter2Go hat Ihnen die 5 besten Tipps für eCommerce Newsletter zusammengestellt.[/vc_column_text][/vc_column][vc_column width="1/2" css=".vc_custom_1409646852571{padding-top: 10px !important;}"][vc_single_image border_color="grey" img_link_target="_self" img_size="full" image="3229" css=".vc_custom_1409646088371{margin-bottom: 20px !important;}"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text css=".vc_custom_1409644069456{margin-bottom: 25px !important;}"]
                                    <h2># 5 CSA Zertifizierung</h2>
                                    [/vc_column_text][vc_column_text]Arbeiten Sie mit Newsletter Software Anbietern die über eine CSA Zertifizierung verfügen.

                                    Anbieter, die Partner der „Certified Senders Alliance“ sind, können Newsletter versenden, die bei großen Email Providern wie Yahoo, Freenet, GMX oder WEB.de direkt in das Postfach des Empfängers zugestellt werden ohne auf Spamverdacht überprüft zu werden.

                                    Auf diese Weise erhalten Ihre Mailings eine höchstmögliche Zustellrate und legen damit den Grundstein für ein erfolgreiches E-Mail-Marketing.[/vc_column_text][/vc_column][vc_column width="1/2"][vc_single_image image="2842" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_single_image image="2843" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/2"][vc_column_text css=".vc_custom_1409644062082{margin-bottom: 25px !important;}"]
                                    <h2># 4 Schreiben Sie interessante Betreffzeilen</h2>
                                    [/vc_column_text][vc_column_text]Die Betreffzeile ist der Schlüssel zur Öffnung eines Newsletters. Sie sollte daher möglichst aussagekräftig, kreativ und prägnant sein. Wecken Sie die Neugier des Empfängers und schaffen Sie konkrete Anreize zum Kaufen.

                                    Beispiel: „Nur noch diese Woche: Damenschuhe zum halben Preis“. Schreiben Sie jedoch nicht zu werblich indem Sie Worte wie „Gewinnspiel“ oder „Jetzt gratis“ verwenden, da diese Art der Betreffzeile zu einer Spam Klassifizierung führen kann.

                                    Nutzen Sie Unicode Symbole wie Sterne und Herzen, um im Postfach des Empfängers besonders hervorzustechen.

                                    Betreffzeilen mit dem Symbol Stern haben so zum Beispiel eine 7% höhere Öffnungsrate und 17% mehr Klicks gebracht. Nutzen Sie darüber hinaus A/B Split-Tests, um herauszufinden, welche Betreffzeilen die höchste Öffnungsrate erzielen.[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text css=".vc_custom_1409644097169{margin-bottom: 25px !important;}"]
                                    <h2># 3 Personalisieren Sie Ihren Newsletter</h2>
                                    [/vc_column_text][vc_column_text]Niemand möchte mit „Sehr geehrte Damen und Herren“ angesprochen werden.

                                    Das wirkt unpersönlich und erinnert an Massenmailings. Daher sollten Sie Daten wie Vor- und Nachnamen bei der Newsletter Registrierung abfragen, um jeden Empfänger individuell ansprechen zu können. Auch Bilder können im Newsletter personalisiert werden. In einer Beispielpersonalisierung war das Bild einer Sektflasche mit dem Vornamen des Empfängers zu sehen.

                                    Die Click-Through-Rate dieses Newsletters war 400% höher als vorangegangene Mailings. Besonders empfehlenswert ist darüber hinaus die Personalisierung nach Produkten. Schauen Sie welcher Kunde sich für welche Produkte interessiert hat und bewerben Sie diese dann im Newsletter. Sie werden sehen: Ihr ROI wird deutlich steigen.[/vc_column_text][/vc_column][vc_column width="1/2"][vc_single_image image="2844" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_single_image image="2845" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/2"][vc_column_text css=".vc_custom_1409644155473{margin-bottom: 25px !important;}"]
                                    <h2># 2 Versenden Sie Lifecycle Emails, um den Customer Lifetime Value zu erhöhen</h2>
                                    [/vc_column_text][vc_column_text]Lifecycle Emails sind Newsletter, die automatisch zu bestimmten Ereignissen in einem „Kundenleben“ versendet werden. Beispiele dafür sind Geburtstags Mailings oder Rabattangebote, wenn ein Kunde längere Zeit inaktiv war.

                                    Erstellen Sie diese Newsletter und legen Sie Ereignisse fest, wann sie automatisch verschickt werden sollen. Sie können Newsletter2Go dazu nutzen, um diese Mailings mit Ihrem Shop abzustimmen.

                                    Hat sich ein Kunde registriert und sein Geburtsdatum angegeben, kann an diesem Tag automatisch ein Geburtstagsnewsletter mit einem Sonderangebot verschickt werden. Erfahrungsgemäß liegt die Öffnungsrate von solchen Lifecycle Emails 20% höher im Vergleich zum normalen Newsletter.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1409644304316{background-color: #f5f5f5 !important;}"][vc_column width="1/2" css=".vc_custom_1409644331365{padding-top: 20px !important;}"][vc_single_image image="3228" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/2" css=".vc_custom_1409644354796{padding-top: 20px !important;padding-bottom: 20px !important;}"][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                    <p style="margin: 0px 0px 9px; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666666; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; text-align: left; text-indent: 0px; background-color: #f7f7f7;">Mit dem Gutscheincode ONPAGE2GO erhalten Sie bis zum 31.10.2014 20% Rabatt auf eine individuelle Newsletter-Vorlage mit:</p>

                                    <ul style="padding: 0px; margin: 0px; color: #666666; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20px; text-align: left; text-indent: 0px; background-color: #f7f7f7;">
                                    	<li>Ihrem Design</li>
                                    	<li>Webshop Integration</li>
                                    	<li>1-Klick-Produktübernahme</li>
                                    </ul>
                                    [/vc_column_text][n2go_button title="Jetzt Gutschein einlösen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_column_text css=".vc_custom_1409644401081{margin-bottom: 25px !important;}"]
                                    <h2># 1 Nutzen Sie eine Webshop Integration</h2>
                                    [/vc_column_text][vc_column_text]Im eCommerce kommen pro Monat oft einige tausend Artikel neu in das Sortiment des Händlers. Damit Kunden über neue Angebote informiert werden können, müssen die Newsletter schnell und effizient mit den aktuellsten Produktdaten ausgestattet werden.

                                    Newsletter2Go bietet daher Webshop Integrationen für alle gängigen Webshop Softwares (bspw. Magento, xt commerce und plentymarkets) an. Damit können Sie mit einem Klick alle relevanten Produktinformationen aus Ihrem Shop mit Ihrem Newsletter synchronisieren. Wichtige Informationen wie Produktbeschreibung, Preise, Bilder und Links werden über die Schnittstelle automatisch übernommen.

                                    Sie können darüber hinaus auch Ihre Empfängerdaten aus Ihrem Webshop synchronisieren und somit optimal personalisierte Newsletter versenden. Legen Sie gleich los und richten Sie Ihr Email Marketing mit Newsletter2Go ein.[/vc_column_text][n2go_button title="Jetzt Gutschein einlösen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column width="1/2"][vc_single_image image="2848" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][/vc_row][vc_row][vc_column][vc_column_text]

                                    [/vc_column_text][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Partner (old)', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-partner-old';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1409646675012{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][/vc_column][/vc_row][vc_row css=".vc_custom_1409133723335{margin-bottom: 15px !important;}"][vc_column width="2/3"][vc_column_text css=".vc_custom_1409917769880{margin-bottom: 20px !important;}"]
                                            <h2>Individuelle Newsletter-Vorlage
                                            mit Webshop Integration</h2>
                                            [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]Newsletter2Go bietet Ihnen eine leistungsstarke Newsletter-Software zum professionellen Erstellen und Versenden Ihrer Newsletter. Die E-Mails werden sicher über deutsche Server versendet und dank der CSA Zertifzierung von Newsletter2Go werden diese nicht vom Spam Filter aussortiert. Den Erfolg Ihrer Newsletter-Mailings machen wir mit umfangreichen Analyse-Tools messbar.

                                            <span style="font-weight: 600; color: #666666;">Speziell für Ihren Webshop: </span><br style="color: #666666;" /><span style="color: #666666;">Mit der praktischen </span><a href="https://www.newsletter2go.de/de/ecommerce-newsletter-vorlage#video">1-Klick-Produktübernahme</a><span style="color: #666666;"> können Sie mit nur einem Klick Artikel aus Ihrem E-Commerce Shop direkt in den Newsletter integrieren. Und das mit Artikelbild, -preis, -link, Rabatt, und Produktbeschreibung.</span>[/vc_column_text][vc_row_inner el_class="n2go-textAlign-center" css=".vc_custom_1409917525757{border-bottom-width: 0px !important;padding-top: 57px !important;}"][vc_column_inner width="1/1"][n2go_button title="Kostenlos registrieren &amp; Gutschein einlösen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3" css=".vc_custom_1409646687667{padding-top: 25px !important;}"][vc_single_image border_color="grey" img_link_target="_self" img_size="full" image="2832" css=".vc_custom_1409917900676{margin-bottom: 20px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1409917725756{padding-bottom: 25px !important;}"][vc_column width="1/1"][vc_single_image image="4163" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409917544331{margin-bottom: 25px !important;}"][vc_column_text css=".vc_custom_1409917563585{margin-bottom: 14px !important;}"]
                                            <h2>Video zur 1-Klick-Produktübernahme</h2>
                                            [/vc_column_text][vc_raw_html]JTNDaWZyYW1lJTIwd2lkdGglM0QlMjI5ODAlMjIlMjBoZWlnaHQlM0QlMjI1NTIlMjIlMjBzcmMlM0QlMjJodHRwcyUzQSUyRiUyRnd3dy55b3V0dWJlLmNvbSUyRmVtYmVkJTJGLXVGYzNyamgyWXMlM0ZzaG93aW5mbyUzRDAlMjZhbXAlM0JyZWwlM0QwJTI2YW1wJTNCbW9kZXN0YnJhbmRpbmclM0QxJTIyJTIwZnJhbWVib3JkZXIlM0QlMjIwJTIyJTIwYWxsb3dmdWxsc2NyZWVuJTNEJTIyJTIyJTIwaWQlM0QlMjJ2aWRlbyUyMiUzRSUzQyUyRmlmcmFtZSUzRQ==[/vc_raw_html][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'SEA (old)', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-sea-old';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1409918622989{padding-top: 25px !important;}"][vc_column width="1/1"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1409586564532{margin-bottom: 20px !important;}"]
                                        <h1 style="margin: 0px 0px 20px; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-weight: 300; color: #1cbaff; font-size: 38px; line-height: 46px; font-style: normal; font-variant: normal; letter-spacing: normal; text-align: left; text-indent: 0px; background-color: #ffffff;">Newsletter-Versand</h1>
                                        [/vc_column_text][vc_column_text]
                                        <h2 style="margin: 0px; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-weight: 300; color: #1cbaff; font-size: 24px; line-height: 30px; font-style: normal; font-variant: normal; letter-spacing: normal; text-align: left; text-indent: 0px; background-color: #ffffff;">Mit Newsletter2Go zum schnellen und unkomplizierten Newsletter-Versand</h2>
                                        [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<span style="color: #666666;"><span style="color: #404040; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 300; letter-spacing: normal; line-height: 25px; text-align: left; text-indent: 0px; float: none; background-color: #ffffff;"><span style="color: #404040; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 300; letter-spacing: normal; line-height: 25px; text-align: left; text-indent: 0px; float: none; background-color: #ffffff;">Der Newsletter-Versand gehört heutzutage zu den wichtigsten Maßnahmen rund um die Kundenbindung. Wer beispielsweise einen Online-Shop betreibt, kann durch das Newsletter Verschicken Ihre Kunden über Neuigkeiten informieren und somit stets mit ihnen im Dialog zu bleiben. Ein großer Vorteil von Newsletter2Go stellt die Möglichkeit der E-Mail Personalisierung dar, in der Sie Ihre Kunden schon in der Betreffzeile persönlich ansprechen können. Dadurch können Sie die Anzahl der Öffnungen Ihres Mailings erheblich erhöhen, was sich auf Ihre Verkaufszahlen positiv auswirken könnte.</span></span></span>[/vc_column_text][vc_column_text css=".vc_custom_1409586631903{margin-bottom: 25px !important;}" el_class="n2go-textContainingCheckmarkList"]<strong style="font-weight: 600; color: #404040; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 18px; text-align: left; text-indent: 0px; background-color: #ffffff;">Vorteile von Newsletter2Go:</strong>
                                        <ul style="padding: 0px; margin: 0px 0px 9px 25px; color: #404040; font-family: \'Open Sans\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 300; letter-spacing: normal; line-height: 18px; text-align: left; text-indent: 0px; background-color: #ffffff;">
                                        	<li style="line-height: 25px;">Keine Setupkosten oder Grundgebühr. Sofort einsatzbereit.</li>
                                        	<li style="line-height: 25px;">Automatischer Empfängerimport und Abgleich (Magento, Plentymarkets uvm.)</li>
                                        	<li style="line-height: 25px;">Teamwork: Arbeiten Sie im Team an Ihren Mailings</li>
                                        	<li style="line-height: 25px;">1-Klick-Produktübernahme aus Ihrem Shopsystem</li>
                                        	<li style="line-height: 25px;">Whitelabeling &amp; Accountverwaltung</li>
                                        </ul>
                                        [/vc_column_text][/vc_column][vc_column width="1/3" css=".vc_custom_1408970923693{padding-right: 20px !important;padding-left: 10px !important;}"][n2go_registration_form][/vc_column][/vc_row][vc_row css=".vc_custom_1409918234231{padding-top: 25px !important;padding-bottom: 25px !important;}"][vc_column width="1/3"][vc_column_text css=".vc_custom_1409580914255{margin-bottom: 20px !important;}"]
                                        <h3>Umfangreiche Funktionen</h3>
                                        [/vc_column_text][vc_row_inner css=".vc_custom_1409580216901{margin-bottom: 0px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409580198029{margin-right: 0px !important;}"][vc_single_image image="2889" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409580253215{padding-top: 12px !important;}"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409580209271{margin-left: -15px !important;}"][vc_column_text]Cloud-Anwendung zum einfachen Erstellen der Mailings[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409580216901{margin-bottom: 0px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409580198029{margin-right: 0px !important;}"][vc_single_image image="2890" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409580309699{padding-top: 12px !important;}"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409580209271{margin-left: -15px !important;}"][vc_column_text]Umfangreiche Analysen und Performance-Reports[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409580216901{margin-bottom: 0px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409580198029{margin-right: 0px !important;}"][vc_single_image image="2893" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409580332288{padding-top: 12px !important;}"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409580209271{margin-left: -15px !important;}"][vc_column_text]Einfacher Empfämger Import und Web-Shop Integration[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409580216901{margin-bottom: 0px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409580198029{margin-right: 0px !important;}"][vc_single_image image="2891" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409580356850{padding-top: 12px !important;}"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409580209271{margin-left: -15px !important;}"][vc_column_text]A/B Testing, Personalisierung und vieles mehr[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/6" css=".vc_custom_1409580198029{margin-right: 0px !important;}"][vc_single_image image="2892" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409580381424{padding-top: 12px !important;}"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409580209271{margin-left: -15px !important;}"][vc_column_text]Whitelabeling und Accountverwaltung[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][vc_column_text css=".vc_custom_1409581131334{margin-bottom: 12px !important;}"]
                                        <h3>Unschlagbare Preise</h3>
                                        [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                        <ul>
                                        	<li><span style="font-weight: 600;">1.000 Emails gratis</span></li>
                                        </ul>
                                        <p style="padding-left: 35px;">und das jeden Monat Vorteile von Newsletter2Go</p>

                                        <ul>
                                        	<li><span style="font-weight: 600;">Prepaid Tarife</span></li>
                                        </ul>
                                        <p style="padding-left: 35px;">für unregelmäßiges Versenden ab 1,48 € pro Tausend Emails Vorteile von Newsletter2Go</p>

                                        <ul>
                                        	<li><span style="font-weight: 600;">Abonnement Tarif</span></li>
                                        </ul>
                                        <p style="padding-left: 35px;">für regelmäßiges Versenden ab 0,40 € pro Tausend Emails ohne Mindestlaufzeit</p>
                                        [/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text css=".vc_custom_1409581131334{margin-bottom: 12px !important;}"]
                                        <h3>Unschlagbare Preise</h3>
                                        [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                        <ul>
                                        	<li>Server in Deutschland</li>
                                        	<li>Wir arbeiten nach dem deutschen Datenschutzgesetz</li>
                                        	<li>Whitelist Server: Direkte Zustellung im Postfach ohne Spamkontrolle</li>
                                        	<li>CSA Zertifizierung</li>
                                        	<li>Mitglied im DDV</li>
                                        </ul>
                                        [/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1409582291672{margin-bottom: 0px !important;background-color: #f5f5f5 !important;}"][vc_column width="1/1" css=".vc_custom_1409580789659{margin-bottom: 15px !important;}"][vc_column_text css=".vc_custom_1409586658663{margin-bottom: 0px !important;padding-top: 15px !important;}"]
                                        <h2 style="text-align: center;">Wir bieten auch Ihnen die perfekte Lösung</h2>
                                        [/vc_column_text][vc_row_inner css=".vc_custom_1409581166985{margin-bottom: 0px !important;}"][vc_column_inner width="1/12"][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]
                                        <p style="text-align: center;"><a href="https://www.newsletter2go.de/de/newsletter-fuer-selbstaendige"><img class="aligncenter wp-image-2871 size-full" src="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/selbst.png" alt="Für Selbstständige" width="150" height="100" /></a>
                                        <a title="Newsletter Sofware für Selbständige" href="https://www.newsletter2go.de/de/newsletter-fuer-selbstaendige">Setzen Sie unkompliziert Ihr Newsletter Marketing auf und nutzen Sie unsere Vorlagen.
                                        mehr</a></p>
                                        [/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]
                                        <p style="text-align: center;"><a href="https://www.newsletter2go.de/de/ecommerce-newsletter-software"><img class="aligncenter wp-image-2869 size-full" src="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/ecommerce.png" alt="Newsletter Sofware für eCommerce und Händler" width="150" height="100" /></a>
                                        <a title="Newsletter Sofware für ecommerce und Händler" href="https://www.newsletter2go.de/de/ecommerce-newsletter-software">Einfach Integration in Ihren Web-Shop, umfangreiche Reports, A/B Tests, Teamwork.
                                        mehr</a></p>
                                        [/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]
                                        <p style="text-align: center;"><a href="https://www.newsletter2go.de/de/newsletter-fuer-agenturen"><img class="aligncenter wp-image-2868 size-full" src="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/agenturen.png" alt="Newsletter Sofware für Agenturen" width="150" height="100" /></a>
                                        <a title="Newsletter Sofware für Agenturen" href="https://www.newsletter2go.de/de/newsletter-fuer-agenturen">Reporting, Whitelabeling, Nutzerverwaltung, Partnerprogramm.
                                        mehr</a></p>
                                        [/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]
                                        <p style="text-align: center;"><a href="https://www.newsletter2go.de/de/newsletter-fuer-vereine"><img class="aligncenter wp-image-2870 size-full" src="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/ngos.png" alt="Newsletter Sofware für Vereine und NGOs" width="150" height="100" /></a>
                                        <a title="Newsletter Sofware für Vereine und NGOs" href="https://www.newsletter2go.de/de/newsletter-fuer-vereine">Empfängerverwaltung, Lifecycle Emails, Intuitive Bedienung.
                                        mehr</a></p>
                                        [/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][vc_column_text]
                                        <p style="text-align: center;"><a href="https://www.newsletter2go.de/de/newsletter-fuer-unternehmen"><img class="aligncenter wp-image-2872 size-full" src="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/unternehmen.png" alt="Newsletter Sofware für Unternehmen" width="150" height="100" /></a>
                                        <a title="Newsletter Sofware für Unternehmen" href="https://www.newsletter2go.de/de/newsletter-fuer-unternehmen">Nutzen Sie die Software von Newsletter2Go für Ihr gesamtes Email Marketing.
                                        mehr</a></p>
                                        [/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Press', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-press';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408982020665{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row css=".vc_custom_1409040368785{padding-right: 20px !important;padding-left: 10px !important;}"][vc_column width="2/3"][vc_column_text]
                                <h1>Presse</h1>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409040256582{margin-bottom: 12px !important;}"]Erfahren Sie mehr über das Unternehmen Newsletter2Go und nutzen Sie das bereitgestellte Presse-Material.[/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<strong>Hier finden Sie:</strong>
                                <ul>
                                	<li>Infos zur Geschäftsführung</li>
                                	<li>Company Facts</li>
                                	<li>Kurzer Onpager zur Newsletter2Go Software</li>
                                	<li>Pressematerial</li>
                                	<li>Pressemitteilungen</li>
                                </ul>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409040428828{margin-bottom: 12px !important;}"]
                                <h2>Die Gründer</h2>
                                [/vc_column_text][vc_column_text]Erfahren Sie mehr über die Gründer der Newsletter2Go GmbH Steffen Schebesta und Christoph Beuck.[/vc_column_text][vc_row_inner][vc_column_inner width="1/3"][vc_single_image image="3260" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<strong>Gründer Team</strong>
                                <a href="https://www.newsletter2go.de/pr/Gruenderteam-72dpi.jpg">72dpi Web</a>
                                <a href="https://www.newsletter2go.de/pr/Gruenderteam-300dpi.jpg">300dpi Print</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_column_text css=".vc_custom_1409040453186{margin-bottom: 12px !important;}"]
                                <h2>Company Facts</h2>
                                [/vc_column_text][vc_column_text]Die Newsletter2Go GmbH wurde 2009 in Berlin gegründet. Sie beschäftigt derzeit 10 Mitarbeiter und wird in der DACH Region sowie auf dem niederländischen Markt vertrieben. Als inhabergeführtes Unternehmen setzt Newsletter2Go hohen Wert auf technologische Innovation und hohe Kundenzufriedenheit. Mit mehr als 31.000 Kunden ist Newsletter2Go einer der führenden Anbieter für professionelles E-Mail Marketing made in Germany.[/vc_column_text][vc_column_text css=".vc_custom_1409040564975{margin-bottom: 12px !important;}"]
                                <h2>Pressematerial</h2>
                                [/vc_column_text][vc_column_text css=".vc_custom_1409040648467{margin-bottom: 20px !important;}"]Hier finden Sie alle relevanten Presse-Materialien. Zu diesen zählt neben dem kurzen Onepager, welcher Informationen der Newsletter2Go Software zusammenfasst auch das Logo (mit und ohne Schriftzug). Wenn Sie weiteres Material benötigen sprechen Sie uns gerne jederzeit an.[/vc_column_text][vc_row_inner css=".vc_custom_1409040186752{margin-bottom: 20px !important;}"][vc_column_inner width="1/12"][vc_single_image image="3520" border_color="grey" img_link_target="_self" img_size="31x32" link="https://www.newsletter2go.de/pr/PresseinformationAugust2011.pdf" css=".vc_custom_1409056751387{margin-top: 14px !important;}"][/vc_column_inner][vc_column_inner width="5/6"][vc_column_text]<a title="Onepager Newsletter2Go" href="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/Onepager_Newsletter2Go.pdf">Newsletter2Go Onepager</a> (01.09.2014, pdf, 336 KB)[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409040142050{margin-bottom: 15px !important;}"][vc_column_inner width="1/3"][vc_single_image border_color="grey" img_link_target="_self" image="3514" img_size="full" css=".vc_custom_1409055954657{margin-top: 14px !important;}"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<strong>Logo
                                </strong><a href="https://www.newsletter2go.de/pr/Logo-72dpi.jpg">72dpi Web
                                </a><a href="https://www.newsletter2go.de/pr/Logo-300dpi.jpg">300dpi Print</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][vc_single_image image="2177" border_color="grey" img_link_target="_self" img_size="full" css=".vc_custom_1409055962837{margin-top: 14px !important;}"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<strong>Logo mit Schriftzug</strong>
                                <a href="https://www.newsletter2go.de/pr/Newsletter2Go-72dpi.jpg">72dpi Web</a>
                                <a href="https://www.newsletter2go.de/pr/Newsletter2Go-300dpi.jpg">300dpi Print</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_column_text css=".vc_custom_1409040483268{margin-bottom: 12px !important;}"]
                                <h2>Newsletter2Go in den Medien</h2>
                                [/vc_column_text][vc_row_inner css=".vc_custom_1409040186752{margin-bottom: 20px !important;}"][vc_column_inner width="1/12"][vc_single_image image="3520" border_color="grey" img_link_target="_self" img_size="full" link="http://185.21.103.80/de/wp-content/uploads/sites/2/2014/08/Onepager_Newsletter2Go.pdf"][/vc_column_inner][vc_column_inner width="11/12"][vc_column_text]<a href="https://www.newsletter2go.de/pr/PresseinformationAugust2011.pdf">Newsletter2Go Version 3.0: Neue Features, neues Preissystem</a> (25.08.2011, pdf, 779 KB)[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409040194337{margin-bottom: 20px !important;}"][vc_column_inner width="1/12"][vc_single_image image="3520" border_color="grey" img_link_target="_self" img_size="full" link="http://www.pressking.de/release/download/PR003627-Personology-und-Newsletter2Go-starten-gemeinsam-durch.pdf"][/vc_column_inner][vc_column_inner width="11/12"][vc_column_text]<a href="http://www.pressking.de/release/download/PR003627-Personology-und-Newsletter2Go-starten-gemeinsam-durch.pdf" rel="nofollow">Personology und Newsletter2Go starten gemeinsam durch</a> (30.05.2011, pdf, 508 KB)[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/12"][vc_single_image image="3520" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/pr/PresseinformationMrz2011.pdf"][/vc_column_inner][vc_column_inner width="11/12"][vc_column_text]<a style="color: #306080;" href="https://www.newsletter2go.de/pr/PresseinformationMrz2011.pdf" rel="nofollow">Newsletter2Go schließt erste Finanzierungsrunde erfolgreich ab</a> (10.03.2011, pdf, 102 KB)[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_column_text css=".vc_custom_1409040762977{margin-bottom: 12px !important;}"]
                                <h2>Ansprechpartner Presse</h2>
                                [/vc_column_text][vc_row_inner css=".vc_custom_1409040186752{margin-bottom: 20px !important;}"][vc_column_inner width="1/3"][vc_single_image border_color="grey" img_link_target="_self" image="3533" img_size="full"][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]Bitte wenden Sie sich an unseren Presse-Sprecher Maximilian Modl, wenn Sie weitere Informationen wünschen. Kontaktieren Sie uns <a title="Kontakt" href="http://185.21.103.80/de/kontakt/">hier</a>.[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][vc_single_image border_color="grey" img_link_target="_self" image="3522" img_size="full"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'References', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-references';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408979527210{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1408980930988{margin-bottom: 20px !important;}"]
                                <h1>Über 31.000 Kunden setzen auf
                                die Software von Newsletter2Go</h1>
                                [/vc_column_text][vc_column_text]
                                <h2>Über 99% Kundenzufriedenheit und namenhafte Referenzen sprechen für sich</h2>
                                [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList" css=".vc_custom_1408981408923{margin-bottom: 25px !important;}"]<b>Vorteile der Newsletter2Go E-Mail-Marketingsoftware:
                                </b>
                                <ul>
                                	<li>Höhe Kundenzufriedenheit</li>
                                	<li>Professionelle E-Mail-Marketingsoftware</li>
                                	<li>Höchste Zustellraten bei versendeten E-Mails</li>
                                	<li>Leistungsstarke Versand-Architektur</li>
                                	<li>Verkaufssteigernde Funktionen für Webshops</li>
                                </ul>
                                [/vc_column_text][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][vc_column_text]<span style="color: #666666;">Lassen auch Sie sich von der verkaufsstarken E-Mail Marketing Software von Newsletter2Go überzeugen.</span>[/vc_column_text][vc_column_text]
                                <h3>Testimonials</h3>
                                [/vc_column_text][vc_row_inner css=".vc_custom_1408980033086{margin-bottom: 10px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409055232564{padding-right: 0px !important;}"][vc_single_image image="1687" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409057053361{margin-top: -10px !important;padding-left: 30px !important;}"][vc_column_text]<span style="color: #666666;">"Mit Newsletter2Go haben wir endlich ein zeitsparendes, benutzerfreundliches und damit effektives Marketingwerkzeug gefunden." </span><em style="color: #666666;">D. Weinmann, <a title="Referenzen von Newsletter2Go" href="http://www.cortile-bavaria.de/" target="_blank">www.cortile-bavaria.de</a></em>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1408980039917{margin-bottom: 10px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409057026640{padding-right: 0px !important;}"][vc_single_image image="1688" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409057061456{margin-top: -10px !important;padding-left: 30px !important;}"][vc_column_text]<span style="color: #666666;">„Der Newsletter „Newsletter2go“ beflügelt unser Marketing!“ - "Zusammenkommen ist ein Beginn, Zusammenbleiben ein Fortschritt, Zusammenarbeiten ein Erfolg" - Henry Ford</span><br style="color: #666666;" /><em style="color: #666666;">R. Smit, <a title="Referenzen von Newsletter2Go" href="http://www.jetmobil.eu/" target="_blank">www.jetmobil.eu</a></em>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1408980048078{margin-bottom: 10px !important;}"][vc_column_inner width="1/6" css=".vc_custom_1409057034553{padding-right: 0px !important;}"][vc_single_image image="1689" border_color="grey" img_link_target="_self" img_size="full"][/vc_column_inner][vc_column_inner width="5/6" css=".vc_custom_1409057067984{margin-top: -10px !important;padding-left: 30px !important;}"][vc_column_text]<span style="color: #666666;">"Die beste eCommerce-Newsletter-Integration auf dem Markt."</span><br style="color: #666666;" /><em style="color: #666666;">J. Kazatchkov, <a title="Referenzen von Newsletter2Go" href="http://www.zugeschnuert-shop.de/" target="_blank">www.zugeschnuert-shop.de</a></em>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][vc_single_image image="1685" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Registration', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-registration';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408975569141{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1408974460688{margin-bottom: 20px !important;}"]
                                <h1>Jetzt kostenlos registrieren!</h1>
                                [/vc_column_text][vc_column_text]<span style="color: #666666;">Versenden Sie monatlich 1.000 Newsletter kostenlos und verwalten Sie beliebig viele Empfänger. Sämtliche Features unserer E-Mail-Marketingsoftware stehen Ihnen dabei gratis zur Verfügung.</span>[/vc_column_text][vc_column_text css=".vc_custom_1408977725650{margin-bottom: 20px !important;}"]
                                <h2>Ihre Vorteile:</h2>
                                [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                <ul>
                                	<li>1.000 Newsletter gratis</li>
                                	<li>Registrierung kostenlos</li>
                                	<li>Keine Vertragsbindung</li>
                                	<li>Keine Einrichtungsgebühren</li>
                                </ul>
                                [/vc_column_text][vc_column_text css=".vc_custom_1408974635351{margin-bottom: 12px !important;}"]
                                <h3>Zertifizierungen der Newsletter2Go Software</h3>
                                [/vc_column_text][vc_column_text css=".vc_custom_1408974764960{margin-bottom: 25px !important;}"]Dank unseren Zertifizierungen werden Ihre versendeten E-Mails nicht auf Spamverdacht überprüft. Das Ergebnis: Sie erreichen deutlich mehr Empfänger:[/vc_column_text][vc_single_image image="2114" border_color="grey" img_link_target="_self" img_size="full"][vc_column_text css=".vc_custom_1408977770071{margin-bottom: 12px !important;}"]
                                <h3>Höchste Kundenzufriedenheit:</h3>
                                [/vc_column_text][vc_column_text css=".vc_custom_1408977780448{margin-bottom: 25px !important;}"]Eine verkaufsstarke E-Mail-Marketingsoftware zu fairen Preisen.
                                Das ist unser Markenversprechen.[/vc_column_text][vc_single_image image="2115" border_color="grey" img_link_target="_self" img_size="full"][/vc_column][vc_column width="1/3" css=".vc_custom_1408973652706{padding-right: 20px !important;padding-left: 10px !important;}"][n2go_registration_form][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'SEO', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-seo';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408975950915{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1408975963927{margin-bottom: 20px !important;}"]
                                    <h1>H1 Das ist die H1-Überschrift</h1>
                                    [/vc_column_text][vc_column_text]
                                    <h2>H2 Das ist die H2 Überschrift</h2>
                                    [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<span style="font-weight: 600;">Ihre Vorteile:</span>
                                    <ul>
                                    	<li>Bullet-Point #1</li>
                                    	<li>Bullet-Point #2</li>
                                    	<li>Bullet-Point #3</li>
                                    	<li>Bullet-Point #4</li>
                                    </ul>
                                    [/vc_column_text][vc_column_text css=".vc_custom_1408976120723{margin-bottom: 12px !important;}"]
                                    <h3>H3 Bullet-Point #1</h3>
                                    [/vc_column_text][vc_column_text css=".vc_custom_1408976949289{margin-bottom: 25px !important;}"]Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1 Textblock Bulletpoint #1[/vc_column_text][vc_single_image border_color="grey" img_link_target="_self" image="2046" img_size="full"][vc_column_text css=".vc_custom_1409046218854{margin-bottom: 12px !important;}"]
                                    <h3>H3 Bullet-Point #2</h3>
                                    [/vc_column_text][vc_column_text css=".vc_custom_1409046460581{margin-bottom: 25px !important;}"]Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #1Textblock Bulletpoint #2 Textblock  ulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #1Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #1Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock

                                    Bulletpoint #2 Textblock Bulletpoint #1 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #2 Textblock Bulletpoint #1[/vc_column_text][vc_single_image border_color="grey" img_link_target="_self" image="3436" img_size="full"][vc_column_text css=".vc_custom_1409046225108{margin-bottom: 12px !important;}"]
                                    <h3>H3 Bullet-Point #3</h3>
                                    [/vc_column_text][vc_column_text css=".vc_custom_1409046495797{margin-bottom: 25px !important;}"]Hier erklären wir warum ein Newsletter Tool wichtig ist. BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla BLA bla Bla[/vc_column_text][vc_row_inner][vc_column_inner width="1/2" css=".vc_custom_1409056398513{padding-left: 40px !important;}"][vc_column_text el_class="n2go-textContainingCheckmarkList"]
                                    <ul>
                                    	<li>Wichtiger Unterpunkt</li>
                                    	<li>Wichtiger Unterpunkt 2</li>
                                    	<li>Wichtiger Unterpunkt 3</li>
                                    	<li>Wichtiger Unterpunkt 4</li>
                                    </ul>
                                    [/vc_column_text][/vc_column_inner][vc_column_inner width="1/4" css=".vc_custom_1409057561622{margin-top: 14px !important;}"][vc_single_image image="3514" border_color="grey" img_link_target="_self" img_size="100x100"][/vc_column_inner][/vc_row_inner][vc_single_image border_color="grey" img_link_target="_self" image="3436" img_size="full"][n2go_button title="Jetzt kostenlos testen" color="orange" size="default" link="url:http%3A%2F%2F185.21.103.80%2Fde%2Fregistrierung%2F|title:Registrierung%20f%C3%BCr%20Newsletter%202Go|"][/vc_column][vc_column width="1/3" css=".vc_custom_1408970923693{padding-right: 20px !important;padding-left: 10px !important;}"][n2go_registration_form][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'SEA', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-sea';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408975950915{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css=".vc_custom_1408975963927{margin-bottom: 20px !important;}"]
                                        <h1>H1 Das ist die H1-Überschrift</h1>
                                        [/vc_column_text][vc_column_text]
                                        <h2>H2 Das ist die H2 Überschrift</h2>
                                        [/vc_column_text][vc_column_text el_class="n2go-textContainingCheckmarkList"]<span style="color: #666666;">Um Ihre Mailing Kampagnen selber zu erstellen müssen Sie kein Profi sein. </span><br style="color: #666666;" /><span style="color: #666666;">Mit unserem Newsletter Editor können Sie kinderleicht Ihre Newsletter erstellen. Entweder wählen Sie eine aus unseren zahlreichen E-Mail Vorlagen aus oder Sie gestalten Ihren Newsletter in Ihrem eigenen Layout. </span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Ganz ohne Programmierkenntnisse können Sie schon vordefinierte Bausteine, wie Bilder, Texte, Personalisierungen in Ihren Newsletter übernehmen und beliebig gestalten.</span><br style="color: #666666;" /><br style="color: #666666;" /><span style="color: #666666;">Um den Erfolg der Newsletter-Kampagnen zu messen und ggf. Ihren Newsletter zu optimieren, stellt Ihnen Newsletter2Go ausführliche Reportings, wie zB. Clickmaps, Öffnungs- und Klickrate zur Verfügung.</span>[/vc_column_text][vc_column_text css=".vc_custom_1409052461637{margin-bottom: 25px !important;}" el_class="n2go-textContainingCheckmarkList"]<strong>Ihre Vorteile von Newsletter2Go auf einen Blick:
                                        </strong>
                                        <ul style="color: #666666;">
                                        	<li>Intuitiver und einfacher Editor</li>
                                        	<li>Zahlreiche Newsletter-Vorlagen (auch im thematischen Design)</li>
                                        	<li>Unlimitiertes Bilder-Hosting</li>
                                        	<li>Leichte Personalisierung Ihres Mailings</li>
                                        	<li>Umfangreiche Statistiken und Reportings</li>
                                        </ul>
                                        [/vc_column_text][vc_column_text css=".vc_custom_1409052506670{margin-bottom: 20px !important;}"]
                                        <h3>H3 Bullet-Point #2</h3>
                                        [/vc_column_text][vc_row_inner css=".vc_custom_1409052039115{margin-bottom: 15px !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409057209760{margin-top: 7px !important;}"][vc_single_image image="2871" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/newsletter-fuer-selbstaendige"][/vc_column_inner][vc_column_inner width="3/4"][vc_column_text]<span style="font-weight: 600; color: #666666;">Für Selbständige</span><br style="color: #666666;" /><span style="color: #666666;">Setzen Sie unkompliziert Ihr E-Mail-Marketingkampagnen auf indem Sie unsere zahlreichen kostenlosen Vorlagen und vordefinierte Bausteine, wie Bilder und Texte nutzen. </span><br style="color: #666666;" /><a title="Newsletter Sofware für Selbständige" href="https://www.newsletter2go.de/de/newsletter-fuer-selbstaendige"><em>mehr erfahren</em></a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409052039115{margin-bottom: 15px !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409057407030{margin-top: 7px !important;}"][vc_single_image image="2869" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/ecommerce-newsletter-software"][/vc_column_inner][vc_column_inner width="3/4"][vc_column_text]<span style="font-weight: 600; color: #666666;">Für E-Commerce und Händler</span><br style="color: #666666;" /><span style="color: #666666;">Nutzen Sie unsere einfache Webshop-Integration um Ihre Empfänger automatisch abzugleichen und Produkte mit nur einem Klick in den Newsletter zu übernehmen</span><br style="color: #666666;" /><a title="Newsletter Sofware für E-Commerce und Händler" href="https://www.newsletter2go.de/de/ecommerce-newsletter-software"><em>mehr erfahren</em></a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409052039115{margin-bottom: 15px !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409057237263{margin-top: 7px !important;}"][vc_single_image image="2868" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/newsletter-fuer-agenturen"][/vc_column_inner][vc_column_inner width="3/4"][vc_column_text]<span style="font-weight: 600; color: #666666;">Für Agenturen</span><br style="color: #666666;" /><span style="color: #666666;">Nutzen Sie unser Whitelabeling, Nutzerverwaltung, Partnerprogramm und umfangreiche Reports um Ihren Kunden erfolgreiches E-Mail-Marketing anbieten zu können. </span><br style="color: #666666;" /><a title="Newsletter Sofware für Agenturen" href="https://www.newsletter2go.de/de/newsletter-fuer-agenturen"><em>mehr erfahren</em></a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409052039115{margin-bottom: 15px !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409057247358{margin-top: 7px !important;}"][vc_single_image image="2870" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/newsletter-fuer-vereine"][/vc_column_inner][vc_column_inner width="3/4"][vc_column_text]<span style="font-weight: 600; color: #666666;">Für Vereine und NGOs</span><br style="color: #666666;" /><span style="color: #666666;">Überzeugen Sie sich von der Empfängersegmentierung, Lifecycle E-Mails und kostenlosen Newsletter-Vorlagen. Verschicken Sie Mailings zu bestimmten Anlässen, wie zB. Geburtstag. </span><br style="color: #666666;" /><a title="Newsletter Sofware für Vereine und NGOs" href="https://www.newsletter2go.de/de/newsletter-fuer-vereine"><em>mehr erfahren</em></a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409052039115{margin-bottom: 15px !important;}"][vc_column_inner width="1/4" css=".vc_custom_1409057253110{margin-top: 7px !important;}"][vc_single_image image="2872" border_color="grey" img_link_target="_self" img_size="full" link="https://www.newsletter2go.de/de/newsletter-fuer-unternehmen"][/vc_column_inner][vc_column_inner width="3/4"][vc_column_text]<span style="font-weight: 600; color: #666666;">Für Großunternehmen</span><br style="color: #666666;" /><span style="color: #666666;">Nutzen Sie die leistungsstarke Software von Newsletter2Go um Ihr E-Mail-Marketing erfolgreich zu machen. Ausführliche Reportings lassen Ihnen Ihre Kampagnen stets optimieren. </span><br style="color: #666666;" /><a title="Newsletter Sofware für Unternehmen" href="https://www.newsletter2go.de/de/newsletter-fuer-unternehmen"><em>mehr erfahren</em></a>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3" css=".vc_custom_1408970923693{padding-right: 20px !important;padding-left: 10px !important;}"][n2go_registration_form][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'Imprint', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-imprint';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408982020665{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]
                            <h1 style="text-align: left;">Impressum</h1>
                            [/vc_column_text][vc_row_inner css=".vc_custom_1409043661770{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Diensteanbieterin:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]Newsletter2Go GmbH[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043704178{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Anschrift:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]Marie-Elisabeth-Lüders-Str. 1
                            10625 Berlin[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043709848{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Telefon:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]0800 7 SUPPORT
                            0800 778 776 77
                            +49 (030) 59 00 833 83[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Fax:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]+49 (030) 59 00 833 84[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043770857{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]E-mail:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text]<a href="mailto:info@newsletter2go.de">info@newsletter2go.de</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Internet:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043764661{margin-bottom: 0px !important;}"]<a href="http://www.newsletter2go.de/">www.newsletter2go.de</a>[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Vertretungsberechtigter Geschäftsführer:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043796341{margin-bottom: 0px !important;}"]Christoph Beuck, Steffen Schebesta[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Registernummer:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043817806{margin-bottom: 0px !important;}"]HRB 133191 B[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Sitz der Gesellschaft:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043842389{margin-bottom: 0px !important;}"]Berlin - Deutschland[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Umsatzsteuer-Identifikationsnummer:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043861029{margin-bottom: 0px !important;}"]DE 276534027[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Haftungshinweis:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043882068{margin-bottom: 0px !important;}"]Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1409043714976{margin-bottom: 0px !important;}"][vc_column_inner width="1/3"][vc_column_text]Bildernachweis:[/vc_column_text][/vc_column_inner][vc_column_inner width="2/3"][vc_column_text css=".vc_custom_1409043902388{margin-bottom: 0px !important;}"]Folgende Bildauthoren von fotolia.com sind aufzuführen: © evgeniya_m, © adisa, © iko, © Andres Rodriguez, © Africa Studio, © Halfpoint, © Syda Productions, © tom, © vege, © Stefan Schurr, © konradbak, © Denchik.[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		$template = array();
		$template[ 'name' ] = __( 'AGB', 'custom_template_for_vc' );
		$template[ 'image_path' ] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/visual-composer/templates/images/feature_list.png' ); // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'n2go-template-agb';
		$template[ 'content' ] = '[vc_row css=".vc_custom_1408982020665{margin-bottom: 20px !important;padding-top: 13px !important;}"][vc_column width="1/1"][n2go_breadcrumbs][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]
                            <h1 style="text-align: left;">Allgemeine Geschäftsbedingungen
                            für die Nutzung von "Newsletter2Go"</h1>
                            [/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text]<strong>§ 1 Grundlagen</strong>

                            (1) Diese AGB gelten für sämtliche Leistungen und Vereinbarungen zwischen dem Betreiber und dem Kunden im Zusammenhang mit auf der Internetseite www.newsletter2go.de angebotenen Leistungen.

                            (2) Anderweitige Allgemeine Geschäftsbedingungen oder ähnliche Klauselwerke des Kunden finden keine Anwendung. Diesen widerspricht der Betreiber hiermit ausdrücklich.

                            (3) Der Betreiber betreibt unter der Bezeichnung "Newsletter2Go" und unter www.newsletter2go.de ein Internetportal, das schwerpunktmäßig die Gestaltung und/oder den Versand von Newslettern mittels E-Mail und SMS an Empfänger hat.

                            (4) Das Angebot richtet sich ausschließlich an gewerbliche Kunden im Sinne von § 14 Abs. 1 BGB. Verträge mit Verbrauchern im Sinne von § 13 BGB werden vom Betreiber nicht abgeschlossen.

                            <strong>§ 2 Definitionen</strong>

                            Für diese AGB gelten folgende Begriffsdefinitionen:
                            <ul>
                                <li>"Betreiber" ist die Newsletter2Go GmbH, vertreten durch die Geschäftsführer Christoph Beuck und Steffen Schebesta, Marie-Elisabeth-Lüders-Str. 1 in 10625 Berlin.</li>
                                <li>"Kunde" ist jede natürliche oder juristische Person oder sonstige Personenvereinigung sowie jedes öffentlich-rechtliches Sondervermögen, die/das sich auf dem Internetportal des Betreibers registrieren/registriert.</li>
                                <li>"Empfänger" ist jede natürliche oder juristische Person oder sonstige Personenvereinigung sowie jedes öffentlich-rechtliches Sondervermögen, die/das eine über das Internetportal des Betreibers bzw. den unter der Marke "Newsletter2Go" angebotenen Dienst versendete und/oder gestaltete E-Mail oder SMS empfängt.</li>
                                <li>"Nichtkunde" ist jede natürliche oder juristische Person oder sonstige Personenvereinigung sowie jedes öffentlich-rechtliches Sondervermögen, die/das nicht Kunde ist.</li>
                                <li>"Jeder" ist Kunde, Empfänger und Nichtkunde.</li>
                                <li>"Newsletter" sind personalisierte Informationen oder Werbungen.</li>
                                <li>"www.newsletter2go.de" umfasst die Hauptdomains "www.newsletter2go.de", "www.nl2go.de" und "www.newslettertogo.de" und entsprechende Top-Level-Domains sowie Sub-Level-Domains und Unterverzeichnisse der Hauptdomain und/oder der entsprechenden Top-Level-Domains.</li>
                                <li>"Kundendaten" sind alle personen- oder unternehmensbezogenen Daten, die ein Nichtkunde bei seiner Registrierung oder später angibt.</li>
                                <li>"Planbare Wartungsarbeiten" sind solche Wartungs- und Updatearbeiten, die für den Betreiber vorhersehbar und planbar sind. "Unplanbare" Wartungsarbeiten sind solche Wartungs- und Updatearbeiten, die für den Betreiber nicht vorhersehbar oder planbar sind, etwa Angriffe auf das System des Betreibers, Versagen der Hardware oder Fälle höherer Gewalt.</li>
                            </ul>
                            Auf die gewerbliche oder private Tätigkeit und/oder die öffentlich-rechtliche oder privat-rechtliche Natur einer Person, Personenvereinigung kommt es für diese Definitionen nicht an.

                            <strong>§ 3 Registrierung, Demoversion</strong>

                            (1) Die Nutzung des gesamten Leistungsangebotes des Betreibers setzt eine Registrierung voraus. Unregistriert kann jeder eine Demoversion auf www.newsletter2go.de einsehen, die bestimmte Leistungsbausteine simuliert und rudimentär zur Verfügung stellt.

                            (2) Die Demoversion dient nur der Veranschaulichung und Bewerbung des Leistungsangebotes des Betreibers. Insbesondere die Versendung von Newslettern und die Speicherung von Daten auf dem System des Betreibers sind ausschließlich im Falle einer Registrierung möglich. Ein Anspruch auf die Nutzung der Demoversion besteht nicht.

                            (3) Zur Nutzung des gesamten Leistungsspektrums des Betreibers durch einen Nichtkunden ist eine Registrierung unter Anerkennung dieser AGB erforderlich. Der Nichtkunde muss dazu die abgefragten Kundendaten in die Abfragemaske auf dem Internetportal des Betreibers eingeben. Nach der Absendung der Kundendaten an das System des Betreibers erhält der Nichtkunde eine E-Mail an die von ihm angegebene E-Mail-Adresse mit einem Bestätigungslink. Mit der Bestätigung durch die Anwahl des Links nimmt der Nichtkunde das Angebot zum Abschluss des Vertrages mit dem Betreiber unter Einbezug und Anerkennung dieser AGB an. Der Registrierungsvorgang ist erst mit der Anwahl des Links abgeschlossen (Double Opt-In-Verfahren).

                            <strong>§ 4 Leistungsangebot des Betreibers</strong>

                            (1) Der Betreiber bietet seinem Kunden auf der Internetplattform www.newsletter2go.de die Möglichkeit, personalisierte E-Mail- und SMS-Newsletter zu gestalten, zu verwalten, zu versenden und auszuwerten.

                            (2) Zudem beinhaltet das Angebot eine integrierte Empfängerdatenverwaltung. Dabei besteht für den Kunden die Möglichkeit, durch händische Eingabe oder durch den Upload unterschiedlicher Dateiformate, wie beispielsweise xls, csv, txt, ods, Empfängerdaten (etwa der Kunden des Kunden) in eine Datenbank beim Betreiber einzustellen, zu speichern und für die Versendung der Newsletter vorzuhalten sowie diese zu verwalten, zu bearbeiten und zu löschen.

                            (3) Das Angebot des Betreibers bietet dem Kunden auch Reporting- und Auswertungsmöglichkeiten. So kann sich der Kunde beispielsweise Öffnungs-, Klick-, Bounce- und Conversion-Raten die zeitliche Entwicklung der Empfängerzahlen oder die örtliche Verteilung der Empfänger anzeigen lassen.

                            (4) Ferner kann der Kunde vom Betreiber bereitgestellte HTML-Templates für die Gestaltung einzelner Newsletter auswählen und nutzen, ohne dass aber ein Anspruch oder Recht des Kunden auf eine ausschließliche bzw. exklusive Verwendung besteht.

                            (5) Der Betreiber ist berechtigt, Teile oder die Gesamtheit seiner Leistungen durch Dritte, insbesondere Subunternehmen, erbringen zu lassen.

                            <strong>§ 5 Erreichbarkeit des Dienstes, Wartungsarbeiten</strong>

                            (1) Der Betreiber gewährleistet eine Erreichbarkeit des Dienstes von 95% bei einer quartalsweisen Betrachtung. Nicht berücksichtigt werden dabei "Down"-Zeiten, die nicht auf einem Verschulden des Betreibers beruhen, etwa Angriffe auf das System des Betreibers, nicht im Voraus erkennbares Versagen der Hardware oder Fälle höherer Gewalt, sowie damit zusammenhängende unplanbare Wartungsarbeiten durch den Betreiber.

                            (2) Planbare Wartungsarbeiten finden regelmäßig in der Zeit zwischen 22 Uhr und 6 Uhr statt und werden mindestens 3 Tage vorher per E-Mail gegenüber dem Kunden angekündigt.

                            (3) Unplanbare Wartungsarbeiten werden spätestens binnen 12 Stunden nach Eintritt der Störung (Unerreichbarkeit) begonnen und schnellstmöglich beendet.

                            <strong>§ 6 Empfangsfehler und Nichterreichbarkeit beim Empfänger</strong>

                            (1) Der Betreiber übernimmt keine Gewähr dafür, dass der Newsletter tatsächlich den Empfänger erreicht, bzw. für das Ausbleiben von Empfangsfehlern, sofern diese nicht auf einer Fehlfunktion der Software und Infrastruktur des Betreibers oder eingeschalteter Subunternehmer beruhen, sondern auf Umständen, die außerhalb der Sphäre des Betreibers liegen.

                            (2) Empfangsfehler sind beispielsweise bei E-Mails Fälle, bei denen die E-Mail vom SPAM-Filter abgefangen wird, das Postfach voll ist, die E-Mail-Adresse nicht existiert, falsch ist oder auf einer sog. Robinsonliste steht, und bei SMS Fälle, bei denen das Endgerät ausgeschaltet ist, keinen Netzzugang hat, der Speicher voll ist oder die Nummer nicht existiert oder auf einer sog. Robinsonliste steht.

                            (3) Der Kunde bleibt bei nicht in der Sphäre des Betreibers liegenden Empfangsfehlern zur Zahlung des entsprechenden Entgelts verpflichtet.

                            <strong>§ 7 Datensicherheit</strong>

                            (1) Der Betreiber gewährleistet größtmögliche Datensicherheit für alle vom Kunden zur Verfügung gestellten Daten. Dabei werden die Daten eines jeden Kunden auf einem jeweils zugewiesenen Daten-Container gehostet und dem Zugriff anderer Kunden oder Nichtkunden entzogen.

                            (2) Der Betreiber wird zum Schutz aller Daten die dem aktuellen Stand der Technik entsprechende Technologie (Software und Hardware) vorhalten und auch künftig einen entsprechenden Standard beibehalten und fortentwickeln. Sofern Leistungen von anderen Unternehmen zugekauft werden, achtet der Betreiber darauf, dass diese einen vergleichbaren Standard der Datensicherheit gewährleisten.

                            <strong>§ 8 Entgelt</strong>

                            (1) Der Kunde hat für die Versendung der einzelnen Newsletter ein Entgelt pro versandter E-Mail oder SMS zu entrichten. Für die optionale Erstellung individueller (auf Bestellung angefertigter) HTML-Templates ist ein pauschales Entgelt zu entrichten. Für frei verfügbare HTML-Templates ist kein Entgelt zu entrichten.

                            (2) Die Preisgestaltung richtet sich nach den jeweils im Zeitpunkt der Inanspruchnahme der Leistung auf der Internetseite des Betreibers vorgehaltenen Preisangaben. Dessen ungeachtet kündigt der Betreiber Preisänderungen gegenüber seinen Kunden mindestens 7 Tage vorher per E-Mail an.

                            (3) Die Preise sind, wenn nicht ausdrücklich anders gekennzeichnet, als Nettopreise angegeben und verstehen sich zuzüglich Umsatzsteuer in gesetzlicher Höhe.

                            (4) Die sonstigen Leistungen der Internetplattform werden unentgeltlich bereitgestellt. Der Betreiber behält sich vor, einzelne unentgeltliche Leistungen zu verändern, einzustellen oder - bei vorheriger Ankündigung - entgeltlich auszugestalten.

                            <strong>§ 9 Zahlungsmodalitäten</strong>

                            (1) Der Kunde leistet das Entgelt auf sein Guthabenkonto im Rahmen eines Pre-Paid-Verfahrens per Vorkasse. Als Zahlungsmethoden stehen regelmäßig paypal, Sofortüberweisung und Überweisung zur Auswahl, ohne dass der Kunde einen Anspruch auf ein bestimmtes Zahlungsmittel hätte.

                            (2) Der Kunde muss, bevor er die Leistungen des Betreibers nach erfolgter Registrierung nutzen kann, sein Guthabenkonto aufladen (Pre-Paid-Verfahren). Dabei bestimmt der Kunde selbst den Betrag, mit dem er sein Guthabenkonto aufladen möchte.

                            (3) Eine Auszahlung ist grundsätzlich nur nach Kündigung des Kundenprofils bzw. des Kundenkontos gemäß §§ 14 f. dieser AGB möglich.

                            (4) Sollten Zahlungsvorfälle, die von dem Kunden ausgelöst werden, nicht ordnungsgemäß durchgeführt werden können bzw. ausfallen, etwa bei Rücklastschriften wegen Konto-Unterdeckung oder einer fehlerhaften Bankverbindung, trägt der Kunde die dem Betreiber dadurch entstandenen Kosten und eine Bearbeitungsgebühr von 5,00 EUR.

                            <strong>§ 10 Daten- und verbraucherschutzrechtliche Verhaltensregeln</strong>

                            (1) Der Betreiber und der Kunde verpflichten sich, die gesetzlichen Bestimmungen zum Datenschutz, insbesondere nach TMG (Telemediengesetz) und BDSG (Bundesdatenschutzgesetz) der Bundesrepublik Deutschland einzuhalten und zu achten. Der Betreiber ist an die Weisungen des Kunden hinsichtlich der von dem Kunden in das System eingegebenen Daten gebunden.

                            (2) Der Betreiber ist auf Weisung des Kunden verpflichtet, den gesamten Datenbestand des Kunden und/oder Teile der Daten zu löschen, es sei denn, der Betreiber ist aus rechtlichen Gründen zur Aufbewahrung der Daten berechtigt oder verpflichtet. In diesem Fall wird der Betreiber die Daten sperren.

                            (3) Der Kunde sichert zu, dass er für jede einzelne der in § 4 genannten Leistungen des Betreibers die erforderlichen datenschutzrechtlichen Einwilligungen zur Verwendung und Nutzung der Empfängerdaten vor Inanspruchnahme der Leistung eingeholt hat, insbesondere, aber nicht abschließend, soweit die Einwilligung erforderlich ist, Daten auf das System des Betreibers hochzuladen, zu speichern, auf diesem zu verwalten und zu bearbeiten, entsprechend zu verwenden sowie zu Reporting und Auswertung zu verarbeiten und zu nutzen. Der Kunde sichert ferner für den Fall, dass durch den Versand von Newslettern Daten- und/oder Verbraucherschutzvorschriften anderer Staaten als der BRD betroffen sind, die Einhaltung auch dieser Schutzvorschriften für jede einzelne der von ihm genutzten Leistungen zu.

                            (4) Der Kunde sichert zu, dass er bei Inanspruchnahme der Leistungen des Betreibers seine Kunden jederzeit in dem gesetzlich erforderlichen Umfang über deren sich aus daten- und verbraucherschützenden Regelungen ergebende Rechte aufklärt und informiert.

                            (5) Der Betreiber ist berechtigt, Daten des Kunden, insbesondere Empfängerdaten, entgegen einer Löschungsanweisung des Kunden oder im Falle der Kündigung des Kundenprofils bzw. des Kundenkontos gemäß §§ 14 f. dieser AGB weiter zu speichern, zu verwenden und weiter zu geben, sofern dies für die Darlegung und den Beweis eines Gesetzesverstoßes oder gegen die Pflichten des Kunden erforderlich ist und/oder Behörden, Gerichte oder sonstige Stellen es rechtlich zulässig fordern. Der Betreiber achtet darauf, dass er nur in dem zwingend erforderlichen Umfang gemessen an dem Zweck dieser Berechtigung die Daten des Kunden speichert, verwendet oder weitergibt.

                            (6) Unbeschadet weitergehender nationaler Regelungen ist der Kunde verpflichtet, die jeweils aktuellen Mindestanforderungen der Certified Senders Alliance anzuwenden und zu berücksichtigen, die unter § 16 einsehbar sind.

                            <strong>§ 11 Weitere wichtige Verhaltensregeln des Kunden</strong>

                            (1) Der Kunde verpflichtet sich folgende Verhaltensregeln zu befolgen und sicher zu stellen:

                            - Die Gestaltung, Verwaltung oder Versendung von Nachrichten mit pornografischen, gewalt-verherrlichenden, diskriminierenden, politischen, religiösen, gesetzlich verbotenen, jugendgefährdenden, gegen die guten Sitten verstoßenden oder die öffentliche Ordnung und Sicherheit gefährdenden Inhalten ist verboten.

                            - Die Gestaltung, Verwaltung oder Versendung von Newslettern darf nicht gegen wettbewerbsrechtliche Vorschriften verstoßen und muss insbesondere die in § 7 UWG geregelten Voraussetzungen für die Zulässigkeit von Werbung beachten.

                            - Der Empfänger der Werbung muss in die Zusendung der Werbung und die Werbemaßnahme als solche den gesetzlichen Anforderungen entsprechend vor der Werbemaßnahme eingewilligt haben und darf diese Einwilligung nicht widerrufen haben.

                            - Sofern der Kunde die optionalen Tracking-Möglichkeiten (Öffnungsverhalten, Klickverhalten, Geolocating, u.ä.) von Newsletter2Go nutzt, benötigt er die Einwilligung der Empfänger in die Erhebung und Speicherung personenbezogener Daten iSv § 12 TMG. Hat der Kunde eine entsprechende Einwilligung nicht, ist es ihm möglich bei jedem Newsletter das Tracking zu deaktivieren.

                            - Urheber-, Marken- und sonstige Schutzrechte oder andere rechtlich geschützte Belange des Betreibers, Dritter oder anderer Kunden dürfen nicht verletzt werden. Der Kunde muss über alle erforderlichen Rechte und Einwilligungen verfügen und diese im Streitfall nachweisen können.

                            - Die Log-In-Daten, Passwort und Kundenname für das Kundenprofil bzw. Kundenkonto sind geheim zu halten und vom Kunden nur solchen Personen zur Verfügung zu stellen, die mit der Gestaltung, der Verwaltung oder der Versendung von Newslettern betraut wurden. Bei einem Verlust der Log-In-Daten oder wenn der Kunde Kenntnis davon erlangt, dass ein nicht mit der Gestaltung, der Verwaltung oder der Versendung betrauter Dritter über die Log-In-Daten verfügt oder diese einem solchen Dritten bekannt geworden sind, müssen diese unverzüglich vom Kunden geändert werden.

                            - Die Bewerbung von Premium-Diensten und Mehrwertnummern ist ohne schriftliche Zustimmung des Betreibers nicht gestattet.

                            (2) Der Versand von E-Mail- und SMS-Nachrichten darf nur durch den Inhaber des Kundeprofils bzw. Kundenkontos oder eine von ihm bevollmächtigte Person erfolgen.

                            (3) Der Kunde ist verpflichtet, alle Angaben zu seinen Personen- und/oder Unternehmensdaten, insbesondere Name, Firma, Kontodaten, Anschrift, auf dem aktuellen Stand zu halten und wahrheitsgemäß anzugeben.

                            <strong>§ 12 Schadensersatz, Vertragsstrafe und Freistellung durch den Kunden</strong>

                            (1) Der Kunde verpflichtet sich bei einem Verstoß gegen seine vertraglichen und/oder gesetzlichen Pflichten Schadensersatz an den Betreiber zu leisten.

                            (2) Der Kunde verpflichtet sich bei einem Verstoß gegen seine Pflichten gemäß §§ 10 f. dieser AGB zur Zahlung einer Vertragsstrafe von 5.001 EUR. Die Geltendmachung eines weitergehenden Schadensersatzes bleibt zulässig.

                            (3) Der Kunde verpflichtet sich für den Fall eines Verstoßes gegen seine Pflichten gemäß §§ 10 f. dieser AGB, den Betreiber von sämtlichen Ansprüchen Dritter freizustellen und ihn auf Anforderung des Betreibers bei der Verteidigung gegenüber Behörden, Gerichten, sonstigen Einrichtungen oder Dritten zu unterstützen und die Kosten zu tragen.

                            (4) Eine Haftung nach Abs. 1-3 scheidet immer dann aus, wenn der Kunde den Verstoß nicht gemäß § 276-278 BGB zu vertreten hat.

                            <strong>§ 13 Haftung des Betreibers</strong>

                            (1) Der Betreiber haftet gegenüber dem Kunden bei Vorsatz und grober Fahrlässigkeit. Für Schäden an Leben, Körper und Gesundheit haftet der Betreiber auch bei einfacher Fahrlässigkeit.

                            (2) Der Betreiber haftet zudem für einfache Fahrlässigkeit, wenn wesentliche Vertragspflichten (sog. Kardinalpflichten, d.h. alle Rechte und Pflichten, die dem Vertrag sein Gepräge geben, die er nach seinem Inhalt und Zweck gerade zu gewähren hat, deren Erfüllung die ordnungsgemäße Durchführung des Vertrages überhaupt erst ermöglicht oder auf deren Einhaltung der Vertragspartner regelmäßig vertraut und vertrauen darf) verletzt werden.

                            (3) Bei einfacher oder grober Fahrlässigkeit ist die Haftung für entgangenen Gewinn und nicht vertragstypischer Weise vorhersehbare Schäden gegenüber dem Kunden ausgeschlossen, es sei denn, es handelt sich um Schäden an Leben, Körper und Gesundheit.

                            (4) Ungeachtet der Abs. 1-3 haftet der Betreiber für Zusicherungen, Garantien und für Fälle zwingender, verschuldendunabhängiger Haftung, etwa nach dem Produkthaftungsgesetz, unbegrenzt.

                            (5) Die Abs. 1-4 gelten auch für die Haftung für gesetzliche Vertreter und/oder Erfüllungsgehilfen des Betreibers.

                            <strong>§ 14 Vertragslaufzeit, Kündigung</strong>

                            (1) Der Vertrag zwischen Betreiber und Kunden wird unbefristet geschlossen.

                            (2) Beide Parteien können den Vertrag mit einer Frist von einem Tag zum Folgetag ordentlich kündigen. Die außerordentliche Kündigung ist jederzeit möglich.

                            (3) Die ordentliche Kündigung muss in schriftlicher oder elektronischer Form gemäß §§ 126 f. BGB erfolgen. Die schriftliche Kündigung an den Betreiber kann an folgende Anschrift erfolgen:

                            Newsletter2Go GmbH, Marie-Elisabeth-Lüders-Str. 1, 10625 Berlin

                            <strong>§ 15 Abwicklung nach Kündigung</strong>

                            (1) Die vom Kunden hinterlegten Daten und das angelegte Kundenprofil bzw. das Kundenkonto werden von dem Betreiber mit Beendigung des Vertrages gemäß § 14 dieser AGB gelöscht. Sofern der Betreiber verpflichtet ist, Daten aus gesetzlichen und/oder vertraglichen Gründen nicht zu löschen, wird er sie sperren und ausschließlich im Rahmen dieser Gründe verwenden und/oder weitergeben. Mit Erreichung der gesetzlichen Zwecke werden die Daten gelöscht.

                            (2) Ein noch vorhandenes Guthaben des Kunden wird an diesen ausbezahlt. Der Kunde ist verpflichtet, ohne dass es einer Aufforderung des Betreibers bedarf, dem Betreiber seine Bankverbindung mitzuteilen. Die Auszahlung wird 30 Tage nach Beendigung des Vertrages und der Mitteilung der Bankverbindung durch den Kunden fällig.

                            <strong>§ 16 Mindestanforderungen der Certified Senders Alliance</strong>

                            (1) Der Kunde muss bei der Versendung einer Werbesendung klar erkennbar sein. Jede versendete E-Mail muss ein leicht erkennbares Impressum enthalten, entweder im Text oder über einen unmittelbaren Link erreichbar.

                            Das Impressum muss die nachfolgenden Angaben enthalten:

                            - den Namen und die Anschrift, unter der der Kunde niedergelassen ist, bei juristischen Personen zusätzlich die Rechtsform, den Vertretungsberechtigten und das Handelsregister, Vereinsregister, Partnerschaftsregister oder Genossenschaftsregister, in das sie eingetragen sind und die entsprechende Registernummer;

                            - Kontaktinformationen, mindestens jedoch eine gültige Telefonnummer oder ein elektronisches Kontaktformular sowie
                            - eine E-Mail-Adresse und

                            - in Fällen, in denen eine Umsatzsteueridentifikationsnummer nach § 27a des Umsatzsteuergesetzes oder eine Wirtschaftsidentifikationsnummer nach § 139c der Abgabenordnung vorhanden ist, die Angabe dieser Nummer.
                            Weitergehende Informationspflichten nach § 5 Abs. 1 des Telemediengesetzes(TMG) bleiben unberührt.

                            (2) Der Versand von E-Mails erfolgt nur an Adressaten, die ihre Einwilligunghierzu erteilt haben (Double-Opt-In) oder sich mit dem Werbenden in bestehenden Auftraggebernbeziehungen befinden und die Voraussetzungen des Art 13 Abs. 2 der Datenschutzrichtlinie für elektronische Kommunikation 2002/58/EG des Europäischen Parlaments und des Rates vom 12. Juli 2002 eingehalten wurden. In diesem Zusammenhang wird dem Kunden ausdrücklich empfohlen, die Erhebung von Empfänger-Adressdaten über das Internet vorzugsweise über das so genannte Double-Opt-In-Verfahren zu realisieren.

                            (3) Die Einwilligung in die Zusendung von Werbung mittels E-Mails muss gesondert erfolgen. Der Empfänger muss entweder ein Kästchen anklicken /ankreuzen oder sonst eine vergleichbar eindeutige Erklärung seiner Zustimmung in die Werbung mittels E-Mails abgeben. Diese Erklärung darf nicht Bestandteil anderer Erklärungen sein (z.B. Einwilligung in die Geltung von Allgemeinen Geschäftsbedingungen).

                            (4) Die Empfänger müssen ihre Einwilligung aktiv durch eine bewusste Handlung abgeben. Es dürfen keine vorangeklickten / vorangekreuzten Kästchen verwendet werden.

                            (5) Auf die Möglichkeit des Widerrufs der Erlaubnis, E-Mails zuzusenden, ist in jeder E-Mail hinzuweisen. Hinweise auf diese Möglichkeit sind in jede versendete Nachricht aufzunehmen. Das Abbestellen von E-Mails muss grundsätzlich durch den Empfänger ohne Kenntnisse von Zugangsdaten (beispielsweise Login und Passwort) möglich sein. Ausnahmen dazu können im Einzelfall zugelassen werden, wenn eine abweichende Handhabung aufgrund von Besonderheiten des angebotenen Dienstes erforderlich ist. Abmeldungen sind unverzüglich zu bearbeiten.

                            (6) Der Betreiber nimmt automatisiert E-Mail Adressen von der Mailingliste des Kunden, sobald ein sog. Hard-Bounce auf dieser E-Mail-Adresse erfolgte.

                            (7) Der Kunde hat einen Ansprechpartner mit Telefonnummer und E-Mail-Adresse für Beschwerden zu benennen. Die Reaktionszeit hat maximal 24 h werktäglich zu betragen.

                            (8) In der Kopf- und Betreffzeile der E-Mail darf der Kunde weder den Absender noch den kommerzielle Charakter der Nachricht verschleiern oder verheimlichen.
                            Ein Verschleiern oder Verheimlichen liegt dann vor, wenn die Kopf- und Betreffzeile absichtlich so gestaltet sind, dass der Empfänger vor Einsichtnahme in den Inhalt der Kommunikation keine oder irreführende Informationen über die tatsächliche Identität des Absenders oder den kommerziellen Charakter der Nachricht erhält.

                            (9) Bei der Verwendung von E-Mail-Adressen, die der Kunde von Dritten erworben hat, ist der Kunde verpflichtet, sich vor der Vornahme von Werbehandlungen zu vergewissern, dass tatsächlich nur solche Empfänger angeschrieben werden, die eine Einwilligung im Sinne dieser Aufnahmekriterien erklärt haben, die sich nicht nur auf den Versand durch einen Dritten sondern auch durch den Kunden selbst bezieht.

                            (10) Die Gewinnung von Empfänger-Adressdaten für Dritte (etwa durch Co-Sponsoring) muss gegenüber dem Empfänger transparent sein. Insbesondere dürfen so gewonnene Empfänger-Adressdaten für eine Versendung nur genutzt werden, wenn bei Erhebung

                            - die Unternehmen, für die die Empfänger-Adressdaten generiert wurden, transparent, namentlich und unter Angabe der Branche einzeln benannt wurden,

                            - die Kenntnisnahme der Liste der Unternehmen für den Empfänger leicht und eindeutig möglich war und

                            - die Anzahl der Unternehmen bzw. Personen, für die die Empfänger-Adressdaten erhoben wurden, auf ein Maß reduziert ist, das die Weiterleitung der Empfänger-Adressdaten an einen unverhältnismäßig großen Kreis Dritter ausschließt und dem Empfänger erlaubt, die Tragweite und der Umfang seiner Einwilligung einfach zu erfassen sowie den rechtmäßigen Umgang mit seinen Daten einfach zu kontrollieren.

                            Klarstellend sei darauf hingewiesen, dass die Unternehmen, für die die Empfänger-Adressdaten generiert werden, diese Empfänger-Adressdaten nicht an Dritte weitergeben dürfen, ohne dass vom Empfänger dafür eine weitere Einwilligung gesondert eingeholt wurde.

                            <strong>§ 17 Schlussbestimmungen</strong>

                            (1) Der Gerichtsstand ist Berlin (Charlottenburg), sofern der Kunde Kaufmann, juristische Person des öffentlichen Rechts oder öffentlich-rechtliches Sondervermögen ist.

                            (2) Es gilt ausschließlich das Recht der Bundesrepublik Deutschland. Die Geltung des Internationalen Privatrechtes ist für Kunden ausgeschlossen.

                            (3) Sollten Klauseln oder Klauselteile dieser AGB unwirksam sein, so berührt dies nicht die Wirksamkeit des sonstigen Vertrages und die Geltung dieser AGB im Übrigen. An Stelle der unwirksamen Klausel oder des unwirksamen Klauselteils gilt das Gesetzesrecht.

                            (4) Nebenabreden, Änderungen oder Ergänzungen zu diesem Vertrag bedürfen zu ihrer Wirksamkeit der Schriftform. Auch die Aufhebung des Schriftformerfordernisses bedarf zu ihrer Wirksamkeit der Schriftform.[/vc_column_text][/vc_column][vc_column width="1/3"][/vc_column][/vc_row]';
		array_push( $templates, $template );
		unset( $template );


		return $templates;
	}

}


$removeStyles = false;

if ( $removeStyles ) {
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => array( 'post', 'page', 'features', 'help_topic' )
	);

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) :
		setup_postdata( $post );

		$post->post_content = preg_replace( '/(<(?!img)\w+[^>]+)(style="[^"]+")([^>]*)(>)/', '${1}${3}${4}', $post->post_content );


		wp_update_post( $post );
	endforeach;

	wp_reset_postdata();
}

$removeTarget = false;

if ( $removeTarget ) {
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => array( 'post', 'page', 'features', 'help_topic' )
	);

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) :
		setup_postdata( $post );

		$post->post_content = preg_replace( '/(<a([^>]+?)href="https:\/\/www.newsletter2go.[^>]+?)\starget="_blank"([^>]*)/', '${1}${3}', $post->post_content );

		wp_update_post( $post );
	endforeach;

	wp_reset_postdata();
}


// Text param for VC
// vc_add_shortcode_param( 'text', 'TextSettingsField' );
// function TextSettingsField( $settings, $value ) {
//     $settings['type']           = isset ( $settings['type'] ) ? $settings['type'] : '' ;
//     $settings['param_name']     = isset ( $settings['param_name'] ) ? $settings['param_name'] : '' ;
//     $settings['options']        = isset ( $settings['options'] ) ? $settings['options'] : '' ;
//     $uniqid                     = substr(uniqid(rand(),1),0,7);
//     $output                     = '';
	
// 	$output .= '
// 	<div class="ng-text-wrap ng-text' . esc_attr( $uniqid ) . '">
// 		'.esc_attr( $value ).'
// 	</div>';

//     return $output;

// }

// Add VC map
add_action( 'vc_before_init', 'VcMapDownloadCenter' );
function VcMapDownloadCenter() {
	vc_map(
		[
			'name' 		=> __( 'Download Center' ),
			'base' 		=> 'download_center',
			'category' 	=> __( 'Download Center' ),
			'params' 	=> [
				[
					'type' 			=> 'text',
					'param_name' 	=> 'text',
					'value' 		=> __( 'JUST ADD IT' ),
				],
			]
		]
	);
}





// Download-Center Ajax
add_action( 'wp_ajax_GetPluginsWithAjax', 'GetPluginsWithAjax' );
add_action( 'wp_ajax_nopriv_GetPluginsWithAjax', 'GetPluginsWithAjax' );

function GetPluginsWithAjax()
{
	$Nonce = $_POST['nonce']; 
	$Edition = $_POST['Edition'];
	$SysName = $_POST['SysName'];

	// Verify nonce field passed from javascript code
	if ( ! wp_verify_nonce( $Nonce, 'ajaxnonce' ) )
		die ( 'Busted!');
	
	include_once( get_template_directory() . '/download-center.php' );
	
	$Data = new IntegrationPlugin($sampleData);

	echo json_encode($Data->GetSystemsViaNameAndEdition($SysName,$Edition));

	wp_die();
}


// Create VC shortcode (download-center)
function VcDownloadCenterOutput( $atts, $content )
{
    $atts = shortcode_atts(
		[],
		$atts,
		'download_center'
	);

	
	include_once( get_template_directory() . '/download-center.php' );

	// Load CSS
	wp_enqueue_style( 'ng-download-center-style', get_template_directory_uri() . '/visual-composer/styles/n2go-download-center.css' );

	// Load JS
	wp_enqueue_script( 'ng-download-center', get_template_directory_uri() . '/scripts/download-center/download-center.js', array('jquery'), '1.0', true );
	wp_localize_script('ng-download-center', 'download_center', array(
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ajaxnonce')
	));


	$Data = new Integration($sampleData);

	echo '<div class="ng-download-center">';
		foreach ($Data->Type() as $TypeKey => $TypeName) {

			// Main title for each section 
			echo '<h2 style="text-align: center"><a id="a"></a>'.$TypeName.'-'.esc_html__('Integrationen' ,'').'</h2>';
			echo '<div class="n2go-centeredContent n2go-centeredContent-page">';

			//Get All Systems in each $type
			foreach ($Data->GetSystemsViaTypes($TypeName) as $SystemKeys => $SystemValues) {

				echo '<div class="clearfix ng-systems-'.$SystemValues['id'].'">';
				
				/* Image */
				echo '
				<div class="vc_col-sm-2">
					<img src="'.$SystemValues['imageUrl'].'" alt="'.$SystemValues['name'].'" title="'.$SystemValues['name'].'" />
				</div>';

				/* Name and Editions */
				echo '
				<div class="vc_col-sm-3 ng-sys-information">
					<div class="ng-sys-name" data-ng_sys_name="'.$SystemValues['name'].'">
						'.$SystemValues['name'].'
					</div>
					<div class="ng-sys-editions">';

					// Gel All Editions
					$AllSystems = new IntegrationSystem($SystemValues['items']);
					
					echo '<div class="ng-system-edition">';
						if ( !is_array ($AllSystems->Editions()) ) {
							echo  $AllSystems->Editions() ;
						} else {
							echo '<select class="ng-editions-sec">';
								$i = 1;
								foreach ( $AllSystems->Editions() as $EditionKey => $EditionValue ) {
									if ( $i === 1 ) {
										$FirstEditionNumber = (string)$EditionValue;
									}
									echo '<option value="'.$EditionValue.'">'.$EditionValue.'</option>';
									$i++;
								}
								
							echo '</select>';
						}
					echo '</div>'; // close .ng-system-edition

				echo '
					</div> 
				</div>'; // close .vc_col-sm-3.ng-sys-information

				/* Plugins */
				echo '
				<div class="vc_col-sm-3 ng-sys-plugins">';
					
					// Get All Plugins
					$AllPlugins = new IntegrationPlugin($AllSystems->Plugins());
					echo'<div class="ng-plugin-name">';
						esc_html_e('Plugin Version');
					echo'</div>';
					echo '<div class="ng-system-plugins">';
					if ( empty ( $AllPlugins->AllPlugins() ) ) {
						echo esc_html__('No Plugins Exist');
					} else {
						echo '<select class="ng-plugins-sec">';
							if ( $FirstEditionNumber == 'All Versions' ) { // If set All Version for editions
								foreach ( $AllPlugins->AllPluginsGroup() as $PluginsKey => $PluginsArray ) {
									echo '<optgroup label="'.$PluginsKey.'">';
										foreach ( $PluginsArray as $Plugins ) {
											echo '<option value="'.$Plugins['url'].'">'.substr(chunk_split($Plugins['version'], 1, '.'), 0, -1).'</option>';
										}
									echo '</optgroup>';
								}
							} elseif ( !empty( $FirstEditionNumber ) && $FirstEditionNumber != 'All Versions' ) {
								foreach ( $AllPlugins->EditionPlugins($FirstEditionNumber) as $Plugins ) {
									echo '<option value="'.$Plugins['url'].'">'.substr(chunk_split($Plugins['version'], 1, '.'), 0, -1).'</option>';
								}
							} else {
								foreach ( $AllPlugins->AllPlugins() as $Plugins ) {
									echo '<option value="'.$Plugins['url'].'">'.substr(chunk_split($Plugins['version'], 1, '.'), 0, -1).'</option>';
								}
							}
						echo '</select>';
					}
					echo '</div>'; //Close .ng-system-plugins

				echo'
				</div>'; // Close .vc_col-sm-3.ng-sys-plugins

				/* Links */
				echo '
				<div class="vc_col-sm-4 ng-sys-download">';

					echo '<div class="ng-download-link">';
					if ( empty ( $AllPlugins->AllPlugins() ) ) {
						echo '<div class="ng-download-no-links">'.esc_html__('No Download Exist').'</div>';
					} else {
						echo '<a class="n2go-button ng-download-link" href="">'.esc_html__('Download').'</a>';
					}
					echo '</div>';
					echo '<div class="ng-help-link">';
					if ( !empty ( $SystemValues['helpUrl'] ) ) {
						echo '<a href="'.$SystemValues['helpUrl'].'" title="'.$SystemValues['name'].'">'.esc_html__('Installation Guide').'</a>';
					}
					echo '</div>';
				echo'
				</div>'; // Close .vc_col-sm-4
				
				echo '</div>'; // Close .clearfix
			}
			echo '</div>'; //Close .n2go-centeredContent.n2go-centeredContent-page
		}
	
	echo '</div>'; //Close .ng-download-center
	
}
add_shortcode( 'download_center', 'VcDownloadCenterOutput' );
