<?php

class WP_Widget_Author extends WP_Widget
{
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-n2go-author', 'description' => __( "Author Widget" ) );
		parent::__construct( 'n2go-author', __( 'N2Go Author Widget' ), $widget_ops );
		$this->alt_option_name = 'widget_n2go_author';
	}

	function widget( $args, $instance ) {
		ob_start();
		extract( $args );

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'About the author:' );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		?>
		<div class="n2go-authorWidget">
			<div class="n2go-authorWidget_left">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '85' ); ?>
			</div>
			<div class="n2go-authorWidget_right">
				<div class="n2go-authorWidget_name"><?php the_author(); ?></div>
				<div class="n2go-authorWidget_description"><?php echo get_the_author_meta('description'); ?></div>
			</div>
		</div>
		<?php

		echo $after_widget;

		ob_end_flush();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ 'widget_n2go_author' ] ) )
			delete_option( 'widget_n2go_author' );

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

function widget_n2go_author_init() {
	register_widget( 'WP_Widget_Author' );
}

add_action( 'widgets_init', 'widget_n2go_author_init' );