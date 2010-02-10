<?php
/*
Plugin Name: Sidebar Stats Widget
Plugin URI: http://www.tacticaltechnique.com/wordpress/sidebar-stats-widget/
Description: Creates a sidebar widget that displays stats about your wordpress blog including the number of posts, authors and comments.
Author: Corey Salzano
Version: 0.100209
Author URI: http://www.twitter.com/salzano
*/



function sidebar_stats_widget() {

	$saved_options = array( );
	$saved_options = get_option("widget_sidebar_stats");
	if( !$saved_options ){
		// set defaults
		$default_options = array( );
		$default_options['title'] = 'Sidebar Stats Plugin';
		$default_options['beforeStat'] = '<b>';
		$default_options['afterStat'] = '</b>';
		$default_options['prefix'] = '';
		$default_options['suffix'] = '';
		update_option( "widget_sidebar_stats",$default_options );
		$saved_options = $default_options;
	}
	$title = $saved_options['title'];
	$beforeStat = $saved_options['beforeStat'];
	$afterStat = $saved_options['afterStat'];
	$prefix = $saved_options['prefix'];
	$suffix = $saved_options['suffix'];

	global $wpdb;
	// any user that has published a post, including the administrator
	$authorCount = count( $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = 'post' AND " . get_private_posts_cap_sql( 'post' ) . " GROUP BY post_author", ARRAY_A));
	$postCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'");
	$commentCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 1");

	echo $prefix . "<h4>" . $title . "</h4>";
	echo $beforeStat . $authorCount . $afterStat . " contributors";
	echo " have published <br>" . $beforeStat . $postCount . $afterStat . " posts";
	echo " that generated <br>" . $beforeStat . $commentCount . $afterStat . " comments" . $suffix;

}

function init_sidebar_stats(){
	register_sidebar_widget("Sidebar Stats Widget", "sidebar_stats_widget");
	register_widget_control("Sidebar Stats Widget", "sidebar_stats_control");
}

function sidebar_stats_control() {

	$options = get_option("widget_sidebar_stats");

	if( !$options ){
		// set defaults
		$default_options = array( );
		$default_options['title'] = 'Sidebar Stats Plugin';
		$default_options['beforeStat'] = '<b>';
		$default_options['afterStat'] = '</b>';
		$default_options['prefix'] = '';
		$default_options['suffix'] = '';
		$options = $default_options;
		update_option( "widget_sidebar_stats",$default_options );
	}

	if ( $_POST['sidebar-stats-submit'] ) {
		// get posted values from form submission
		$new_options['title'] = strip_tags(stripslashes($_POST['sidebar-stats-title']));
		$new_options['beforeStat'] = $_POST['sidebar-stats-beforeStat'];
		$new_options['afterStat'] = $_POST['sidebar-stats-afterStat'];
		$new_options['prefix'] = $_POST['sidebar-stats-prefix'];
		$new_options['suffix'] = $_POST['sidebar-stats-suffix'];
		// if the posted options are different, save them
		if ( $options != $new_options ) {
			$options = $new_options;
			update_option('widget_sidebar_stats', $options);
		}
	}

	// format title for html
	$title = htmlspecialchars($options['title'], ENT_QUOTES);

	$beforeStat = $options['beforeStat'];
	$afterStat = $options['afterStat'];
	$prefix = $options['prefix'];
	$suffix = $options['suffix'];
?>
	<div>
	<label for="sidebar-stats-title" style="line-height:35px;display:block;">Title: <input type="text" id="sidebar-stats-title" name="sidebar-stats-title" value="<?php echo $title; ?>" /></label>
	<label for="sidebar-stats-beforeStat" style="line-height:35px;display:block;">Before each #: <input type="text" id="sidebar-stats-beforeStat" name="sidebar-stats-beforeStat" value="<?php echo $beforeStat; ?>" /></label>
	<label for="sidebar-stats-afterStat" style="line-height:35px;display:block;">After each #: <input type="text" id="sidebar-stats-afterStat" name="sidebar-stats-afterStat" value="<?php echo $afterStat; ?>" /></label>
	<label for="sidebar-stats-prefix" style="line-height:35px;display:block;">Before everything: <input type="text" id="sidebar-stats-prefix" name="sidebar-stats-prefix" value="<?php echo $prefix; ?>" /></label>
	<label for="sidebar-stats-suffix" style="line-height:35px;display:block;">After everything: <input type="text" id="sidebar-stats-suffix" name="sidebar-stats-suffix" value="<?php echo $suffix; ?>" /></label>
	<input type="hidden" name="sidebar-stats-submit" id="sidebar-stats-submit" value="1" />
	</div>
<?php

}

add_action("plugins_loaded", "init_sidebar_stats");
?>