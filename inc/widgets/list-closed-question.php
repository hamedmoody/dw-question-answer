<?php  

class dwqa_Closed_Question_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function dwqa_Closed_Question_Widget() {
        $widget_ops = array( 'classname' => 'dwqa-closed-question', 'description' => __('Show a list of questions that was ordered by views.','dwqa') );
        $this->WP_Widget( 'dwqa-closed-question', __('DWQA Closed Questions','dwqa'), $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        $instance = wp_parse_args( $instance, array( 
            'title' => __('Closed Questions','dwqa'),
            'number' => 5
        ) );
        
        echo $before_widget;
        echo $before_title;
        echo $instance['title'];
        echo $after_title;
		$args = array(
			'post_type' => 'dwqa-question',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => '_dwqa_status',
					'compare' => '=',
					'value' => 'resolved'
				),
				array(
					'key' => '_dwqa_status',
					'compare' => '=',
					'value' => 'closed'
				)
			)
		);
        $questions = new WP_Query( $args );
        if( $questions->have_posts() ) {
            echo '<div class="dwqa-popular-questions">';
            echo '<ul>';
            while ( $questions->have_posts() ) { $questions->the_post();
                echo '
				<li><a href="'.get_permalink().'" class="question-title">'.get_the_title().'</a> '.__('asked by','dwqa').' ' . get_the_author_link();
				'</li>';
            }   
            echo '</ul>';
            echo '</div>';
        }
        wp_reset_query();
        wp_reset_postdata();
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( $instance, array( 
            'title' => '',
            'number' => 5
        ) );
        ?>
        <p><label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Widget title') ?></label>
        <input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?>" value="<?php echo $instance['title'] ?>" class="widefat">
        </p>
        <p><label for="<?php echo $this->get_field_id('number') ?>"><?php _e('Number of posts') ?></label>
        <input type="text" name="<?php echo $this->get_field_name('number') ?>" id="<?php echo $this->get_field_id('number') ?>" value="<?php echo $instance['number'] ?>" class="widefat">
        </p>
        <?php
    }
}
add_action( 'widgets_init', create_function( '', "register_widget( 'dwqa_closed_Question_Widget' );" ) );

?>