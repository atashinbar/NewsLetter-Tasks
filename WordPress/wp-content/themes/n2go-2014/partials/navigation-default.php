<div class="n2go-header" data-view-element data-rel-view="main">
	<div class="n2go-centeredContent n2go-centeredContent-page">

		<div class="n2go-header_right">
			<?php

			if ( function_exists( 'n2go_output_language_selector_options' ) )
			{
				echo '<select class="n2go-languageDropdown">';
				n2go_output_language_selector_options();
				echo '</select>';
			}

			?>

			<?php

			if ( function_exists( 'n2go_output_language_selector_toggle' ) ) {
				n2go_output_language_selector_toggle();
			}

			?>
			<a id="button-header_registerButton" href="<?php the_field( 'header_trial_button_url', 'option' ); ?>" class="n2go-header_registerButton n2go-button n2go-button-small" style="margin-right: 10px;"><?php _ex( 'Sign up free', 'header - sign up button label', 'n2go-theme' ); ?></a>
			<a id="button-header_loginButton" href="<?php the_field( 'header_login_button_url', 'option' ); ?>" class="n2go-header_loginButton n2go-button n2go-button-small n2go-button-inverted n2go-button-grey"><?php _ex( 'Log in', 'header - log in button label', 'n2go-theme' ); ?></a>
		</div>

		<div class="n2go-offCanvasNavigationToggle">
			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 viewBox="0 0 1024 1024" enable-background="new 0 0 1024 1024" xml:space="preserve">
			<g>
				<g>
					<rect fill="#393939" x="0" y="144.848" width="1024" height="73.943"/>
					<rect fill="#393939" x="0" y="472.181" width="1024" height="73.964"/>
					<rect fill="#393939" x="0" y="805.152" width="1024" height="74"/>
				</g>
			</g>
			</svg>
		</div>

		<a id="link-home" href="<?php echo get_bloginfo( 'url' ); ?>" class="n2go-header_logoWrapper">
			<div class="n2go-header_logo">
				<div class="n2go-svg" data-svg-ref="logo"></div>
			</div>
		</a>

		<?php echo smn_full_navigation_hierarchy( 'main-navigation', 'n2go_main_nav_menu_item_output', '<nav class="n2go-header_navigation"><ul class="n2go-centeredContent n2go-centeredContent-page">', '</ul></nav>', '<li>', '</li>' ); ?>
	</div>
</div>