<?php
$output = $content = '';
extract( shortcode_atts( array(
	'css' => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_login_form_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );


$content = '<div class="n2go-loginForm">' .
                '<div class="n2go-loginForm_headline n2go-headline n2go-headline-h2">' . _x( 'Login', 'login form - headline', 'n2go-theme' ) . '</div>' .
                '<form id="loginForm" action="' . get_field( 'login_form_action', 'option' ) . '" method="POST">' .
                    '<div class="n2go-loginForm_error n2go-js-loginFailed" style="display:none;">' . _x( 'Login failed. Please check your login details.', 'login form - login failed error', 'n2go-theme' ) . '</div>' .
                    '<div>' . _x( 'Email', 'login form - label for email input', 'n2go-theme' ) . '</div>' .
                    '<input class="n2go-loginForm_input" type="text" value="" id="mail" name="login_mail">' .
                    '<div>' . _x( 'Password', 'login form - label for password input', 'n2go-theme' ) . '</div>' .
                    '<input class="n2go-loginForm_input" type="password" value="" id="password" name="login_password">' .
                    '<input type="checkbox" value="0" name="permalogin" id="cbterms"> ' . _x( 'log me in automatically in future', 'login form - label for stay-logged-in checkbox', 'n2go-theme' ) . '<br>' .
                    '<button type="submit" id="submit" name="submit" value="" class="n2go-button">' . _x( 'Log in', 'login form - label for submit button', 'n2go-theme' ) . '</button>' .
                    '<input type="hidden" name="captcha" value="123456">' .
                '</form>' .

                '<a class="n2go-js-forgotPassword" style="float: right;" href="#">' . _x( 'Forgot password?', 'login form - label for forgot-password button', 'n2go-theme' ) . '</a>' .
                '<a href="' . get_field( 'login_form_signup_button_url', 'option' ) . '" style="float: left;">' . _x( 'No account yet?', 'login form - label for sign-up button', 'n2go-theme' ) . '</a>' .

            '</div>';

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_login_form_widget' );

echo $output;