<?php

class WP_Widget_Custom_Newsletter_Form extends WP_Widget
{
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_custom_recent_posts', 'description' => __( "Newsletter Sign-Up Form" ) );
		parent::__construct( 'n2go-newsletter-signup', __( 'N2Go Newsletter Sign-Up' ), $widget_ops );
		$this->alt_option_name = 'widget_n2go_newsletter_signup';

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance ) {
		$cache = array();
		if ( !$this->is_preview() ) {
			$cache = wp_cache_get( 'widget_n2go_newsletter_signup', 'widget' );
		}

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}

		if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
			echo $cache[ $args[ 'widget_id' ] ];

			return;
		}

		ob_start();
		extract( $args );

		$ctaText = ( !empty( $instance[ 'ctaText' ] ) ) ? $instance[ 'ctaText' ] : __( 'Sign up for our newsletter!' );
		$emailPlaceholder = ( !empty( $instance[ 'emailPlaceholder' ] ) ) ? $instance[ 'emailPlaceholder' ] : __( 'YOUR EMAIL' );
		$submitText = ( !empty( $instance[ 'submitText' ] ) ) ? $instance[ 'submitText' ] : __( 'SIGN UP' );
		$infoText = ( !empty( $instance[ 'infoText' ] ) ) ? $instance[ 'infoText' ] : __( 'Jederzeit abbestellbar. Keine Adressweitergabe!' );
		$trackingCode = ( !empty( $instance[ 'trackingCode' ] ) ) ? $instance[ 'trackingCode' ] : '';

		echo $before_widget;

		?>
		<div class="n2go-sidebarNewsletterForm">
			<div class="n2go-sidebarNewsletterForm_icon">
				<div class="n2go-svg" data-svg-ref="mail-outline"></div>
			</div>
			<div class="n2go-sidebarNewsletterForm_ctaText"><?php echo $ctaText; ?></div>
			<form class="n2go-js-newsletterSignupForm" action="">
				<input type="hidden" name="nl2go--key" value="74add074387011aacdbd3095f083da69aea4ee6661ab073af05d90b4da79b580$8382">
				<input type="hidden" name="nl2go--43180" value="<?php echo $trackingCode; ?>"/>
				<div style="position: absolute; left: -5000px;" aria-hidden="true">
					<input type="text" name="website" tabindex="-1" value="">
				</div>
				<input type="text" name="nl2go--mail" placeholder="<?php echo $emailPlaceholder; ?>" />
				<input type="submit" class="n2go-button n2go-button-small" value="<?php echo $submitText; ?>" />
			</form>
			<div class="n2go-sidebarNewsletterForm_infoText"><?php echo $infoText; ?></div>
		</div>
		<?php

		echo $after_widget;

		if ( !$this->is_preview() ) {
			$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
			wp_cache_set( 'widget_n2go_newsletter_signup', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'ctaText' ] = strip_tags( $new_instance[ 'ctaText' ] );
		$instance[ 'emailPlaceholder' ] = strip_tags( $new_instance[ 'emailPlaceholder' ] );
		$instance[ 'submitText' ] = strip_tags( $new_instance[ 'submitText' ] );
		$instance[ 'infoText' ] = strip_tags( $new_instance[ 'infoText' ] );
		$instance[ 'trackingCode' ] = strip_tags( $new_instance[ 'trackingCode' ] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ 'widget_n2go_newsletter_signup' ] ) )
			delete_option( 'widget_n2go_newsletter_signup' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_n2go_newsletter_signup', 'widget' );
	}

	function form( $instance ) {
		$ctaText = isset( $instance[ 'ctaText' ] ) ? esc_attr( $instance[ 'ctaText' ] ) : '';
		$emailPlaceholder = isset( $instance[ 'emailPlaceholder' ] ) ? esc_attr( $instance[ 'emailPlaceholder' ] ) : '';
		$submitText = isset( $instance[ 'submitText' ] ) ? esc_attr( $instance[ 'submitText' ] ) : '';
		$infoText = isset( $instance[ 'infoText' ] ) ? esc_attr( $instance[ 'infoText' ] ) : '';
		$trackingCode = isset( $instance[ 'trackingCode' ] ) ? esc_attr( $instance[ 'trackingCode' ] ) : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'ctaText' ); ?>"><?php _e( 'CTA Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'ctaText' ); ?>"
				   name="<?php echo $this->get_field_name( 'ctaText' ); ?>" type="text"
				   value="<?php echo $ctaText; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'emailPlaceholder' ); ?>"><?php _e( 'Email Placeholder Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'emailPlaceholder' ); ?>"
				   name="<?php echo $this->get_field_name( 'emailPlaceholder' ); ?>" type="text"
				   value="<?php echo $emailPlaceholder; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'submitText' ); ?>"><?php _e( 'Submit Button Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'submitText' ); ?>"
				   name="<?php echo $this->get_field_name( 'submitText' ); ?>" type="text"
				   value="<?php echo $submitText; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'infoText' ); ?>"><?php _e( 'Info Text:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'infoText' ); ?>"
				   name="<?php echo $this->get_field_name( 'infoText' ); ?>" type="text"
				   value="<?php echo $infoText; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'trackingCode' ); ?>"><?php _e( 'Tracking Code:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'trackingCode' ); ?>"
				   name="<?php echo $this->get_field_name( 'trackingCode' ); ?>" type="text"
				   value="<?php echo $trackingCode; ?>"/>
		</p>
		<?php
	}
}

function widget_n2go_newsletter_signup_init() {
	register_widget( 'WP_Widget_Custom_Newsletter_Form' );
}

add_action( 'widgets_init', 'widget_n2go_newsletter_signup_init' );