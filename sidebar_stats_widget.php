<?php
/*
Plugin Name: Sidebar stats widget
Plugin URI: http://www.tacticaltechnique.com/wordpress/sidebar-stats-widget/
Description: Creates a sidebar widget that displays stats about your wordpress blog including the total number of posts, authors and comments.
Author: Corey Salzano
Version: 0.120218
Author URI: http://www.twitter.com/salzano
*/

class sidebar_stats_widget extends WP_Widget {

	function sidebar_stats_widget() {
		// widget actual processes
		parent::WP_Widget( /* Base ID */'sidebar_stats_widget', /* Name */'Sidebar stats widget', array( 'description' => 'Posts, comments and user counts' ) );
	}

	function form($instance) {
		// outputs the options form on admin

		// format title for html
		$title = htmlspecialchars($instance['title'], ENT_QUOTES);

		$beforeStat = $instance['beforeStat'];
		$afterStat = $instance['afterStat'];
?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>" style="line-height:35px;display:block;">Title: <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" /></label>
	<label for="<?php echo $this->get_field_id('beforeStat'); ?>" style="line-height:35px;display:block;">Before each #: <input type="text" id="<?php echo $this->get_field_id('beforeStat'); ?>" name="<?php echo $this->get_field_name('beforeStat'); ?>" value="<?php echo $beforeStat; ?>" /></label>
	<label for="<?php echo $this->get_field_id('afterStat'); ?>" style="line-height:35px;display:block;">After each #: <input type="text" id="<?php echo $this->get_field_id('afterStat'); ?>" name="<?php echo $this->get_field_name('afterStat'); ?>" value="<?php echo $afterStat; ?>" /></label>
	</p>
<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes( $new_instance['title'] ));
		$instance['beforeStat'] = $new_instance['beforeStat'];
		$instance['afterStat'] = $new_instance['afterStat'];
		return $instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		extract($args, EXTR_SKIP);

		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$beforeStat = $instance['beforeStat'];
		$afterStat = $instance['afterStat'];

		global $wpdb;
		$authorCount = count( $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = 'post' AND " . get_private_posts_cap_sql( 'post' ) . " GROUP BY post_author", ARRAY_A));
		$postCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'");
		$commentCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 1");

		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo $beforeStat . $authorCount . $afterStat . " contributors have published<br>";
		echo $beforeStat . $postCount . $afterStat . " posts that generated<br>";
		echo $beforeStat . $commentCount . $afterStat . " comments";
		echo $after_widget;
	}
}

if( !function_exists('register_sidebar_stats_widget')){
	add_action('widgets_init', 'register_sidebar_stats_widget');
	function register_sidebar_stats_widget() {
	    register_widget('sidebar_stats_widget');
	}
}
?>