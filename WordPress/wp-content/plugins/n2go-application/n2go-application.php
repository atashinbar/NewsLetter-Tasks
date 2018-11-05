<?php
/*
Plugin Name: Newsletter2Go Application
Description: Application Plugin
Author: SliceMeNice
Version: 1.0
Author URI: http://www.slicemenice.de
*/

// Include the plugin API libraries
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Newsletter2Go Application plugin
 */
class N2GoApplication {
	const VERSION = '1.0';
	const PLUGIN_FILE = 'n2go-application/n2go-application.php';
	const TEXT_DOMAIN = 'n2go-application';

	protected static $instance;

	/**
	 * Constructor
	 */
	function __construct() {
		// Load all library files used by this plugin
		$libs = glob( dirname( __FILE__ ) . '/lib/*.php' );

		if ( $libs && count( $libs ) ) {
			foreach ( $libs as $lib ) {
				include_once( $lib );
			}
		}

		// Register hooks
		$this->registerHooks();
	}

	/**
	 * Static method to get singleton
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Is this plugin active
	 */
	public static function isActive() {
		return is_plugin_active( self::PLUGIN_FILE );
	}

	/**
	 * On activate plugin
	 *
	 * @return void
	 */
	public function onActivate() {
		$this->registerHelpTopicCategoryTaxonomy();
		$this->registerHelpTopicTagTaxonomy();
		$this->registerPostTypeHelpTopics();
		$this->registerPostTypeFeatures();

		$this->registerPostTypeVideoKnowledge();
		$this->registerPostTypeWhitepaper();
		$this->registerPostTypeInfographics();

		flush_rewrite_rules();
	}

	/**
	 * On deactivate plugin
	 *
	 * @return void
	 */
	public function onDeactivate() {
		// Nothing to do
		flush_rewrite_rules();
	}

	/**
	 * Register wp hooks for action, filter etc
	 *
	 * @return void
	 */
	public function registerHooks() {
		// Set WP timezone setting for using native php date functions
		$tz = get_option( 'timezone_string' );

		if ( $tz ) {
			date_default_timezone_set( $tz );
		}

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.0
		 */


		add_action( 'after_setup_theme', array( $this, 'addCustomImageSizes' ) );

		add_action( 'init', array( $this, 'registerHelpTopicCategoryTaxonomy' ), 11 );
		add_action( 'init', array( $this, 'registerHelpTopicTagTaxonomy' ), 11 );

		add_action( 'init', array( $this, 'registerPostTypeVideoKnowledge' ), 11 );
		add_action( 'init', array( $this, 'registerPostTypeWhitepaper' ), 11 );
		add_action( 'init', array( $this, 'registerPostTypeInfographics' ), 11 );

		add_action( 'init', array( $this, 'registerPostTypeFeatures' ), 11 );
		add_action( 'init', array( $this, 'registerPostTypeHelpTopics' ), 11 );

		add_filter( 'post_link', array( $this, 'setHelpTopicPermalink' ), 1, 3 );
		add_filter( 'post_type_link', array( $this, 'setHelpTopicPermalink' ), 1, 3 );
		add_action( 'init', array( $this, 'addHelpTopicsCategoryRewriteTag' ), 10, 0 );

		add_action( 'after_setup_theme', array( $this, 'addCustomFields' ) );

		add_action( 'admin_menu', array( $this, 'addUpdatePricingSubmenuPage') );
		add_action( 'admin_enqueue_scripts', array( $this, 'addAdminStyles' ), 10 );

		add_filter( 'comment_form_defaults', array( $this, 'addRecaptchaToCommentForm' ) );
	}

	public function load_textdomain() {
		load_plugin_textdomain( self::TEXT_DOMAIN, false, plugin_basename( dirname( __FILE__ ) ) );
	}


	/**
	 * Add custom image sizes, generates additional images sizes when uploading image
	 *
	 * @return void
	 */
	public function addCustomImageSizes() {
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'teaser-landscape', 725, 300, true );
			add_image_size( 'teaser-portrait', 585, 825, true );
			add_image_size( 'blog-post-thumbnail', 36, 36, true );
		}
	}

	public function addRecaptchaToCommentForm($default) {
		$recaptcha_public_key = get_field( 'google_recaptcha_public_key', 'option' );

		$default['comment_notes_after'] .= '<div class="g-recaptcha" data-sitekey="' . $recaptcha_public_key . '"></div>';
		return $default;

	}



	public function registerPostTypeHelpTopics() {
		$lables = array(
			'name'               => _x( 'Help Topics', 'post type general name', self::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Help Topic', 'post type singular name', self::TEXT_DOMAIN ),
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add Help Topic',
			'edit_item'          => 'Edit Help Topic',
			'new_item'           => 'New Help Topic',
			'all_items'          => 'All Help Topics',
			'view_item'          => 'View Help Topic',
			'search_items'       => 'Search Help Topics',
			'not_found'          => 'No Help Topic found',
			'not_found_in_trash' => 'No Help Topic found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Help Topics', 'post type general name', self::TEXT_DOMAIN )
		);

		$helpTopicsPage = get_field( 'help_topics_page', 'option' );
		$helpTopicsSlug = 'faq';

		if ( $helpTopicsPage ) {
			$helpTopicsSlug = $helpTopicsPage->post_name;
		}

		$helpTopicsSlug .= '/%help_topics_category%';

		$args = array(
			'labels'             => $lables,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $helpTopicsSlug, 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes' ),
			'taxonomies'         => array( 'help_topic_category', 'help_topic_tag' )
		);

		register_post_type( 'help_topic', $args );

		// flush the rewrite_rules after registering the post types
		//flush_rewrite_rules();
	}

	public function registerHelpTopicCategoryTaxonomy() {
		if ( !taxonomy_exists( 'help_topic_category' ) ) {
			$helpTopicsPage = get_field( 'help_topics_page', 'option' );
			$helpTopicsSlug = 'faq';

			if ( $helpTopicsPage ) {
				$helpTopicsSlug = $helpTopicsPage->post_name;
			}

			$helpTopicsCategorySlug = $helpTopicsSlug;

			register_taxonomy(
				'help_topic_category',
				array( 'help_topic' ),
				array(
					'hierarchical' => true,
					'label'        => 'Help Topic Categories',
					'public'       => true,
					'show_ui'      => true,
					'query_var'    => true,
					'rewrite'      => array( 'slug' => $helpTopicsCategorySlug, 'with_front' => false )
				)
			);
		}

		// flush the rewrite_rules after registering the post types
		//flush_rewrite_rules();
	}

	public function registerHelpTopicTagTaxonomy() {
		if ( !taxonomy_exists( 'help_topic_tag' ) ) {
			$helpTopicsPage = get_field( 'help_topics_page', 'option' );
			$helpTopicsSlug = 'faq';

			if ( $helpTopicsPage ) {
				$helpTopicsSlug = $helpTopicsPage->post_name;
			}

			$helpTopicsTagSlug = $helpTopicsSlug . '/tag';

			if ( get_field( 'help_topics_tag_slug', 'option' ) ) {
				$helpTopicsTagSlug = $helpTopicsSlug . '/' . get_field( 'help_topics_tag_slug', 'option' );
			}

			register_taxonomy(
				'help_topic_tag',
				array( 'help_topic' ),
				array(
					'hierarchical' => false,
					'label'        => 'Help Topic Tag',
					'public'       => false,
					'show_ui'      => true,
					'query_var'    => true,
					'rewrite'      => array( 'slug' => $helpTopicsTagSlug, 'with_front' => false )
				)
			);
		}

		// flush the rewrite_rules after registering the post types
		//flush_rewrite_rules();
	}

	protected function get_term_top_most_parent( $term_id, $taxonomy ) {
		// start from the current term
		$parent = get_term_by( 'id', $term_id, $taxonomy );
		// climb up the hierarchy until we reach a term with parent = '0'
		while ( $parent->parent != '0' ) {
			$term_id = $parent->parent;

			$parent = get_term_by( 'id', $term_id, $taxonomy );
		}
		return $parent;
	}

	public function setHelpTopicPermalink( $permalink, $post_id, $leavename ) {
		if ( strpos( $permalink, '%help_topics_category%' ) === false )
			return $permalink;

		// Get post
		$post = get_post( $post_id );
		if ( !$post )
			return $permalink;

		// Get taxonomy terms
		$terms = wp_get_object_terms( $post->ID, 'help_topic_category' );

		if ( !is_wp_error( $terms ) && !empty( $terms ) && is_object( $terms[ 0 ] ) ) {
			$rootCategory = $this->get_term_top_most_parent( $terms[ 0 ]->term_id, 'help_topic_category' );
			$taxonomy_slug = $rootCategory->slug;
		} else $taxonomy_slug = 'no-category';

		return str_replace( '%help_topics_category%', $taxonomy_slug, $permalink );
	}

	public function addHelpTopicsCategoryRewriteTag() {
		add_rewrite_tag( '%help_topics_category%', '([^&]+)' );
	}

	public function registerPostTypeFeatures() {
		$lables = array(
			'name'               => _x( 'Features', 'post type general name', self::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Feature', 'post type singular name', self::TEXT_DOMAIN ),
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add Feature',
			'edit_item'          => 'Edit Feature',
			'new_item'           => 'New Feature',
			'all_items'          => 'All Features',
			'view_item'          => 'View Feature',
			'search_items'       => 'Search Features',
			'not_found'          => 'No Feature found',
			'not_found_in_trash' => 'No Feature found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Features', 'post type general name', self::TEXT_DOMAIN )
		);

		$slug = get_field( 'features_slug', 'option' );

		if ( empty( $slug ) ) {
			$slug = 'features';
		}

		$args = array(
			'labels'             => $lables,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug, 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes' ),
			'taxonomies'         => array( '' )
		);

		register_post_type( 'features', $args );
	}

	public function registerPostTypeVideoKnowledge() {
		$lables = array(
			'name'               => _x( 'Video Knowledge', 'post type general name', self::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Video Knowledge', 'post type singular name', self::TEXT_DOMAIN ),
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add Post',
			'edit_item'          => 'Edit Post',
			'new_item'           => 'New Post',
			'all_items'          => 'All Posts',
			'view_item'          => 'View Post',
			'search_items'       => 'Search Posts',
			'not_found'          => 'No Post found',
			'not_found_in_trash' => 'No Post found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Video Knowledge', 'post type general name', self::TEXT_DOMAIN )
		);

		$slug = get_field( 'videoKnowledge_slug', 'option' );

		if ( empty( $slug ) ) {
			$slug = 'video-wissen';
		}

		$args = array(
			'labels'             => $lables,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug, 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'         => array( 'post_tag', 'category' )
		);

		register_post_type( 'video-knowledge', $args );
	}

	public function registerPostTypeWhitepaper() {
		$lables = array(
			'name'               => _x( 'Whitepaper', 'post type general name', self::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Whitepaper', 'post type singular name', self::TEXT_DOMAIN ),
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add Post',
			'edit_item'          => 'Edit Post',
			'new_item'           => 'New Post',
			'all_items'          => 'All Posts',
			'view_item'          => 'View Post',
			'search_items'       => 'Search Posts',
			'not_found'          => 'No Post found',
			'not_found_in_trash' => 'No Post found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Whitepaper', 'post type general name', self::TEXT_DOMAIN ),
		);

		$slug = get_field( 'whitepaper_slug', 'option' );

		if ( empty( $slug ) ) {
			$slug = 'whitepaper';
		}

		$args = array(
			'labels'             => $lables,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug, 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'         => array( 'post_tag', 'category' )
		);

		register_post_type( 'whitepaper', $args );
	}

	public function registerPostTypeInfographics() {
		$lables = array(
			'name'               => _x( 'Infographics', 'post type general name', self::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Infographics', 'post type singular name', self::TEXT_DOMAIN ),
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add Post',
			'edit_item'          => 'Edit Post',
			'new_item'           => 'New Post',
			'all_items'          => 'All Posts',
			'view_item'          => 'View Post',
			'search_items'       => 'Search Posts',
			'not_found'          => 'No Post found',
			'not_found_in_trash' => 'No Post found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Infographics', 'post type general name', self::TEXT_DOMAIN )
		);

		$slug = get_field( 'infographics_slug', 'option' );

		if ( empty( $slug ) ) {
			$slug = 'infografiken';
		}

		$args = array(
			'labels'             => $lables,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug, 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'         => array( 'post_tag', 'category' )
		);

		register_post_type( 'infographics', $args );
	}



	/**
	 * Add Update Prices Sub Menu
	 *
	 * @return void
	 */
	public function addUpdatePricingSubmenuPage() {
		add_submenu_page( 'themes.php', 'N2Go Pricing', 'N2Go Pricing', 'manage_options', 'n2go-pricing', array( $this, 'addUpdatePricing'));
	}

	/**
	 * Add Admin Styles
	 *
	 * @return void
	 */
	public function addAdminStyles() {
		wp_enqueue_style( 'AdminStyles', get_stylesheet_directory_uri() . '/stylesheets/style_backend.css', false );
	}

	/**
	 * Add Update Pricing
	 *
	 * @return void
	 */
	public static function addUpdatePricing() {

		$username = get_site_option('n2go_update_pricing_username');
		$password = get_site_option('n2go_update_pricing_password');
		$auth_token = get_site_option('n2go_update_pricing_auth_token');

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have the permissions to access this page.' ) );
		}

		echo '<h1 class="heading">N2Go Pricing</h1>' . PHP_EOL;
	    echo '<hr>' . PHP_EOL;
	    echo '<p>Update pricing via API and save to local database.' . PHP_EOL;
	    if(isset($_POST['submit'])) {
	        try {

	            $languages = array();
    			if (isset($_POST['lang_de'])) {
    				$languages[] = 'DE';
    			}
    			if (isset($_POST['lang_at'])) {
    				$languages[] = 'AT';
    			}
    			if (isset($_POST['lang_ch'])) {
    				$languages[] = 'CH';
    			}
    			if (isset($_POST['lang_gb'])) {
    				$languages[] = 'GB';
    			}
    			if (isset($_POST['lang_ca'])) {
    				$languages[] = 'CA';
    			}
    			if (isset($_POST['lang_es'])) {
    				$languages[] = 'ES';
    			}
    			if (isset($_POST['lang_fr'])) {
    				$languages[] = 'FR';
    			}
    			if (isset($_POST['lang_nl'])) {
    				$languages[] = 'NL';
    			}
    			if (isset($_POST['lang_us'])) {
    				$languages[] = 'US';
    			}
    			if (isset($_POST['lang_it'])) {
    				$languages[] = 'IT';
    			}
    			if (isset($_POST['lang_pl'])) {
    				$languages[] = 'PL';
    			}
    			if (isset($_POST['lang_br'])) {
    				$languages[] = 'BR';
    			}
    			if(!empty($languages)) {
		            if(isset($_POST['username']) && !empty($_POST['username'])) {
		            	if(isset($_POST['password']) && !empty($_POST['password'])) {
		            		if(isset($_POST['auth_token']) && !empty($_POST['auth_token'])) {

		            			$username = trim($_POST['username']);
		            			$password = trim($_POST['password']);
		            			$auth_token = trim($_POST['auth_token']);

		            			update_site_option('n2go_update_pricing_username', $username);
		            			update_site_option('n2go_update_pricing_password', $password);
		            			update_site_option('n2go_update_pricing_auth_token', $auth_token);

								$quantities = array(500, 1000, 5000, 10000, 25000, 50000, 100000, 500000, 1000000);

								$types = array('mail', 'subscription');

								foreach ($types as $type) {
									foreach ($quantities as $size) {
										foreach ($languages as $language) {

											//Get Access Token
											$url = "https://api.newsletter2go.com/oauth/v2/token";
											$fields = array(
											            'username'=>urlencode($username),
											            'grant_type'=>urlencode('https://nl2go.com/jwt'),
											            'password'=>urlencode($password)
											        );

											$fields_string = '';
											foreach($fields as $key=>$value) {
												$fields_string .= $key.'='.$value.'&';
											}
											$fields_string = rtrim($fields_string,'&');

											$ch = curl_init();

											curl_setopt($ch,CURLOPT_URL,$url);
											curl_setopt($ch,CURLOPT_POST,count($fields));
											curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
											curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
											curl_setopt($ch,CURLOPT_HTTPHEADER, array(
											        'Authorization: ' . "Basic " . base64_encode($auth_token)
											    ));

											$result = curl_exec($ch);

											$json_obj = json_decode($result);

											$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
											if($httpCode != 400) {
												$accessToken = $json_obj->access_token;

												$url = "https://api.newsletter2go.com/payment/price/" . $type;
												$fields = array(
												            'size'=>urlencode($size),
												            'country'=>urlencode($language)
												        );

												$fields_json = json_encode($fields);

												$ch = curl_init();

												curl_setopt($ch,CURLOPT_URL,$url);
												curl_setopt($ch,CURLOPT_POST,count($fields));
												curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_json);
												curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch,CURLOPT_HTTPHEADER, array(
														'Content-Type: application/json',
														'Content-Length: ' . strlen($fields_json),
												        'Authorization: ' . "Bearer " . $accessToken
												    ));

												$result = curl_exec($ch);

												$json_obj = json_decode($result);

												$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
												if($httpCode != 400) {


													$quantity = $json_obj->value[0]->quantity;
													$amount = $json_obj->value[0]->amount;
													$cpm = $json_obj->value[0]->cpm;

													//e.g. n2go_update_pricing_DE_mail_2000_amount
													update_site_option('n2go_update_pricing_' . $language . '_' . $type . '_' . $quantity . '_amount', $amount);
													update_site_option('n2go_update_pricing_' . $language . '_' . $type . '_' . $quantity . '_cpm', $cpm);


												} else {
									                throw new RuntimeException($json_obj->error_description);
									            }
											} else {
								                throw new RuntimeException($json_obj->error_description);
								            }
								        }
								    }
								}

				                echo '<div class="row">
				                    <div class="col col-12">
				                        <div class="notification-container notification-success">Success: <strong>Data successfully updated!</strong></div>
				                    </div>
				                  </div>';

				            } else {
				                throw new RuntimeException('Empty Auth-Token');
				            }
			            } else {
			                throw new RuntimeException('Empty password');
			            }
		        	} else {
		                throw new RuntimeException('Empty username');
		            }
	        	} else {
	                throw new RuntimeException('No languages selected');
	            }
	        } catch(RuntimeException $e) {
	            echo '<div class="row">
	                    <div class="col col-12">
	                        <div class="notification-container notification-error">Error: <strong>' . $e->getMessage() . '</strong></div>
	                    </div>
	                  </div>';
	        }
	    }

	    echo '<form class="n2go-pricing-container" method="post" action="' . get_admin_url() . 'themes.php?page=n2go-pricing">
	            <div class="row">
	                <div class="col col-2">
	                    <h2>API-Authentification</h2>
	                </div>
	                <div class="col col-10">
	                </div>
	            </div>
	            <div class="row">
	                <div class="col col-2">
	                    <label for="username">Username / E-Mail:</label>
	                </div>
	                <div class="col col-10">
	                    <input type="text" id="username" name="username"' . (isset($username) ? ' value="' . $username . '"' : '') . '>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col col-2">
	                    <label for="password">Password:</label>
	                </div>
	                <div class="col col-10">
	                    <input type="password" id="password" name="password"' . (isset($password) ? ' value="' . $password . '"' : '') . '>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col col-2">
	                    <label for="auth_token">Auth-Token:</label>
	                </div>
	                <div class="col col-10">
	                    <input type="text" id="auth_token" name="auth_token"' . (isset($auth_token) ? ' value="' . $auth_token . '"' : '') . '>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col col-2">
	                    <label for="lang_de">Languages to Update:</label>
	                </div>
	                <div class="col col-10">
	                   <input name="lang_de" id="lang_de" type="checkbox"/>
	                   DE</br>
	                   <input name="lang_at" id="lang_at" type="checkbox"/>
	                   AT</br>
	                   <input name="lang_ch" id="lang_ch" type="checkbox"/>
	                   CH</br>
	                   <input name="lang_gb" id="lang_gb" type="checkbox"/>
	                   GB</br>
	                   <input name="lang_ca" id="lang_ca" type="checkbox"/>
	                   CA</br>
	                   <input name="lang_es" id="lang_es" type="checkbox"/>
	                   ES</br>
	                   <input name="lang_fr" id="lang_fr" type="checkbox"/>
	                   FR</br>
	                   <input name="lang_nl" id="lang_nl" type="checkbox"/>
	                   NL</br>
	                   <input name="lang_us" id="lang_us" type="checkbox"/>
	                   US</br>
	                   <input name="lang_it" id="lang_it" type="checkbox"/>
	                   IT</br>
	                   <input name="lang_pl" id="lang_pl" type="checkbox"/>
	                   PL</br>
	                   <input name="lang_br" id="lang_br" type="checkbox"/>
	                   BR</br>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col col-10 right">
	                    <input class="left" type="submit" name="submit" value="Update">
	                </div>
	            </div>
	          </form>' . PHP_EOL;


	}


	/**
	 * Add custom fields
	 *
	 * @return void
	 */
	public static function addCustomFields() {
		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_help-topic-fields',
				'title'      => 'Help Topic Fields',
				'fields'     => array(
					array(
						'key'          => 'field_5392e99302052',
						'label'        => 'Images',
						'name'         => 'help_topic_images',
						'type'         => 'gallery',
						'preview_size' => 'full',
						'library'      => 'all',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'help_topic',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_theme-configuration',
				'title'      => 'Theme Configuration',
				'fields'     => array(
					array(
						'key'           => 'field_537f57dd51ota',
						'label'         => 'Video Knowledge Slug',
						'name'          => 'videoKnowledge_slug',
						'type'          => 'text',
						'default_value' => 'video-wissen',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_537f57dd51cta',
						'label'         => 'Whitepaper Slug',
						'name'          => 'whitepaper_slug',
						'type'          => 'text',
						'default_value' => 'whitepaper',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_537f57dd51ita',
						'label'         => 'Infographics Slug',
						'name'          => 'infographics_slug',
						'type'          => 'text',
						'default_value' => 'infographics',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_537f57dd51btg',
						'label'         => 'Features Slug',
						'name'          => 'features_slug',
						'type'          => 'text',
						'default_value' => 'features',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'        => 'field_537f57dd51bcb',
						'label'      => 'Hilfeseite',
						'name'       => 'help_topics_page',
						'type'       => 'post_object',
						'post_type'  => array(
							0 => 'page',
						),
						'taxonomy'   => array(
							0 => 'all',
						),
						'allow_null' => 0,
						'multiple'   => 0,
					),
					array(
						'key'           => 'field_53e06b7e7ded6',
						'label'         => 'Footer Label – Column 1',
						'name'          => 'footer_label_column_1',
						'type'          => 'text',
						'default_value' => 'Über uns',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_53e06bd47ded7',
						'label'         => 'Footer Label – Column 2',
						'name'          => 'footer_label_column_2',
						'type'          => 'text',
						'default_value' => 'Kooperationen',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_53e06bea7ded8',
						'label'         => 'Footer Label – Column 3',
						'name'          => 'footer_label_column_3',
						'type'          => 'text',
						'default_value' => 'Support',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_53e06bf97ded9',
						'label'         => 'Footer Label – Column 4',
						'name'          => 'footer_label_column_4',
						'type'          => 'text',
						'default_value' => 'Rechtliches',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_53e06bf97dab9',
						'label'         => 'Footer Label – Column 5',
						'name'          => 'footer_label_column_5',
						'type'          => 'text',
						'default_value' => 'Zertifizierungen',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_3fae4df7fd4d68c824b46010ab99090463b7145a',
						'label'         => '"Mehr Hilfebeiträge..." - Text',
						'name'          => 'mehr_hilfebeitraege_text',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_1accba38f7fed898b030f429c04ae5367b48fdfb',
						'label'         => '"Mehr Hilfebeiträge..." - Link',
						'name'          => 'mehr_hilfebeitraege_link',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_6ee5cfbc48e94be0958926f6edd96c65352fa3d6',
						'label'         => '"Mehr Wissensbeiträge..." - Text',
						'name'          => 'mehr_wissensbeitraege_text',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_9049b59b578e9feb416354639789519a7813f27a',
						'label'         => '"Mehr Wissensbeiträge..." - Link',
						'name'          => 'mehr_wissensbeitraege_link',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_6ee5cfbc48e94be0958926f6edd96c65352fa3d61',
						'label'         => 'Google ReCaptcha Public-Key',
						'name'          => 'google_recaptcha_public_key',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_9049b59b578e9feb416354639789519a7813f27a1',
						'label'         => 'Google ReCaptcha Private-Key',
						'name'          => 'google_recaptcha_private_key',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-theme-configuration',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}


		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_header-settings',
				'title'      => 'Header Settings',
				'fields'     => array(
					array(
						'key'           => 'field_5408f45bc3883',
						'label'         => 'Login Button Label',
						'name'          => 'header_login_button_label',
						'type'          => 'text',
						'default_value' => 'Login',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5408f4c5c3884',
						'label'         => 'Login Button URL',
						'name'          => 'header_login_button_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5408f54cc3885',
						'label'         => 'Trial Button Label',
						'name'          => 'header_trial_button_label',
						'type'          => 'text',
						'default_value' => 'Kostenlos testen',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5408f576c3886',
						'label'         => 'Trial Button URL',
						'name'          => 'header_trial_button_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'          => 'field_541f911fcf49e',
						'label'        => 'Header Ekomi Image',
						'name'         => 'header_ekomi_image',
						'type'         => 'image',
						'save_format'  => 'url',
						'preview_size' => 'full',
						'library'      => 'all',
					),
					array(
						'key'           => 'field_541f9159cf49f',
						'label'         => 'Header Ekomi Text',
						'name'          => 'header_ekomi_text',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f9259go49f',
						'label'         => 'Header Ekomi URL',
						'name'          => 'header_ekomi_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f9168cf4a0',
						'label'         => 'Header Phone Text',
						'name'          => 'header_phone_text',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f4968cd4a0',
						'label'         => 'Header Phone URL',
						'name'          => 'header_phone_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-header',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_footer',
				'title'      => 'Footer',
				'fields'     => array(
					array(
						'key'           => 'field_540b5b1b14caa',
						'label'         => 'Facebook URL',
						'name'          => 'footer_facebook_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5b4114cab',
						'label'         => 'Twitter URL',
						'name'          => 'footer_twitter_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5b5414cac',
						'label'         => 'Google Plus URL',
						'name'          => 'footer_google_plus_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5b6c14cad',
						'label'         => 'YouTube URL',
						'name'          => 'footer_youtube_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5b6c14cae',
						'label'         => 'LinkedIn URL',
						'name'          => 'footer_linkedin_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array (
						'key' => 'field_5774d6137054e',
						'label' => 'Show Xing Link?',
						'name' => 'footer_show_xing_link',
						'type' => 'true_false',
						'message' => 'Show Xing Link',
						'default_value' => 0,
					),
					array (
						'key' => 'field_5774d63a7054f',
						'label' => 'Xing URL',
						'name' => 'footer_xing_url',
						'type' => 'text',
						'conditional_logic' => array (
							'status' => 1,
							'rules' => array (
								array (
									'field' => 'field_5774d6137054e',
									'operator' => '==',
									'value' => '1',
								),
							),
							'allorany' => 'all',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'none',
						'maxlength' => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-footer',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_form-settings',
				'title'      => 'Form Settings',
				'fields'     => array(
					array(
						'key'           => 'field_540b5e8af209d',
						'label'         => 'Registration Form Headline',
						'name'          => 'registration_form_headline',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541a4140f37d8',
						'label'         => 'Registration Form Target URL',
						'name'          => 'registration_form_action',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5ed7f209e',
						'label'         => 'Label for E-Mail Input',
						'name'          => 'registration_form_email_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5f0cf209f',
						'label'         => 'Label for Password Input',
						'name'          => 'registration_form_password_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5f2bf20a0',
						'label'         => 'Label for Password Confirmation Input',
						'name'          => 'registration_form_password_confirmation_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5f58f20a1',
						'label'         => 'Label for Terms and Conditions Checkbox',
						'name'          => 'registration_form_terms_checkbox_label',
						'type'          => 'wysiwyg',
						'default_value' => '',
						'toolbar'       => 'full',
						'media_upload'  => 'yes',
					),
					array(
						'key'           => 'field_540b5f9bf20a2',
						'label'         => 'Label for Submit Button',
						'name'          => 'registration_form_submit_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540b5fd8f20a3',
						'label'         => 'Additional Text',
						'name'          => 'registration_form_additional_text',
						'type'          => 'wysiwyg',
						'default_value' => '',
						'toolbar'       => 'full',
						'media_upload'  => 'yes',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-registration-form',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'default',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
			register_field_group( array(
				'id'         => 'acf_error-messages',
				'title'      => 'Error Messages',
				'fields'     => array(
					array(
						'key'           => 'field_540be7eefb934',
						'label'         => 'Server Error',
						'name'          => 'registration_form_server_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be828fb935',
						'label'         => 'Invalid Email Address',
						'name'          => 'registration_form_invalid_email_address_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be864fb936',
						'label'         => 'Email Already Exists',
						'name'          => 'registration_form_email_already_exists_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be948fb937',
						'label'         => 'Force Company Email Error – Part 1',
						'name'          => 'registration_form_force_company_email_error_1',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be96dfb938',
						'label'         => 'Force Company Email Error – Part 2',
						'name'          => 'registration_form_force_company_email_error_2',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be984fb939',
						'label'         => 'Insecure Password Error',
						'name'          => 'registration_form_insecure_password_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be99ffb93a',
						'label'         => 'Password Mismatch Error',
						'name'          => 'registration_form_password_mismatch_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_540be9c2fb93b',
						'label'         => 'Must Accept our Terms and Conditions Error',
						'name'          => 'registration_form_terms_and_conditions_error',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-registration-form',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'default',
					'hide_on_screen' => array(),
				),
				'menu_order' => 1,
			) );

		}


		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_all-features-shortcode-settings',
				'title'      => 'All Features Shortcode Settings',
				'fields'     => array(
					array(
						'key'           => 'field_541a46b33ec39',
						'label'         => 'Headline',
						'name'          => 'all_features_headline',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541a46c83ec3a',
						'label'         => 'Column Height',
						'name'          => 'all_features_column_height',
						'type'          => 'number',
						'default_value' => 690,
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => 'px',
						'min'           => '',
						'max'           => '',
						'step'          => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-all-features-shortcode',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_help-topic-search',
				'title'      => 'Help Topic Search',
				'fields'     => array(
					array(
						'key'           => 'field_541f8b26957d1',
						'label'         => 'Related Questions Label',
						'name'          => 'help_topics_related_questions_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f88008b7d3',
						'label'         => 'Search Input Placeholder',
						'name'          => 'help_topics_search_input_placeholder',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f88168b7d4',
						'label'         => 'Search Results Page Headline',
						'name'          => 'help_topics_search_results_page_headline',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f88468b7d5',
						'label'         => 'Search Results Search Term Label',
						'name'          => 'help_topics_search_results_search_term_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_541f88928b7d6',
						'label'         => 'No Search Results Message',
						'name'          => 'help_topics_no_search_results_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'        => 'field_541f88ad8b7d7',
						'label'      => 'Contact Form',
						'name'       => 'help_topics_search_contact_form',
						'type'       => 'post_object',
						'post_type'  => array(
							0 => 'wpcf7_contact_form',
						),
						'taxonomy'   => array(
							0 => 'all',
						),
						'allow_null' => 0,
						'multiple'   => 0,
					),
					array(
						'key'           => 'field_54fc849499ccc',
						'label'         => 'Back to Overview Page',
						'name'          => 'help_topics_back_to_overview_page_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54fc84c099ccd',
						'label'         => 'Back to Parent Page',
						'name'          => 'help_topics_back_to_parent_page_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-help-topics',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_login-form-settings',
				'title'      => 'Login Form Settings',
				'fields'     => array(
					array(
						'key'           => 'field_5429618007099',
						'label'         => 'Headline',
						'name'          => 'login_form_headline',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429619f0709a',
						'label'         => 'Target URL',
						'name'          => 'login_form_action',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542961e30709b',
						'label'         => 'Label for E-Mail Input',
						'name'          => 'login_form_email_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429621a0709c',
						'label'         => 'Label for Password Input',
						'name'          => 'login_form_password_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542962300709d',
						'label'         => 'Label for Perma-Login Checkbox',
						'name'          => 'login_form_permalogin_checkbox_label',
						'type'          => 'wysiwyg',
						'default_value' => '',
						'toolbar'       => 'full',
						'media_upload'  => 'yes',
					),
					array(
						'key'           => 'field_542962770709e',
						'label'         => 'Label for Submit Button',
						'name'          => 'login_form_submit_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542962970709f',
						'label'         => 'Label for Sign-Up Button',
						'name'          => 'login_form_signup_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542923470709f',
						'label'         => 'Sign-Up Button URL',
						'name'          => 'login_form_signup_button_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54296315070a0',
						'label'         => 'Label for Forgot Password Button',
						'name'          => 'login_form_forgot_password_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5245345c76a5c',
						'label'         => 'Login Failed Message',
						'name'          => 'login_form_login_failed_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429729276a53',
						'label'         => 'Password Form Headline',
						'name'          => 'password_form_headline',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542972c776a54',
						'label'         => 'Password Form Description',
						'name'          => 'password_form_description',
						'type'          => 'wysiwyg',
						'default_value' => '',
						'toolbar'       => 'full',
						'media_upload'  => 'yes',
					),
					array(
						'key'           => 'field_5429732b76a55',
						'label'         => 'Label for E-Mail Input of Password Form',
						'name'          => 'password_form_email_input_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429735f76a56',
						'label'         => 'Label for Request New Password Button of Password Form',
						'name'          => 'password_form_request_new_password_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429739876a57',
						'label'         => 'Label for Cancel Button of Password Form',
						'name'          => 'password_form_cancel_button_label',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_542973db76a58',
						'label'         => 'Password Form Target URL',
						'name'          => 'password_form_action_url',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429740676a59',
						'label'         => 'Password Form Success Message',
						'name'          => 'password_form_success_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429741a76a5a',
						'label'         => 'Password Form Request Limit Message',
						'name'          => 'password_form_request_limit_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429744d76a5b',
						'label'         => 'Password Form Error Message',
						'name'          => 'password_form_error_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_5429745c76a5c',
						'label'         => 'Password Form Email Required Message',
						'name'          => 'password_form_email_required_message',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-login-form',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'default',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_blog-settings',
				'title'      => 'Blog Settings',
				'fields'     => array(
					array(
						'key'           => 'field_54eab938bd95a',
						'label'         => 'Blog Title',
						'name'          => 'n2go_blog_title',
						'type'          => 'textarea',
						'default_value' => '',
						'placeholder'   => '',
						'maxlength'     => '',
						'rows'          => '',
						'formatting'    => 'br',
					),
					array(
						'key'           => 'field_54eab9adbd95b',
						'label'         => 'Search Input Placeholder',
						'name'          => 'n2go_blog_searchInputPlaceholder',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eab9ecbd95d',
						'label'         => 'Published on',
						'name'          => 'n2go_blog_publishedOn',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eab9ecbd95a',
						'label'         => 'Single Response',
						'name'          => 'n2go_blogTeaser_singleResponse',
						'type'          => 'text',
						'default_value' => '%s Comment',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eab9ecbd95b',
						'label'         => 'Multiple Responses',
						'name'          => 'n2go_blogTeaser_multipleResponses',
						'type'          => 'text',
						'default_value' => '%s Comments',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eab9ecbd95c',
						'label'         => 'Read More',
						'name'          => 'n2go_blog_readMore',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eab9febd95d',
						'label'         => 'Related Articles',
						'name'          => 'n2go_blog_relatedArticles',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eaba66bd95e',
						'label'         => 'Written by',
						'name'          => 'n2go_blog_writtenBy',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eababdbd95f',
						'label'         => 'Registration Box Title',
						'name'          => 'n2go_blog_registrationBoxTitle',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eabazuzd95f',
						'label'         => 'Search – Results found',
						'name'          => 'n2go_blog_searchResultsFound',
						'type'          => 'text',
						'default_value' => 'Wir haben %s Treffer zum Thema "%s" gefunden:',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_54eabapokd95f',
						'label'         => 'Search – No results found',
						'name'          => 'n2go_blog_searchNoResultsFound',
						'type'          => 'text',
						'default_value' => 'Leider konnten wir keine Treffer zum Thema "%s" finden.',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-blog',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_knowledge-base',
				'title'      => 'Knowledge Base',
				'fields'     => array(
					array(
						'key'          => 'field_55fec57cd8d2f',
						'label'        => 'Subnavigation',
						'name'         => 'n2go_kb_subnavigation',
						'type'         => 'nav_menu',
						'instructions' => 'Select the subnavigation menu to display in the knowledge base.',
						'save_format'  => 'object',
						'container'    => 0,
						'allow_null'   => 1,
					),
					array(
						'key'           => 'field_55feca77c9c34',
						'label'         => 'Search Input Placeholder',
						'name'          => 'n2go_kb_searchInputPlaceholder',
						'type'          => 'text',
						'instructions'  => 'Define a placeholder for the search input field.',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'none',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-knowledge-base',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_social-sharing',
				'title'      => 'Social Sharing',
				'fields'     => array(
					array(
						'key'           => 'field_55ff28ab11ea4',
						'label'         => 'Facebook Button (Singular)',
						'name'          => 'n2go_facebookButton_singular',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff28cd11ea5',
						'label'         => 'Facebook Button (Plural)',
						'name'          => 'n2go_facebookButton_plural',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff28ec11ea6',
						'label'         => 'Twitter Button (Singular)',
						'name'          => 'n2go_twitterButton_singular',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff290a11ea7',
						'label'         => 'Twitter Button (Plural)',
						'name'          => 'n2go_twitterButton_plural',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff293211ea8',
						'label'         => 'Google Plus Button (Singular)',
						'name'          => 'n2go_googleplusButton_singular',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff294b11ea9',
						'label'         => 'Google Plus Button (Plural)',
						'name'          => 'n2go_googleplusButton_plural',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
					array(
						'key'           => 'field_55ff296311eaa',
						'label'         => 'Share via Mail',
						'name'          => 'n2go_shareButton_mail',
						'type'          => 'text',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-social-sharing',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

		self::addCustomFields_SEO();
	}

	protected static function addCustomFields_SEO() {
		if ( function_exists( "register_field_group" ) ) {
			register_field_group( array(
				'id'         => 'acf_seo',
				'title'      => 'SEO',
				'fields'     => array(
					array(
						'key'           => 'field_5672910c02906',
						'label'         => 'Canonical Base URL',
						'name'          => 'n2go_canonicalBaseUrl',
						'type'          => 'text',
						'instructions'  => 'Enter a canonical base URL without protocol and slashes, e.g. www.newsletter2go.com',
						'default_value' => '',
						'placeholder'   => '',
						'prepend'       => '',
						'append'        => '',
						'formatting'    => 'html',
						'maxlength'     => '',
					),
				),
				'location'   => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-options-theme-configuration',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options'    => array(
					'position'       => 'normal',
					'layout'         => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0,
			) );
		}

	}
}

/**
 * Create singleton of this plugin
 */
N2GoApplication::getInstance();

/**
 * Register activation and deactivation hooks
 */
register_activation_hook( __FILE__, array( N2GoApplication::getInstance(), 'onActivate' ) );
register_deactivation_hook( __FILE__, array( N2GoApplication::getInstance(), 'onDeactivate' ) );
