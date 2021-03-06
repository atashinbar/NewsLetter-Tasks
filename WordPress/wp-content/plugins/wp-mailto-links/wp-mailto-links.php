<?php defined('ABSPATH') OR die('No direct access.');
/*
Plugin Name: WP Mailto Links - Manage Email Links
Plugin URI: http://www.freelancephp.net/wp-mailto-links-plugin
Description: Manage mailto links on your site and protect email addresses from spambots, set mail icon and more.
Author: Victor Villaverde Laan
Version: 1.3.3
Author URI: http://www.freelancephp.net
License: Dual licensed under the MIT and GPL licenses
Text Domain: wp-mailto-links
Domain Path: /languages
*/

// constants
if (!defined('WP_MAILTO_LINKS_VERSION')) { define('WP_MAILTO_LINKS_VERSION', '1.3.3'); }
if (!defined('WP_MAILTO_LINKS_FILE')) { define('WP_MAILTO_LINKS_FILE', __FILE__); }
if (!defined('WP_MAILTO_LINKS_KEY')) { define('WP_MAILTO_LINKS_KEY', 'WP_Mailto_Links'); }
if (!defined('WP_MAILTO_LINKS_DOMAIN')) { define('WP_MAILTO_LINKS_DOMAIN', 'wp-mailto-links'); }
if (!defined('WP_MAILTO_LINKS_OPTIONS_NAME')) { define('WP_MAILTO_LINKS_OPTIONS_NAME', 'WP_Mailto_Links_options'); }
if (!defined('WP_MAILTO_LINKS_ADMIN_PAGE')) { define('WP_MAILTO_LINKS_ADMIN_PAGE', 'wp-mailto-links-settings'); }

// check plugin compatibility
if (isset($wp_version)
            AND version_compare(preg_replace('/-.*$/', '', $wp_version), '3.4', '>=')
            AND version_compare(phpversion(), '5.2.4', '>=')) {

    // includes
    require_once('includes/class-wpml-admin.php');
    require_once('includes/class-wpml-site.php');
    require_once('includes/template-functions.php');

    // create instance
    $WPML_Site = new WPML_Site;

} else {

    // set error message
    if (!function_exists('wpml_error_notice')):
        function wpml_error_notice() {
            $plugin_title = get_admin_page_title();

            echo '<div class="error">'
                . sprintf(__('<p>Warning - The plugin <strong>%s</strong> requires PHP 5.2.4+ and WP 3.4+.  Please upgrade your PHP and/or WordPress.'
                             . '<br/>Disable the plugin to remove this message.</p>'
                             , WP_MAILTO_LINKS_DOMAIN), $plugin_title)
                . '</div>';
        }

        add_action('admin_notices', 'wpml_error_notice');
    endif;

}

/*?> // ommit closing tag, to prevent unwanted whitespace at the end of the parts generated by the included files */