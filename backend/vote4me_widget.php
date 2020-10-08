<?php

//Registering Widget
function vote4me_register_widget() {
	register_widget( 'vote4me_widget' );
}
add_action( 'widgets_init', 'vote4me_register_widget' );

// Creating the widget 
class vote4me_widget extends WP_Widget {
 
function __construct() {
	parent::__construct(

		// Base ID of your widget
		'vote4me_widget', 
		 
		// Widget name will appear in UI
		__('Add A Poll - Vote4me', 'vote4me'), 
		 
		// Widget description
		array( 'description' => __( 'Add Poll via widget in sidebar', 'vote4me' ), )
	);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
	$poll_id = $instance['poll_id'];
	$poll_widget_style = $instance['poll_widget_style'];
	echo $args['before_widget'];
	// This is where you run the code and display the output
	echo '<div class="vote4me_widget">';
	echo do_shortcode('[VOTE4ME id="'.$poll_id.'" type="'.$poll_widget_style.'" use_in="widget"][/VOTE4ME]');
	echo '</div>';
	echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {

	if ( isset( $instance[ 'poll_id' ] ) ) {
		$poll_id = $instance[ 'poll_id' ];
	}else{
		$poll_id = 1;
	}

	if ( isset( $instance[ 'poll_widget_style' ] ) ) {
		$poll_widget_style = $instance[ 'poll_widget_style' ];
	}else{
		$poll_widget_style = 'list';
	}
// Widget admin form
?>
	<p>
	<label for="<?php echo $this->get_field_id( 'poll_id' ); ?>"><?php _e( 'Select A Poll:' ); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id( 'poll_id' ); ?>" name="<?php echo $this->get_field_name( 'poll_id' ); ?>">
		<option value="0">Choose Poll</option>
		<?php
					// WP_Query arguments
					$itepollBackednqueryargs = array(
						'post_type'              => array( 'vote4me_poll' ),
						'post_status'            => array( 'publish' ),
						'nopaging'               => false,
						'paged'                  => '0',
						'posts_per_page'         => '20',
						'order'                  => 'DESC',
					);

					// The Query
					$itepollBackednquery = new WP_Query( $itepollBackednqueryargs );

					// The Loop
					$i=1;
					if ( $itepollBackednquery->have_posts() ) {
						while ( $itepollBackednquery->have_posts() ) {
							$itepollBackednquery->the_post();?>
							<option value="<?php echo get_the_id();?>"<?php if($poll_id == get_the_id()) echo " selected";?>><?php echo the_title();?></option>
						<?php }
					}
					?>
	</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'poll_widget_style' ); ?>"><?php _e( 'Poll Style in Widget:' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'poll_widget_style' ); ?>" name="<?php echo $this->get_field_name( 'poll_widget_style' ); ?>">
			<option value="list"<?php if($poll_widget_style == 'list') echo ' selected';?>>List</option>
			<option value="grid"<?php if($poll_widget_style == 'grid') echo ' selected';?>>Grid</option>
		</select>
	</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['poll_id'] = ( ! empty( $new_instance['poll_id'] ) ) ? strip_tags( $new_instance['poll_id'] ) : '';
	$instance['poll_widget_style'] = ( ! empty( $new_instance['poll_widget_style'] ) ) ? strip_tags( $new_instance['poll_widget_style'] ) : '';
	return $instance;
}
} // Class vote4me_widget ends here