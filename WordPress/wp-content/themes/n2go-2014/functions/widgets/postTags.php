<?php

class WP_Widget_Post_Tags extends WP_Widget
{
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-n2go-post-tags', 'description' => __( "Post Tags Widget" ) );
		parent::__construct( 'n2go-post-tags', __( 'N2Go Post Tags' ), $widget_ops );
		$this->alt_option_name = 'widget_n2go_post_tags';
	}

	function widget( $args, $instance ) {
		if ( !has_tag() ) {
			return;
		}

		ob_start();
		extract( $args );

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Tags:' );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		the_tags( '<div class="n2go-postTagsWidget">', ' ', '</div>' );

		echo $after_widget;

		ob_end_flush();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ 'widget_n2go_post_tags' ] ) )
			delete_option( 'widget_n2go_post_tags' );

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				   value="<?php echo $title; ?>"/>
		</p>
		<?php
	}
}

function widget_n2go_post_tags_init() {
	register_widget( 'WP_Widget_Post_Tags' );
}

add_action( 'widgets_init', 'widget_n2go_post_tags_init' );