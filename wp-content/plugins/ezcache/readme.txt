=== ezCache ===
Contributors: upress, ilanf
Tags: upress,hosting,cache,speed,boost
Requires PHP: 5.6
Requires at least: 4.6
Tested up to: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

EzCache is an easy and innovative cache plugin that will help you significantly improve your site speed.

== Description ==

EzCache is an easy and innovative cache plugin that will help you significantly improve your site speed.
The plugin comes in a simple and easy installation, without the need for advanced technical knowledge, offers you the opportunity to make your site much faster in a few simple steps, cache pages on your site, automatically optimize images using WebP format to reduce the size of your site's images by tens of percent and save You need the extra image minimization plugin.

In addition, the plugin allows you to minimize advanced HTML files, JAVA SCRIPT files
And CSS files
In the advanced settings of the extension, you can easily save advanced settings, such as:
Configure caching by page type, set cached links,
Exclude certain user types.
And of course, you can always view statistics that will always keep you updated on your site's caching performance.

We created ezCash to take the new decade's speed experience and bring it to your WordPress sites easily and quickly

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/ezcache` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==

1. Plugin main screen

== Changelog ==
= 1.3.8 =
- FIX: WebP convertion did not detect images with non unicode URLs
- FIX: Whitelist Knockout.js HTML comments
- ADD: Option to not remove HTML comments when minifing
- ADD: Option to disable caching by cookie

= 1.3.7 =
- FIX: CLI requests getting cached
- FIX: Fail to minify protocol relative URLs

= 1.3.6 =
- FIX: Error saving settings with Simple permalinks setting
- FIX: WebP conversion may cuase blank cache file

= 1.3.5 =
- FIX: Sometimes plugin saves empty cached file
- ADD: Do not serve cached data for files not using WordPress' theme
- ADD: Allow to disable gzip compression via a wp-config define: `define( 'EZCACHE_DISABLE_GZIP', false );`

= 1.3.4 =
- FIX: Store only gzipped cache data to save on disk space
- FIX: External images processed as internal

= 1.3.3 =
- FIX: Error preventing media file deletion

= 1.3.2 =
- FIX: Error preventing saving in plugin/theme editor

= 1.3.1 =
- FIX: Translations

= 1.3 =
- FIX: Possible error when installing over older version
- FIX: Hebrew URL Rejection
- ADD: Google Font optimization
- ADD: Disable Emoji
- ADD: Auto detection for Woocommerce cart and checkout pages
