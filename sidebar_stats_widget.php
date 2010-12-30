<?php
/*
Plugin Name: Sidebar stats widget
Plugin URI: http://www.tacticaltechnique.com/wordpress/sidebar-stats-widget/
Description: Creates a sidebar widget that displays stats about your wordpress blog including the total number of posts, authors and comments.
Author: Corey Salzano
Version: 0.101230
Author URI: http://www.twitter.com/salzano
*/



function sidebar_stats_widget() {
	$saved_options = array( );
	$saved_options = get_option("widget_sidebar_stats");
	if( !$saved_options ){
		$default_options = get_sidebar_stats_widget_default_options;
		update_option( "widget_sidebar_stats",$default_options );
		$saved_options = $default_options;
	}
	$title = $saved_options['title'];
	$beforeStat = $saved_options['beforeStat'];
	$afterStat = $saved_options['afterStat'];
	$wrapWithLiTF = $saved_options['wrapWithLiTF'];

	global $wpdb;
	// any user that has published a post, including the administrator
	$authorCount = count( $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = 'post' AND " . get_private_posts_cap_sql( 'post' ) . " GROUP BY post_author", ARRAY_A));
	$postCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'");
	$commentCount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 1");

	if($wrapWithLiTF){ echo "<li id=\"sidebar-stats-widget\">"; }
	echo "<h4 id=\"ssw-title\">" . $title . "</h4>";
	echo $beforeStat . $authorCount . $afterStat . " contributors have published<br>";
	echo $beforeStat . $postCount . $afterStat . " posts that generated<br>";
	echo $beforeStat . $commentCount . $afterStat . " comments";
	if($wrapWithLiTF){ echo "</li>"; }
}

function get_sidebar_stats_widget_default_options( ){
	$default_options = array(	'title' => 'Sidebar stats',
								'beforeStat' => '<b>',
								'afterStat' => '</b>',
								'wrapWithLiTF' => false );
	return $default_options;
}

function init_sidebar_stats(){
	register_sidebar_widget("Sidebar stats widget", "sidebar_stats_widget");
	register_widget_control("Sidebar stats widget", "sidebar_stats_control");
}

function sidebar_stats_control() {

	$options = get_option("widget_sidebar_stats");

	if( !$options ){
		$default_options = get_sidebar_stats_widget_default_options;
		update_option( "widget_sidebar_stats",$default_options );
	}

	if ( $_POST['sidebar-stats-submit'] ) {
		// get posted values from form submission
		$new_options['title'] = strip_tags(stripslashes($_POST['sidebar-stats-title']));
		$new_options['beforeStat'] = $_POST['sidebar-stats-beforeStat'];
		$new_options['afterStat'] = $_POST['sidebar-stats-afterStat'];
		if( $_POST['sidebar-stats-wrapWithLiTF']=="1"){
			$new_options['wrapWithLiTF'] = true;
		} else{
			$new_options['wrapWithLiTF'] = false;
		}

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
	$wrapWithLiTF = $options['wrapWithLiTF'];
?>
	<div>
	<label for="sidebar-stats-title" style="line-height:35px;display:block;">Title: <input type="text" id="sidebar-stats-title" name="sidebar-stats-title" value="<?php echo $title; ?>" /></label>
	<label for="sidebar-stats-beforeStat" style="line-height:35px;display:block;">Before each #: <input type="text" id="sidebar-stats-beforeStat" name="sidebar-stats-beforeStat" value="<?php echo $beforeStat; ?>" /></label>
	<label for="sidebar-stats-afterStat" style="line-height:35px;display:block;">After each #: <input type="text" id="sidebar-stats-afterStat" name="sidebar-stats-afterStat" value="<?php echo $afterStat; ?>" /></label>
	<label for="sidebar-stats-wrapWithLiTF" style="line-height:35px;display:block;"><input type="checkbox" id="sidebar-stats-wrapWithLiTF" value="1" name="sidebar-stats-wrapWithLiTF"<?php if($wrapWithLiTF){ echo " checked"; } ?> /> Wrap widget with &lt;li&gt; tags</label>
	<input type="hidden" name="sidebar-stats-submit" id="sidebar-stats-submit" value="1" />
	</div>
<?php

}

add_action("plugins_loaded", "init_sidebar_stats");
?>