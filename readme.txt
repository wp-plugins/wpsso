=== WordPress Social Sharing Optimization ===
Contributors: jsmoriss
Donate Link: http://surniaulula.com/extend/plugins/wpsso/
Tags: nextgen, featured, attached, open graph, meta, facebook, google, google plus, g+, twitter, linkedin, social, seo, pinterest, rich pins, tumblr, stumbleupon, widget, language, multilingual, object cache, transient cache, wp_cache, nggalbum, nggallery, singlepic, imagebrowser, nextgen gallery, gallery, twitter cards, photo card, gallery card, player card, large image summary card, summary card, woocommerce, marketpress, e-commerce, multisite, hashtags, bbpress, buddypress, jetpack, photon, slideshare, vimeo, wistia, youtube
License: GPLv3
License URI: http://surniaulula.com/wp-content/plugins/wpsso/license/gpl.txt
Requires At Least: 3.0
Tested Up To: 3.8.1
Stable Tag: 2.4.0

Improves Ranking and Click-Through-Rate (CTR) on Social Websites and Google Search &mdash; A Fast, Reliable and Full Featured Plugin!

== Description ==

WordPress Social Sharing Optimization (WPSSO) adds HTML meta tags to the head section of WordPress webpages for **improved Google Search results / ranking and sharing on Facebook, Google+, Twitter, LinkedIn, Pinterest, StumbleUpon, Tumblr and other social websites**.

= Summary of Features =

**Free (GPL) Version**

* Adds Open Graph / Rich Pin meta tags (Facebook, Google+, LinkedIn, Pinterest).
* Configurable image sizes, title and description lengths for different contexts (Facebook, Google, etc.).
* Optional fallback to a default image and video for index and search webpages.
* Validation of source image dimensions to provide accurate images for the social websites.
* Auto-generation of innacurate / missing WordPress image sizes.
* Support for embedded videos (iframe and/or object HTML tags).
* Fully render content (including shortcodes) for accurate description texts.
* Include author and publisher profile URLs for Facebook and Google Search.
* Include hashtags from Post / Page WordPress Tags.
* Include the author's name for Pinterest Rich Pins.
* Uses object and transient cache for fastest execution speed.
* Provides Facebook, Google+ and Twitter URL profile contact fields.
* Includes a Google / SEO description meta tag if a known SEO plugin is not detected.
* Validation tools and meta tag preview information on admin edit pages.

**Pro Version**

* Adds Twitter Card meta tags (Summary, Large Image Summary, Photo, Gallery, Player, and Product).
* Additional image sizes for each type of Twitter Card.
* Additional profile contact fields with configurable label and field names.
* Custom meta tag values (topic, description, image, video, etc.) for each Post, Page, and custom post type.
* Ability to turn off / exclude specific Google / SEO, Open Graph and Twitter Card meta tags.
* Integrates with 3rd party plugins and services for additional image, video, product, and content information:
	* NextGEN Gallery
	* JetPack Photon
	* WordPress SEO by Yoast
	* All in One SEO Pack
	* WooCommerce
	* MarketPress - WordPress eCommerce
	* WP e-Commerce
	* bbPress
	* BuddyPress
	* Slideshare, Vimeo, Wistia, Youtube APIs

<blockquote>
<p>WPSSO is a fork (child) of the popular <a href="http://wordpress.org/plugins/nextgen-facebook/">NGFB Open Graph+</a> plugin &ndash; they have the same author, many of the same great features, but WPSSO strives to be a little <strong>smaller and faster</strong> by removing the sharing buttons and their related features (shortcodes, widgets, stylesheets, javascript caching, and url shortening). WPSSO has 25% less code, is 0.006 secs faster per page load, and is often preferred for websites that already have (or don't need) a set of sharing buttons.</p>
</blockquote>

= Complete Meta Tags =

WPSSO adds Facebook / [Open Graph](http://ogp.me/), [Pinterest Rich Pins](http://developers.pinterest.com/rich_pins/), [Twitter Cards](https://dev.twitter.com/docs/cards), and [Search Engine Optimization](http://en.wikipedia.org/wiki/Search_engine_optimization) meta tags to the head section of webpages. These meta tags are used by Google Search and most Social Websites to describe and display your content correctly (title, description, hashtags, images, videos, product, author profile / authorship, publisher, etc.). [See a few examples from Google Search / Google+, Facebook, Twitter, Pinterest, StumbleUpon, Tumblr, and others](/extend/plugins/wpsso/screenshots/). 

WPSSO (Pro version) provides the [Summary](https://dev.twitter.com/docs/cards/types/summary-card), [Large Image Summary](https://dev.twitter.com/docs/cards/large-image-summary-card), [Photo](https://dev.twitter.com/docs/cards/types/photo-card), [Gallery](https://dev.twitter.com/docs/cards/types/gallery-card), [Player](https://dev.twitter.com/docs/cards/types/player-card) and [Product](https://dev.twitter.com/docs/cards/types/product-card) Twitter Cards &ndash; *including configurable image sizes for each card type*.

= Excellent Performance =

**WPSSO is fast and tuned for performance**, and unlike most plugins, makes full use of all available caching techniques (persistent / non-persistent object and disk caching).

**WPSSO only loads the library files and object classes it needs**, keeping it small, fast, and yet still able to support a wide range of 3rd party integration features.

<p>An example of PHP code execution speeds from <a href="http://wordpress.org/plugins/p3-profiler/">P3 (Plugin Performance Profiler)</a>, using <a href="http://wptest.io/">WP Test Data</a> and the default settings of a few popular plugins:</p>
<ul>
	<li><strong>0.0117</strong> secs - All in One SEO Pack v2.1.4</li>
	<li><strong>0.0124</strong> secs - <strong>WordPress Social Sharing Optimization (WPSSO) v2.4.0</strong></li>
	<li><strong>0.0130</strong> secs - MarketPress - WordPress eCommerce v2.9.2.1 (<em>No Products</em>)</li>
	<li><strong>0.0179</strong> secs - NGFB Open Graph+ v7.4.0</li>
	<li><strong>0.0322</strong> secs - WP e-Commerce v3.8.13.3 (<em>No Products</em>)</li>
	<li><strong>0.0393</strong> secs - bbPress v2.5.3 (<em>No Forums or Topics</em>)</li>
	<li><strong>0.0405</strong> secs - WooCommerce v2.1.5 (<em>No Products</em>)</li>
	<li><strong>0.0572</strong> secs - SEO Ultimate v7.6.2</li>
	<li><strong>0.0579</strong> secs - Facebook v1.5.5</li>
	<li><strong>0.0656</strong> secs - BuddyPress v1.9.2 (<em>No Activities</em>)</li>
	<li><strong>0.1051</strong> secs - WordPress SEO v1.5.2.5</li>
</ul>

= 3rd Party Integration =

Aside from the additional support for Twitter Cards, the main difference between the Free (GPL) and Pro versions is the integration of 3rd party plugins and services.

**Images and Videos**

WPSSO detects and uses all images - associated or included in your Post or Page content - including WordPress Media Library image galleries and embedded videos from Slideshare, Vimeo, Wistia, and Youtube (including their preview images). WordPress Media Library images (and NextGEN Gallery in the Pro version) are resized according to their intended audience (Facebook, Twitter, Pinterest, etc).

WPSSO (Pro version) also includes support for [JetPack Photon](http://jetpack.me/support/photon/) and [NextGEN Gallery v1 and v2](http://wordpress.org/plugins/nextgen-gallery/) albums, galleries and images (shortcodes, image tags, album / gallery preview images, etc.).

**Enhanced SEO**

WPSSO (Pro version) integrates with [WordPress SEO by Yoast](http://wordpress.org/plugins/wordpress-seo/) and [All in One SEO Pack](http://wordpress.org/plugins/all-in-one-seo-pack/), making sure your custom SEO settings are reflected in the Open Graph, Rich Pin, and Twitter Card meta tags.

**eCommerce Products**

WPSSO (Pro version) also supports [WooCommerce v1 and v2](http://wordpress.org/plugins/woocommerce/), [MarketPress - WordPress eCommerce](http://wordpress.org/plugins/wordpress-ecommerce/) and [WP e-Commerce](http://wordpress.org/plugins/wp-e-commerce/) product pages, creating appropriate meta tags for [Facebook Products](https://developers.facebook.com/docs/payments/product/), [Twitter Product Cards](https://dev.twitter.com/docs/cards/types/product-card) and [Pinterest Rich Pins](http://developers.pinterest.com/rich_pins/), including variations and additional / custom images.

**Forums and Social**

WPSSO (Pro version) supports [bbPress](http://wordpress.org/plugins/bbpress/) and [BuddyPress](http://wordpress.org/plugins/buddypress/) (see the [BuddyPress Integration Notes](http://surniaulula.com/codex/plugins/wpsso/notes/buddypress-integration/)), making sure your meta tags reflect the page content, including appropriate titles, descriptions, images, etc.

= Custom Contacts =

WPSSO (Pro version) allows you to customize the field names, label, and show / remove the following Contact Methods from the user profile page:

* AIM
* Facebook 
* Google+ 
* Jabber / Google Talk
* LinkedIn 
* Pinterest 
* Skype 
* Tumblr 
* Twitter 
* Yahoo IM
* YouTube

= Clean Uninstall =

**Try the WPSSO plugin with complete confidence** - when uninstalled, WPSSO removes *all* traces of itself from the database (options, site options, user and post meta, transients, etc.).

= Great Support =

**WPSSO support and development is on-going**. You can review the [FAQ](http://faq.wpsso.surniaulula.com/) and [Notes](http://notes.wpsso.surniaulula.com/) pages for additional setup information. If you have any suggestions or comments, post them to the [WordPress support forum](http://wordpress.org/support/plugin/wpsso) or the [Pro version support website](http://support.wpsso.surniaulula.com/).

Follow Surnia Ulula on [Google+](https://plus.google.com/+SurniaUlula?rel=author), [Facebook](https://www.facebook.com/SurniaUlulaCom), and [Twitter](https://twitter.com/surniaululacom), and [YouTube](http://www.youtube.com/user/SurniaUlulaCom).

== Installation ==

= How-To =

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/how-to/install-the-plugin/">Install the Plugin</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/how-to/uninstall-the-plugin/">Uninstall the Plugin</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/how-to/migrate-from-ngfb-open-graph-to-wpsso/">Migrate from NGFB Open Graph+ to WPSSO</a></li>
</ul>

== Frequently Asked Questions ==

= Frequently Asked Questions =

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/can-i-use-the-pro-version-on-multiple-websites/">Can I use the Pro version on multiple websites?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/does-linkedin-read-the-open-graph-meta-tags/">Does LinkedIn read the Open Graph meta tags?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/doesnt-an-seo-plugin-cover-that/">Doesn’t an SEO plugin cover that?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-can-i-exclude-ignore-certain-parts-of-the-content-text/">How can I exclude / ignore certain parts of the content text?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-can-i-see-what-facebook-sees/">How can I see what Facebook sees?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-can-i-share-a-single-nextgen-gallery-image/">How can I share a single NextGEN Gallery image?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-do-i-attach-an-image-without-showing-it-on-the-webpage/">How do I attach an image without showing it on the webpage?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-do-i-install-the-wpsso-pro-version/">How do I install the WPSSO Pro version?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-does-wpsso-find-images/">How does WPSSO find images?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/w3c-says-there-is-no-attribute-property/">W3C says “there is no attribute ‘property’”</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-about-google-search-and-google-plus/">What about Google Search and Google Plus?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-features-of-nextgen-gallery-are-supported/">What features of NextGEN Gallery are supported?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-is-the-difference-between-the-gpl-and-pro-versions/">What is the difference between the Free (GPL) and Pro versions?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/where-do-i-start/">Where do I start?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-arent-pins-from-my-website-posting-rich/">Why aren’t Pins from my website posting Rich?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-do-my-facebook-shares-have-small-images/">Why do my Facebook shares have small images?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-facebook-play-videos-instead-of-linking-them/">Why does Facebook play videos instead of linking them?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-google-structured-data-testing-tool-show-errors/">Why does Google Structured Data Testing Tool show errors?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-wpsso-ignore-some-img-html-tags/">Why does WPSSO ignore some &lt;img/&gt; HTML tags?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-doesnt-facebook-show-the-correct-image/">Why doesn’t Facebook show the correct image?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-dont-my-twitter-cards-show-on-twitter/">Why don’t my Twitter Cards show on Twitter?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-is-the-open-graph-title-the-same-for-every-webpage/">Why is the Open Graph title the same for every webpage?</a></li>
</ul>

== Other / Additional Notes ==

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/about-pro-addons/">About Pro Addons</a>
	<ul>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/about-pro-addons/slideshare-vimeo-wistia-youtube-apis/">Slideshare, Vimeo, Wistia, Youtube APIs</a>
			<div>An example showing the difference in meta tags between the WPSSO Free (GPL) version, which does not support video APIs or Twitter Cards, and the Pro version which does.</div></li>
	</ul></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/constants/">Constants</a>
		<div>A list of available PHP constants for the WPSSO plugin.</div></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/debugging-and-problem-solving/">Debugging and Problem Solving</a>
		<div>A few debugging and problem solving techniques for the WPSSO plugin for WordPress.</div></li>
	<li><a href="http://surniaulula.com/codex/plugins/nextgen-facebook/filters/">Filters</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/integration-notes/">Integration Notes</a>
	<ul>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/integration-notes/buddypress-integration/">BuddyPress Integration</a>
			<div>BuddyPress specific integration issues, and a few possible techniques to overcome them.</div></li>
	</ul></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/multisite-network-support/">Multisite / Network Support</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/performance-tuning/">Performance Tuning</a>
		<div>WPSSO is highly optimized, but you may still improve page load times by a few milliseconds by considering the following suggestions.</div></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/resources-and-contacts/">Resources and Contacts</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/nextgen-facebook/notes/working-with-image-attachments/">Working with Image Attachments</a>
		<div>A selection of plugins available to manage WordPress image attachments.</div></li>
</ul>

== Screenshots ==

1. Screenshot 1 : General Settings Page
2. Screenshot 2 : Advanced Settings Page
5. Screenshot 3 : An Example Facebook Link Share
6. Screenshot 4 : An Example Facebook Video Share
7. Screenshot 5 : An Example Google+ Link Share
8. Screenshot 6 : An Example Google+ Video Share
9. Screenshot 7 : An Example Google Search Result showing Author Profile Info
10. Screenshot 8 : An Example LinkedIn Share
11. Screenshot 9 : An Example Pinterest Image Pin
12. Screenshot 10 : An Example StumbleUpon Share
13. Screenshot 11 : An Example Tumblr 'Link' Share
14. Screenshot 12 : An Example Tumblr 'Photo' Share
15. Screenshot 13 : An Example Tumblr 'Video' Share
16. Screenshot 14 : An Example Twitter 'Summary' Card
17. Screenshot 15 : An Example Twitter 'Large Image Summary' Card
18. Screenshot 16 : An Example Twitter 'Photo' Card
19. Screenshot 17 : An Example Twitter 'Gallery' Card
20. Screenshot 18 : An Example Twitter 'Product' Card from a WooCommerce Product Page

== Changelog ==

= Version 2.4.0 =

* Bugfixes
	* Fixed missing check for 'og_def_img_on_index' and 'og_def_img_on_search' options for Twitter Card meta tags (Pro version).
	* Fixed an incorrect 'twitter:title' value when in the admin interface, by adding a missing `$use_post` argument to `SucomWebpage::get_title()` in the Twitter Card addon (Pro version).
* Enhancements
	* Split the existing 'Image and Video' General settings tab into separate 'Images' and 'Videos' settings tabs.
	* Added 'Default Video URL', 'Use Default Video on Indexes', and 'Use Default Video on Search Results' options.
	* Added a new `WpssoMedia::get_default_video()` method.
	* Added a new 'wpsso_the_object' filter to modify the return of post objects.
	* Changed the update hook from 'site_transient_update_plugins' to 'pre_set_site_transient_update_plugins' (Pro version).
	* Added debugging messages to the `SucomUpdate::inject_update()` method (Pro version).
	* Added hooks into 'wp_head', 'wp_footer', 'admin_head', and 'admin_footer' to print the debug log.
	* Added reporting on the number of licenses assigned (Pro version).
	* Changed the image resize crop value from 1/0 to true/false.
	* Added a 'Object Cache Expiry' option to the multisite Network admin settings page.
	* Increased the default object cache expiry value from 3600 to 7200 seconds.

= Version 2.3.2 =

* Bugfixes
	* *None*
* Enhancements
	* Changed the default Open Graph 'Image Dimensions' from 1200x1200 cropped to 800x800 cropped.
	* Disabled the Default Image URL option when a Default Image ID has been specified.
	* Updated a few help messages in lib/messages.php.

= Version 2.3.1 =

* Bugfixes
	* *None*
* Enhancements
	* Added action hooks for 'wpmu_new_blog' and 'wpmu_activate_blog' to install default options (if necessary) when **multisite** blogs are created and/or activated.
	* Added a notice error message if / when the WordPress `wp_remote_get()` function (used when checking for updates) returns an error (Pro version).
	* Changed the update filter hook priorities from 10 to 100 in order to avoid 3rd party filters from modifying the update information (Pro version).
	* Changed the default Open Graph Image Dimensions from 1200x630 cropped to 1200x1200 cropped.
	* Changed the update check schedule from every 12 hours to every 24 hours.
	* Changed the `WpssoOptions::get_defaults()` method to filter the default options only once.

== Upgrade Notice ==

= 2.4.0 =

Fixed the 'twitter:title' value in admin interface, fixed missing Default Image use for Twitter Cards, added new default video options, added license use status (Pro version).

= 2.3.2 =

Changed default Open Graph 'Image Dimensions' from 1200x1200 to 800x800 cropped, disabled Default Image URL when a Default Image ID has been specified, updated a few help messages.

= 2.3.1 =

Added default options when creating a new multisite blog, added check for wp_remote_get() errors, changed the default Open Graph image size to 1200x1200 cropped.

