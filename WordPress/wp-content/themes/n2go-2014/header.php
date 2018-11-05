<!DOCTYPE html>
<html class="n2go-html n2go-css" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml" <?php language_attributes(); ?>>
	<head>
		<?php do_action( 'wpe_gce_head' ); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<?php // discourage search engines from indexing paged pages ?>
		<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		if ( $paged > 1 ) : ?>
			<meta name="robots" content="noindex,follow">
		<?php endif; ?>

		<title>Newsletter Software und Email Marketing Software von Newsletter2Go</title>

		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/favicons/manifest.json">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/favicons/safari-pinned-tab.svg" color="#00baff">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/favicons/mstile-144x144.png">
		<meta name="theme-color" content="#00baff">


		<meta property="og:street-address" content="Nürnberger Straße 8"/>
		<meta property="og:postal-code" content="10787"/>
		<meta property="og:city" content="Berlin"/>
		<meta property="og:country-name" content="Deutschland"/>
		<meta property="og:email_address" content="support@newsletter2go.de"/>
		<meta property="og:phone_number" content="+493031199510"/>
		<meta property="og:fax_number" content="+4930590083384"/>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

		<?php

		wp_register_style( 'n2g-newsletter2go', get_template_directory_uri() . '/stylesheets/style.css', array() );
		wp_enqueue_style( 'n2g-newsletter2go' );

		wp_register_style( 'jgrowl', get_template_directory_uri() . '/scripts/jquery/jgrowl/jquery.jgrowl.css' );
		wp_enqueue_style( 'jgrowl' );
		echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.3.2/css/simple-line-icons.css">';
		?>

		<?php

		function smn_register_script( $id, $dependencies ) {
			wp_register_script( $id, get_template_directory_uri() . '/scripts/' . $id . '.js', $dependencies, false, false );
		}

		smn_register_script( 'smn.checkboxReplacement', array( 'jquery-ui-widget' ) );
		smn_register_script( 'n2go.checkbox', array( 'smn.checkboxReplacement', 'n2go-svg-sprite-service' ) );

		smn_register_script( 'n2go.newsletterSignupForm', array( 'jquery' ) );

		smn_register_script( 'vendor/jquery.dotdotdot', array( 'jquery' ) );

		wp_register_script( 'jgrowl', get_template_directory_uri() . '/scripts/jquery/jgrowl/jquery.jgrowl_min_de.js', array( 'jquery' ), false, false );
		wp_register_script( 'apprise', get_template_directory_uri() . '/scripts/jquery/apprise/apprise-1.5.full.min.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-login', get_template_directory_uri() . '/scripts/n2go.login.js', array( 'apprise' ), false, false );
		wp_register_script( 'n2go-registration', get_template_directory_uri() . '/scripts/n2go.registration.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-sticky-footer', get_template_directory_uri() . '/scripts/n2go.sticky-footer.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-form-select', get_template_directory_uri() . '/scripts/n2go.form.select.js', array( 'jquery-ui-widget' ), false, false );
		wp_register_script( 'n2go-smooth-scroll', get_template_directory_uri() . '/scripts/n2go.smooth-scroll.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-main', get_template_directory_uri() . '/scripts/n2go.main.js', array( 'n2go-form-select' ), false, false );

		wp_register_script( 'modernizr', get_template_directory_uri() . '/scripts/modernizr.js', array(), false, false );
		wp_register_script( 'swipe-service', get_template_directory_uri() . '/scripts/smn.swipeService.js', array(), false, false );

		wp_register_script( 'GSAP-TweenLite', get_template_directory_uri() . '/scripts/gsap/minified/TweenLite.min.js', array(), false, true );
		wp_register_script( 'GSAP-TimelineLite', get_template_directory_uri() . '/scripts/gsap/minified/TimelineLite.min.js', array( 'GSAP-TweenLite' ), false, true );
		wp_register_script( 'GSAP-EasePack', get_template_directory_uri() . '/scripts/gsap/minified/easing/EasePack.min.js', array( 'GSAP-TweenLite' ), false, true );
		wp_register_script( 'GSAP-CSSPlugin', get_template_directory_uri() . '/scripts/gsap/minified/plugins/CSSPlugin.min.js', array( 'GSAP-TweenLite' ), false, true );

		wp_register_script( 'off-canvas-service', get_template_directory_uri() . '/scripts/offCanvasService.js', array( 'GSAP-TimelineLite', 'GSAP-EasePack', 'GSAP-CSSPlugin' ), false, false );
		wp_register_script( 'n2go-off-canvas', get_template_directory_uri() . '/scripts/n2go.off-canvas.js', array( 'off-canvas-service' ), false, false );

		wp_register_script( 'n2go-page-tabs-frontend', get_template_directory_uri() . '/visual-composer/scripts/n2go-page-tabs.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-page-tab-anchors-frontend', get_template_directory_uri() . '/visual-composer/scripts/n2go-page-tab-anchors.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-slider-frontend', get_template_directory_uri() . '/visual-composer/scripts/n2go-slider.js', array( 'jquery' ), false, false );
		wp_register_script( 'n2go-slider-touch', get_template_directory_uri() . '/visual-composer/scripts/n2go-slider-touch.js', array( 'jquery', 'swipe-service', 'GSAP-TimelineLite', 'GSAP-EasePack', 'GSAP-CSSPlugin' ), false, false );

		wp_register_script( 'n2go-blog-sidebar', get_template_directory_uri() . '/scripts/n2go.blog-sidebar.js', array( 'jquery' ), false, true );
		wp_register_script( 'n2go-blog-teaser', get_template_directory_uri() . '/scripts/n2go.teaser.js', array( 'jquery', 'vendor/jquery.dotdotdot' ), false, true );
		wp_register_script( 'n2go-latest-post-grid', get_template_directory_uri() . '/scripts/n2go.latestPostGrid.js', array( 'jquery' ), false, true );

		wp_register_script( 'smn-tooltip-service', get_template_directory_uri() . '/scripts/smn.tooltip-service.js', array(), false, true );
		wp_register_script( 'n2go-tabs-flyout', get_template_directory_uri() . '/scripts/n2go.tabs-flyout.js', array( 'smn-tooltip-service', 'GSAP-TimelineLite', 'GSAP-EasePack', 'GSAP-CSSPlugin' ), false, true );

		wp_register_script( 'common-ready', get_template_directory_uri() . '/scripts/common.ready.js', array(), false, false );
		wp_register_script( 'n2go-svg-sprite-service', get_template_directory_uri() . '/scripts/n2go.svg-sprite-service.js', array(), false, false );
		wp_register_script( 'n2go-svg-sprite', get_template_directory_uri() . '/scripts/n2go.svg-sprite.js', array( 'common-ready', 'n2go-svg-sprite-service' ), false, false );

		wp_register_script( 'sourcebuster-js', get_template_directory_uri() . '/scripts/sourcebuster.min.js', array(), false, false );
		wp_register_script( 'sourcebuster-init', get_template_directory_uri() . '/scripts/sourcebuster_init.js', array(), false, false );

		wp_enqueue_script( 'n2go.checkbox' );
		wp_enqueue_script( 'n2go.newsletterSignupForm' );

		wp_enqueue_script( 'jgrowl' );
		wp_enqueue_script( 'modernizr' );
		wp_enqueue_script( 'n2go-login' );
		wp_enqueue_script( 'n2go-registration' );
		wp_enqueue_script( 'n2go-sticky-footer' );
		wp_enqueue_script( 'n2go-form-select' );
		wp_enqueue_script( 'n2go-smooth-scroll' );
		wp_enqueue_script( 'n2go-main' );
		wp_enqueue_script( 'n2go-page-tabs-frontend' );
		wp_enqueue_script( 'n2go-page-tab-anchors-frontend' );
		wp_enqueue_script( 'n2go-slider-frontend' );
		wp_enqueue_script( 'n2go-slider-touch' );

		wp_enqueue_script( 'n2go-off-canvas' );
		wp_enqueue_script( 'n2go-blog-sidebar' );
		wp_enqueue_script( 'n2go-blog-teaser' );
		wp_enqueue_script( 'n2go-latest-post-grid' );
		wp_enqueue_script( 'n2go-tabs-flyout' );

		wp_enqueue_script( 'n2go-svg-sprite' );

		wp_enqueue_script( 'sourcebuster-js' );
		wp_enqueue_script( 'sourcebuster-init' );


		wp_localize_script( 'n2go-registration', 'WordPress', array(
			'templateDirectory'    => get_template_directory_uri(),
			'siteUrl'              => get_bloginfo( 'url' ),
			'ajaxUrl'              => admin_url( 'admin-ajax.php' ),
			'NewsletterSignupForm' => json_encode( array(
				'messages' => array(
					'ServerError'            => _x( 'An error occurred.', 'newsletter form - server error', 'n2go-theme' ),
					'AlreadySubscribedError' => _x( 'You have already subscribed to our newsletter.', 'registration form - already subscribed error', 'n2go-theme' ),
					'EmailError'             => _x( 'Please enter a valid email address.', 'registration form - invalid email / missing data error', 'n2go-theme' ),
					'SuccessMessage'         => _x( 'Thanks for subscribing to our newsletter!', 'registration form - success message', 'n2go-theme' ),
				)
			) ),
			'RegistrationForm'     => json_encode( array(
				'messages' => array(
					'ServerError'                     => _x( 'An error occurred.', 'registration form - server error', 'n2go-theme' ),
					'InvalidEmailAddress'             => _x( 'Email address is invalid.', 'registration form - invalid email address', 'n2go-theme' ),
					'EmailAlreadyExists'              => _x( 'Email address already exists.', 'registration form - email already exists', 'n2go-theme' ),
					'ForceCompanyEmail'               => _x( 'Please use the company email address that corresponds to your website, <u>not</u> %s, if possible.', 'registration form - force company email', 'n2go-theme' ),
					'InsecurePassword'                => _x( 'Password is too simple.', 'registration form - insecure password', 'n2go-theme' ),
					'PasswordMismatch'                => _x( 'Passwords do not match.', 'registration form - password mismatch', 'n2go-theme' ),
					'MustAcceptOurTermsAndConditions' => _x( 'Please accept our General Terms and Conditions.', 'registration form - must accept our terms and conditions error', 'n2go-theme' ),
				)
			) ),
			'LoginForm'            => json_encode( array(
				'messages' => array(
					'PasswordFormHeadline'                      => _x( 'Reset Password', 'reset password form - headline', 'n2go-theme' ),
					'PasswordFormDescription'                   => _x( 'For safety reasons we save your password in encrypted form and therefore cannot send it to you in an email. To obtain a new password enter your email address below. We will send you an email detailing what to do next.', 'reset password form - description', 'n2go-theme' ),
					'PasswordFormEmailInputLabel'               => _x( 'Email', 'reset password form - label for email input field', 'n2go-theme' ),
					'PasswordFormRequestNewPasswordButtonLabel' => _x( 'Request new password', 'reset password form - label for request new password button', 'n2go-theme' ),
					'PasswordFormCancelButtonLabel'             => _x( 'Cancel', 'reset password form - label for cancel button', 'n2go-theme' ),
					'PasswordFormActionUrl'                     => _x( 'https://app.newsletter2go.com/en/user/index/resetpasswordrequest', 'reset password form - form action', 'n2go-theme' ),
					'PasswordFormSuccessMessage'                => _x( 'We have sent you an email. Please follow the instructions.', 'reset password form - success message', 'n2go-theme' ),
					'PasswordFormRequestLimitMessage'           => _x( 'Only one request permitted every 24h.', 'reset password form - request limit message', 'n2go-theme' ),
					'PasswordFormErrorMessage'                  => _x( 'An error has occurred.', 'reset password form - error message', 'n2go-theme' ),
					'PasswordFormEmailRequiredMessage'          => _x( 'Please enter email address.', 'reset password form - email required error message', 'n2go-theme' ),
				)
			) )
		) );

		?>

		<?php wp_head(); ?>

		<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/scripts/IE8/html5shiv.js"></script>
		<![endif]-->

		<!-- Facebook Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','//connect.facebook.net/en_US/fbevents.js');

		fbq('init', '433919070144811');
		fbq('track', "PageView");</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=433919070144811&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
	</head>
	<body class="n2go-body"><?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) {
			gtm4wp_the_gtm_tag();
		} ?>

		<div class="n2go-offCanvasView n2go-offCanvasView-isFixed n2go-offCanvasNavigation" data-view="navigation" style="display:none;">
			<div class="n2go-offCanvasNavigation_panel" style="text-align:right;">
				<a id="button-header_trial_button" href="<?php the_field( 'header_trial_button_url', 'option' ); ?>" class="n2go-button n2go-button-small" style="display:block;"><?php _ex( 'Sign up free', 'header - sign up button label', 'n2go-theme' ); ?></a>
				<a id="button-header_login_button" href="<?php the_field( 'header_login_button_url', 'option' ); ?>" class="n2go-header_loginButton n2go-button n2go-button-small n2go-button-grey" style="display:block;margin-top:1rem;"><?php _ex( 'Log in', 'header - log in button label', 'n2go-theme' ); ?></a>
			</div>
			<?php echo smn_full_navigation_hierarchy( 'main-navigation', 'n2go_mobile_nav_menu_item_output', '<nav><ul class="n2go-offCanvasNavigation_list">', '</ul></nav>', '', '' ); ?>
		</div>

		<div class="n2go-offCanvasView n2go-offCanvasView-isFixed n2go-offCanvasLanguageSelector" data-view="language-selector" style="display:none;">
			<?php

			if ( function_exists( 'n2go_output_language_selector' ) ) {
				n2go_output_language_selector();
			}

			?>
		</div>

<?php get_template_part( '/partials/navigation', 'default' ); ?>