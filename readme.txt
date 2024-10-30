=== jQuery Tagline Rotator ===
Contributors: ArvinJA
Tags: jquery, tagline, plugin, rotation, wordpress, site-description
Requires at least: 3.0.1
Tested up to: 3.2.2
Stable tag: 0.1.5

A plugin that will rotate your tagline in a random sequence using jQuery, there's no reloads required for the tagline to change. 

== Description ==

A plugin that will rotate your tagline in a random sequence using jQuery, there's no reloads required for the tagline to change.
The plugin uses the MySQL database that your wordpress installation is already depending on.
This plugin has only been tested with TwentyTen so there might be a few bugs, it comes with the bare-minimum needed to add taglines and change the delay for the rotation and the fade-in time.

== Installation ==

1. Upload the `jquery-tagline-rotator` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Settings -> jQuery Tagline Rotator" in the admin section and change the options and add taglines.

== Frequently Asked Questions ==

= How do I contact you if I encounter a problem? =
wordpressdev@arvtard.com

= Where are the taglines stored? =
In a single field called 'jtr_taglines' in the options table in the MySQL database that your wordpress installation relies on.

= Can I rotate images with the plugin? =
You are free to try, it wasn't designed for this purpose but the feature might be added in the future.
For now, my time is limited and I'd rather work on other things, however, if you feel that your wallet is getting too heavy for you, I might be of assistance.


== Screenshots ==


== Changelog ==

= 0.1 =
* The plugin is created.

= 0.1.1 =
* BUGFIX: You couldn't previously delete the first tagline that you added, now you can. Thanks to Sara Eileen who reported the bug, it's appreciated.
* jQuery is now enqueued automatically, so there's no need to enqueue it yourself.

= 0.1.2 =
* New Feature: You can now change the fade-in time.
* BUGFIX: There will be no slashes added to apostrophes and the like
* BUGFIX: Fixed a compatability issue with Wordpress 3.2.1
* Made it so the tagline does not have to be wrapped in a container with a site-description id, it should hence work out of the box for most themes.
* The plugin will now leave your &lt;title&gt; alone.

= 0.1.3 =
* BUGFIX: Stopped the plugin from inserting a span with the site description inside of the head tag when other plugins used the bloginfo('description') function with the wp_head action.

= 0.1.4 =
* BUGFIX: Fixed it so the plugin won't skip the first tagline in the array upon wrap-around

= 0.1.5 =
* Having problems with git-svn, let's skip a version :)