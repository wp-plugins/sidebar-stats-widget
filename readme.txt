=== Sidebar stats widget ===
Contributors: salzano
Donate link: http://www.tacticaltechnique.com/donate/
Tags: wordpress stats, author count, total post count, total comment count, site stats
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: 0.120218

Creates a sidebar widget that displays stats about your wordpress blog including
the total number of posts, authors and comments.

== Description ==

<p>Sample output:</p>
<p>761 contributors have published<br>990 posts that generated<br>656 comments</p>
<p>This plugin queries the wordpress database to count the number
of authors that have published at least one post, the total number of published posts, and
the number of approved comments sitewide. Options are available to customize the 
title and appearance of the output.</p>

== Installation ==

1. Download sidebar-stats-widget.zip
2. Decompress the file contents
3. Upload the sidebar-stats-widget folder to a Wordpress plugins directory (/wp-content/plugins)
4. Activate the plugin from the Administration Dashboard
5. Open the Widgets page under the Appearance section
6. Drag the Sidebar Stats Widget to the active sidebar
7. Configure the widget options to suit your needs and click Save

== Frequently Asked Questions ==

= Need help? Have a suggestion? =
[Visit this plugin's home page](http://www.tacticaltechnique.com/wordpress/sidebar-stats-widget/)

== Screenshots ==

1. Sample output using "Site Stats" as the title and bold tags on the numerical stats via the widget options.

== Change Log ==

= 0.120218 =
* Implemented 2.8 Widgets API
* Now uses $before_widget, $after_widget, $before_title, $after_title values from the theme

= 0.101230 =
* Added element ID attributes
* Condensed default options code
* Added option to wrap widget output in &lt;li&gt; HTML tags
* Removed options to prefix and suffix widget output

= 0.100209 =
First build

== Upgrade Notice ==

= 0.120218 =
I upgraded this plugin's code to the use the 2.8 Widgets API. This means that this plugin now plays nicely with other plugins that may modify the behavior of your widgets. It also integrates with themes better by using $before_widget, $after_widget, $before_title and $after_title values.

= 0.101230 =
This release contains HTML element ID attributes that make the widget output more CSS friendly. The prefix and suffix options have been removed. They have been replaced with an option to wrap the widget output in list item HTML tags to place nice with themes.

= 0.100209 =
First build
