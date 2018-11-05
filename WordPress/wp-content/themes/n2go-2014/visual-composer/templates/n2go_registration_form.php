<?php
$output = $content = '';
extract( shortcode_atts( array(
	'layout'       => 'default',
	'button_title' => '',
	'css'          => ''
), $atts ) );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_n2go_registration_form_widget wpb_content_element' . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );

$registration_form_terms_checkbox_label = wp_kses( _x( 'I accept the <a href="/general-terms-and-conditions/" target="_blank">General Terms and Conditions.</a>', 'registration form - label for terms and conditions checkbox', 'n2go-theme' ), array(
	'a'      => array(
		'href'   => array(),
		'title'  => array(),
		'target' => array(),
	),
	'br'     => array(),
	'em'     => array(),
	'strong' => array(),
	'span'   => array(),
	'b'      => array(),
	'i'      => array(),
	'u'      => array(),
) );

if ( empty( $button_title ) ) {
	$button_title = _x( 'Register for free', 'registration form - label for submit button', 'n2go-theme' );
}

$content = '<div class="n2go-registrationForm n2go-registrationForm-' . $layout . '">';

if ( $layout !== 'minimal' ):
	$content .= '<div class="n2go-registrationForm_headline n2go-headline n2go-headline-h2">' . _x( 'Register free <br>and test now.', 'registration form - headline', 'n2go-theme' ) . '</div>';
endif;

$content .= '<form id="registerForm" action="' . get_field( 'registration_form_action', 'option' ) . '" method="POST">';

$content .= '<div class="n2go-registrationForm_group">';

if ( $layout !== 'minimal' ):
	$content .= '<div>' . _x( 'Email:', 'registration form - label for email input', 'n2go-theme' ) . '</div>';
endif;

$content .= '<input class="n2go-registrationForm_input" type="text" value="" id="mail" name="mail"';

if ( $layout == 'minimal' ):
	$content .= ' placeholder="' . _x( 'Email:', 'registration form - label for email input', 'n2go-theme' ) . '"';
endif;

$content .= '>';
$content .= '</div>';
$content .= '<div class="n2go-registrationForm_group">';

if ( $layout !== 'minimal' ):
	$content .= '<div>' . _x( 'Password:', 'registration form - label for password input', 'n2go-theme' ) . '</div>';
endif;

$content .= '<input class="n2go-registrationForm_input" type="password" value="" id="password" name="password"';

if ( $layout == 'minimal' ):
	$content .= ' placeholder="' . _x( 'Password:', 'registration form - label for password input', 'n2go-theme' ) . '"';
endif;

$content .= '>';
$content .= '</div>';
$content .= '<div class="n2go-registrationForm_group">';

if ( $layout !== 'minimal' ):
	$content .= '<div>' . _x( 'Repeat password:', 'registration form - label for password confirmation input', 'n2go-theme' ) . '</div>';
endif;

$content .= '<input class="n2go-registrationForm_input" type="password" value="" id="password_reply" name="password_reply"';

if ( $layout == 'minimal' ):
	$content .= ' placeholder="' . _x( 'Repeat password:', 'registration form - label for password confirmation input', 'n2go-theme' ) . '"';
endif;

$content .= '>';
$content .= '</div>';
$content .= '<div class="n2go-registrationForm_group">';

$content .= '<input type="checkbox" value="1" name="terms" id="cbterms"> ' . $registration_form_terms_checkbox_label . '<br>';

$content .= '</div>';
$content .= '<div class="n2go-registrationForm_group">';

$content .= '<button type="submit" id="submit" name="submit" value="" class="n2go-button">' . $button_title . '</button>';
$content .= '</div>';

$content .= '<div style="position: absolute; left: -5000px;" aria-hidden="true">
				<input type="text" name="website" tabindex="-1" value="">
			</div>' .
	'</form>';

if ( $layout !== 'minimal' ):
	$content .= '<p>' . wp_kses( _x( 'Do you have a question? Contact us now:<br><b>Free&nbsp;Hotline: (858) 365 0860&nbsp;</b>', 'registration form - additional text', 'n2go-theme' ), array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'span'   => array(),
			'b'      => array(),
			'i'      => array(),
			'u'      => array(),
		) ) . '</p>';
endif;

$content .= '</div>';

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= $content;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_n2go_registration_form_widget' );

echo $output;