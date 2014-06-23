=== WordPress Social Sharing Optimization ===
Contributors: jsmoriss
Donate Link: http://surniaulula.com/extend/plugins/wpsso/
Tags: nextgen gallery, featured, attached, open graph, meta tags, facebook, google, google+, g+, twitter, linkedin, social, seo, pinterest, rich pins, multilingual, object cache, transient cache, wp_cache, nggalbum, nggallery, singlepic, imagebrowser, gallery, twitter cards, photo card, gallery card, player card, summary card, easy digital downloads, woocommerce, marketpress, e-commerce, multisite, hashtags, bbpress, buddypress, jetpack, photon, slideshare, vimeo, wistia, youtube, polylang
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Requires At Least: 3.0
Tested Up To: 3.9.1
Stable Tag: 2.5.0

Improve the appearance, ranking, and social engagement of your social shares on Facebook, Twitter, Pinterest, Google+, LinkedIn, etc.

== Description ==

<blockquote>
<p><strong>Make sure social websites present your content in the best possible way, no matter <em>how</em> your webpage is shared</strong> &mdash; from sharing buttons on the webpage, browser add-ons and extensions, or URLs pasted directly on social websites. HTML meta tags provide information about your content, not the sharing buttons.</p>
</blockquote>

<p>WordPress Social Sharing Optimization (WPSSO) <strong>provides the information search engines and social websites need</strong> to improve Google Search ranking and social engagement on Facebook, Google+, Twitter, LinkedIn, Pinterest, and many more.</p>

= Quick List of Features =

**Free / Basic Version**

* Adds Open Graph / Rich Pin meta tags (Facebook, Google+, LinkedIn, Pinterest, etc.).
* Configurable image sizes, title and description lengths for different contexts.
* Optional fallback to a default image and video for index and search webpages.
* Supports featured, attached, gallery shortcode, and/or HTML image tags in content.
* Validates image dimensions to provide accurate media for social websites.
* Auto-regeneration of inaccurate / missing WordPress image sizes.
* Support for embedded videos (iframe and object HTML tags).
* Fully renders content (including shortcodes) for accurate description texts.
* Includes author and publisher profile URLs for Facebook and Google Search.
* Includes hashtags from Post / Page WordPress Tags.
* Includes the author's name for Pinterest Rich Pins.
* Uses object and transient caches to provide incredibly fast execution speeds.
* Includes a Google / SEO description meta tag if a known SEO plugin is not detected.
* Provides Facebook, Google+ and Twitter URL profile contact fields.
* Validation tools and special meta tag preview tabs on admin edit pages.
* Customizable *multilingual* Site Title and (default) Description texts.
* Contextual help for *every* plugin option and [comprehensive online documentation](http://surniaulula.com/codex/plugins/wpsso/).

**Pro / Power-User Version**

* Twitter Card meta tags (Summary, Large Image, Photo, Gallery, Player, and Product).
* Customizable image dimensions for each Twitter Card type.
* Additional profile contact fields with configurable label and field names.
* Custom settings and meta tag values for each Post, Page, and custom post type.
* Options to exclude specific Google / SEO, Open Graph, and Twitter Card meta tags.
* Integrates with 3rd party plugins and services for additional image, video, product, and content information (see [About Pro Addons](http://surniaulula.com/codex/plugins/wpsso/notes/addons/) and [Integration Notes](http://surniaulula.com/codex/plugins/wpsso/installation/integration/) for details):
	* Plugins
		* All in One SEO Pack
		* bbPress
		* BuddyPress
		* Easy Digital Downloads
		* JetPack Photon
		* NextGEN Gallery
		* MarketPress - WordPress eCommerce
		* Polylang
		* WooCommerce
		* WordPress SEO by Yoast
		* WP e-Commerce
	* Service APIs
		* Gravatar Images
		* Slideshare Presentations
		* Vimeo Videos
		* Wistia Videos
		* Youtube Videos and Playlists

= Complete Meta Tags =

WPSSO adds Facebook / [Open Graph](http://ogp.me/), [Pinterest Rich Pins](http://developers.pinterest.com/rich_pins/), [Twitter Cards](https://dev.twitter.com/docs/cards), and [Search Engine Optimization](http://en.wikipedia.org/wiki/Search_engine_optimization) meta tags to the head section of webpages. These meta tags are used by Google Search and most social websites to describe and display your content correctly (title, description, hashtags, images, videos, product, author profile / authorship, publisher, etc.).

<blockquote>
<p><a href="http://surniaulula.com/extend/plugins/wpsso/screenshots/">See examples from Google Search, Google+, Facebook, Twitter, Pinterest, StumbleUpon, Tumblr, etc.</a> &mdash; along with screenshots of the WPSSO settings pages.</p>
</blockquote>

WPSSO (Pro version) provides the [Summary](https://dev.twitter.com/docs/cards/types/summary-card), [Large Image Summary](https://dev.twitter.com/docs/cards/large-image-summary-card), [Photo](https://dev.twitter.com/docs/cards/types/photo-card), [Gallery](https://dev.twitter.com/docs/cards/types/gallery-card), [Player](https://dev.twitter.com/docs/cards/types/player-card) and [Product](https://dev.twitter.com/docs/cards/types/product-card) Twitter Cards &mdash; *including configurable image sizes for each card type*.

* **Google / SEO Link and Meta Tags**
	* author
	* description
	* publisher
* **Facebook Meta Tags**
	* fb:admins
	* fb:app_id
* **Open Graph / Rich Pin Meta Tags**
	* article:author
	* article:publisher
	* article:published_time
	* article:modified_time
	* article:section
	* article:tag
	* og:description
	* og:image
	* og:image:secure_url
	* og:image:width
	* og:image:height
	* og:locale
	* og:site_name
	* og:title
	* og:type
	* og:url
	* og:video
	* og:video:secure_url
	* og:video:width
	* og:video:height
	* og:video:type
	* product:price:amount
	* product:price:currency
	* product:availability
* **Schema Meta Tags**
	* description
* **Twitter Card Meta Tags** (Pro version)
	* twitter:card (Summary, Large Image Summary, Photo, Gallery, Player and Product)
	* twitter:creator
	* twitter:data1
	* twitter:data2
	* twitter:data3
	* twitter:data4
	* twitter:description
	* twitter:image
	* twitter:image:width
	* twitter:image:height
	* twitter:image0
	* twitter:image1
	* twitter:image2
	* twitter:image3
	* twitter:label1
	* twitter:label2
	* twitter:label3
	* twitter:label4
	* twitter:player
	* twitter:player:width
	* twitter:player:height
	* twitter:site
	* twitter:title

= 3rd Party Integration =

Aside from the additional support for Twitter Cards, the main difference between the Free and Pro versions is the integration of 3rd party plugins and services.

**Images and Videos**

WPSSO detects and uses all images - associated or included in your Post or Page content - including WordPress Media Library image galleries and embedded videos from Slideshare, Vimeo, Wistia, and Youtube (including their preview images). WordPress Media Library images (and NextGEN Gallery in the Pro version) are resized according to their intended audience (Facebook, Twitter, Pinterest, etc).

WPSSO (Pro version) also includes support for [JetPack Photon](http://jetpack.me/support/photon/) and [NextGEN Gallery v1 and v2](http://wordpress.org/plugins/nextgen-gallery/) albums, galleries and images (shortcodes, image tags, album / gallery preview images, etc.).

**Enhanced SEO**

WPSSO (Pro version) integrates with [WordPress SEO by Yoast](http://wordpress.org/plugins/wordpress-seo/) and [All in One SEO Pack](http://wordpress.org/plugins/all-in-one-seo-pack/), making sure your custom SEO settings are reflected in the Open Graph, Rich Pin, and Twitter Card meta tags.

**eCommerce Products**

WPSSO (Pro version) also supports [Easy Digital Downloads](http://wordpress.org/plugins/easy-digital-downloads/), [MarketPress - WordPress eCommerce](http://wordpress.org/plugins/wordpress-ecommerce/), [WooCommerce v1 and v2](http://wordpress.org/plugins/woocommerce/), and [WP e-Commerce](http://wordpress.org/plugins/wp-e-commerce/) product pages, creating appropriate meta tags for [Facebook Products](https://developers.facebook.com/docs/payments/product/), [Twitter Product Cards](https://dev.twitter.com/docs/cards/types/product-card) and [Pinterest Rich Pins](http://developers.pinterest.com/rich_pins/), including variations and additional / custom images.

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

= Proven Performance =

WPSSO is fast and coded for performance, making full use of all available caching techniques (persistent / non-persistent object and disk caching). WPSSO loads only the library files and object classes it needs, keeping it small, fast, and yet still able to support a wide range of 3rd party integration features.

<strong><em>How Fast is WPSSO Compared to Other Plugins?</em></strong> Very Fast. A few examples from the [P3 (Plugin Performance Profiler)](http://wordpress.org/plugins/p3-profiler/), using [WP Test Data](http://wptest.io/) and the default settings of a some well known plugins:

<ul>
	<li><strong>0.0105</strong> secs - <strong>WordPress Social Sharing Optimization</strong> (WPSSO) v2.5.2</li>
	<li><strong>0.0117</strong> secs - All in One SEO Pack v2.1.4</li>
	<li><strong>0.0130</strong> secs - MarketPress - WordPress eCommerce v2.9.2.1 (<em>No Products</em>)</li>
	<li><strong>0.0175</strong> secs - NextGEN Facebook (NGFB) v7.5.2</li>
	<li><strong>0.0189</strong> secs - Contact Form 7 v3.7.2</li>
	<li><strong>0.0230</strong> secs - Easy Digital Downloads v1.9.8 (No Products)</li>
	<li><strong>0.0322</strong> secs - WP e-Commerce v3.8.13.3 (<em>No Products</em>)</li>
	<li><strong>0.0393</strong> secs - bbPress v2.5.3 (<em>No Forums or Topics</em>)</li>
	<li><strong>0.0405</strong> secs - WooCommerce v2.1.5 (<em>No Products</em>)</li>
	<li><strong>0.0572</strong> secs - SEO Ultimate v7.6.2</li>
	<li><strong>0.0579</strong> secs - Facebook v1.5.5</li>
	<li><strong>0.0656</strong> secs - BuddyPress v1.9.2 (<em>No Activities</em>)</li>
	<li><strong>0.1051</strong> secs - WordPress SEO by Yoast v1.5.3.3</li>
	<li><strong>0.1980</strong> secs - JetPack by WordPress.com v2.9.2</li>
</ul>

<p><small><em>Tests executed on a VPS with SSDs and 6GB ram, APC opcode/object cache, WordPress v3.8.1, P3 v1.4.1 configured with opcode optimization enabled (improves accuracy).</em></small></p>

= Clean Uninstall =

Try the WPSSO plugin with complete confidence &mdash; when uninstalled, WPSSO removes *all* traces of itself from the database (options, site options, user and post meta, transients, etc.).

= Great Support =

WPSSO support and development is on-going. You can review the [FAQ](http://faq.wpsso.surniaulula.com/) and [Notes](http://notes.wpsso.surniaulula.com/) pages for additional setup information. If you have any suggestions or comments, post them to the [WordPress support forum](http://wordpress.org/support/plugin/wpsso) or the [Pro version support website](http://support.wpsso.surniaulula.com/).

**Follow Surnia Ulula on [Google+](https://plus.google.com/+SurniaUlula?rel=author), [Facebook](https://www.facebook.com/SurniaUlulaCom), and [Twitter](https://twitter.com/surniaululacom), and [YouTube](http://www.youtube.com/user/SurniaUlulaCom)**.

== Installation ==

= Install and Uninstall =

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation-to/install-the-plugin/">Install the Plugin</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation/integration/">Integration Notes</a>
	<ul>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation/integration/buddypress-integration/">BuddyPress Integration</a></li>
	</ul></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation/migrate-from-ngfb/">Migrate from NGFB</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation/uninstall-the-plugin/">Uninstall the Plugin</a></li>
</ul>

= Setup =

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/installation/a-setup-guide/">A Setup Guide</a></li>
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
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/how-does-wpsso-find-detect-select-images/">How does WPSSO find / detect / select images?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/w3c-says-there-is-no-attribute-property/">W3C says “there is no attribute ‘property’”</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-about-google-search-and-google-plus/">What about Google Search and Google Plus?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-features-of-nextgen-gallery-are-supported/">What features of NextGEN Gallery are supported?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/what-is-the-difference-between-the-free-and-pro-versions/">What is the difference between the Free and Pro versions?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-arent-pins-from-my-website-posting-rich/">Why aren’t Pins from my website posting Rich?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-do-my-facebook-shares-have-small-images/">Why do my Facebook shares have small images?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-facebook-play-videos-instead-of-linking-them/">Why does Facebook play videos instead of linking them?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-google-structured-data-testing-tool-show-errors/">Why does Google Structured Data Testing Tool show errors?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-does-wpsso-ignore-some-img-html-tags/">Why does WPSSO ignore some &lt;img/&gt; HTML tags?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-doesnt-facebook-show-the-correct-image/">Why doesn’t Facebook show the correct image?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-dont-my-twitter-cards-show-on-twitter/">Why don’t my Twitter Cards show on Twitter?</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-is-the-open-graph-title-the-same-for-every-webpage/">Why is the Open Graph title the same for every webpage?</a></li>
</ul>

== Other Notes ==

<ul>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/">About Pro Addons</a>
	<ul>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/author-gravatar/">Author Gravatar</a></li>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/easy-digital-downloads/">Easy Digital Downloads</a></li>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/jetpack-photon/">Jetpack Photon</a></li>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/slideshare-vimeo-wistia-youtube-apis/">Slideshare, Vimeo, Wistia, Youtube APIs</a></li>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/addons/woocommerce/">WooCommerce</a></li>
	</ul></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/contact-information/">Contact Information and Feeds</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/debugging-and-problem-solving/">Debugging and Problem Solving</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/">Developer Resources</a>
	<ul>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/constants/">Constants</a></li>
		<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/filters/">Filters</a>
		<ul>
			<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/filters/open-graph/">Open Graph Filters</a></li>
			<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/filters/twitter-card/">Twitter Card Filters</a></li>
			<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/developer/filters/webpage/">Webpage Filters</a></li>
		</ul></li>
	</ul></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/multisite-network-support/">Multisite / Network Support</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/performance-tuning/">Performance Tuning</a></li>
	<li><a href="http://surniaulula.com/codex/plugins/wpsso/notes/working-with-image-attachments/">Working with Image Attachments</a></li>
</ul>

== Screenshots ==

1. Screenshot 1 : An Example Facebook Link Share
2. Screenshot 2 : An Example Facebook Video Share
3. Screenshot 3 : An Example Google+ Link Share
4. Screenshot 4 : An Example Google+ Video Share
5. Screenshot 5 : An Example Google Search Result showing Author Profile Info
6. Screenshot 6 : An Example LinkedIn Share
7. Screenshot 7 : An Example Pinterest Image Pin
8. Screenshot 8 : An Example Pinterest Product Pin
9. Screenshot 9 : An Example Pinterest Product Pin (Zoomed)
10. Screenshot 10 : An Example StumbleUpon Share
11. Screenshot 11 : An Example Tumblr 'Link' Share
12. Screenshot 12 : An Example Tumblr 'Photo' Share
13. Screenshot 13 : An Example Tumblr 'Video' Share
14. Screenshot 14 : An Example Twitter 'Summary' Card
15. Screenshot 15 : An Example Twitter 'Large Image Summary' Card
16. Screenshot 16 : An Example Twitter 'Photo' Card
17. Screenshot 17 : An Example Twitter 'Gallery' Card
18. Screenshot 18 : An Example Twitter 'Product' Card
19. Screenshot 19 : SSO General Settings Page
20. Screenshot 20 : SSO Advanced Settings Page
21. Screenshot 21 : Post / Page SSO Custom Settings

== Changelog ==

= Version 2.5.2 =

* Bugfixes
	* *None*
* Enhancements
	* Renamed the 'About' settings page to 'Read Me'.
	* Added a new 'Setup Guide' settings page with [configuration hints and suggestions](http://surniaulula.com/codex/plugins/wpsso/installation/a-setup-guide/).
	* Added a new 'Welcome' dashboard page, displayed only once, when the options are updated or the plugin is activated.
	
= Version 2.5.0 =

* Bugfixes
	* *None*
* Enhancements
	* Renamed the 'Custom Settings' metabox to 'Social Settings'.
	* Renamed the `$this->p->user` object variable to `$this->p->addons['util']['user']`.
	* Changed several `is_author()` checks to include support for admin side user profile pages.
	* Added an `WpssoUtilUser` addon class that extends `WpssoUser`.
	* Added a 'Gravatar Images for Author Indexes' option on the General settings page.
	* Added a 'Force Default Image on Author Index' option on the General settings page.
	* Added a 'Force Default Video on Author Index' option on the General settings page.
	* Added a 'Show Social Settings on: User Profile' option on the Advanced settings page.
	* Added a `get_author_image()` method to the `WpssoMedia` class.
	* Added a `get_author_object()` method to the `SucomUtil` class.
	* Added the `lib/gpl/admin/user.php` and `lib/gpl/util/user.php` library files.
	* Added the `lib/pro/admin/user.php`, `lib/pro/util/user.php`, and `lib/pro/media/gravatar.php` library files (Pro version).
	* Added an 'Author Gravatar' addon to include Gravatar images in author index pages (Pro version).
	* Added a new 'Social Settings' metabox to the user profile page (Pro version).

== Upgrade Notice ==

= 2.5.2 =

Added a new 'Setup Guide' settings page and 'Welcome' dashboard page (displayed only once, when options are updated or the plugin is activated).

= 2.5.0 =

Added a new 'Social Settings' metabox to the user profile page, and added support for author Gravatar images (Pro version).

