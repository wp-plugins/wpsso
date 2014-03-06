<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoMessages' ) ) {

	class WpssoMessages {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get( $idx = '', $atts = null, $class = '' ) {

			$text = is_array( $atts ) || is_object( $atts ) ? '' : $atts;
			$idx = sanitize_title_with_dashes( $idx );
			if ( strpos( $idx, 'tooltip-' ) !== false && empty( $class ) )
				$class = $this->p->cf['form']['tooltip_class'];	// default tooltip class

			switch ( $idx ) {
				/*
				 * 'Plugin Features' side metabox
				 */
				case ( strpos( $idx, 'tooltip-side-' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'tooltip-side-debug-messages':
							$text = 'Debug code is loaded when the \'Add Hidden Debug Info\' option is checked, or one of the available 
							<a href="http://surniaulula.com/codex/plugins/wpsso/notes/constants/" target="_blank">debugging 
							constants</a> is defined.';
							break;
						case 'tooltip-side-non-persistant-cache':
							$text = $this->p->cf['full'].' saves filtered / rendered content to a non-persistant cache
							(aka <a href="http://codex.wordpress.org/Class_Reference/WP_Object_Cache" target="_blank">WP Object Cache</a>) 
							for re-use within the same page load. You can disable the use of non-persistant cache (not recommended)
							using one of the available <a href="http://surniaulula.com/codex/plugins/wpsso/notes/constants/" 
							target="_blank">constants</a>.';
							break;
						case 'tooltip-side-open-graph-rich-pin':
							$text = 'Open Graph and Rich Pin meta tags are added to the head section of all webpages. 
							You must have a compatible eCommerce plugin installed to add <em>Product</em> Rich Pins, 
							including product prices, images, and other attributes.';
							break;
						case 'tooltip-side-pro-update-check':
							$text = 'When a \'Pro Version Authentication ID\' is entered on the '.$this->p->util->get_admin_url( 'advanced', 
							'Advanced settings page' ).', a check is scheduled every 12 hours to see if a Pro version update is available.';
							break;
						case 'tooltip-side-transient-cache':
							$text = $this->p->cf['full'].' saves Open Graph, Rich Pin, Twitter Card meta tags, and sharing buttons to a persistant
							(aka <a href="http://codex.wordpress.org/Transients_API" target="_blank">Transient</a>) cache for '.
							$this->p->options['plugin_object_cache_exp'].' seconds (default is '.$this->p->opt->get_defaults( 'plugin_object_cache_exp' ).
							' seconds). You can adjust the Transient Cache expiration value from the '.
							$this->p->util->get_admin_url( 'advanced', 'Advanced settings page' ).', or disable it completely using an available
							<a href="http://surniaulula.com/codex/plugins/wpsso/notes/constants/" target="_blank">constant</a>.';
							break;
						case 'tooltip-side-custom-post-meta':
							$text = 'The Custom Post Meta feature adds an SSO Custom Settings metabox to the Post and Page editing pages.
							Custom values van be entered for Open Graph, Rich Pin, and Twitter Card meta tags, along with custom social sharing
							text and meta tag validation tools.';
							break;
						case 'tooltip-side-wp-locale-language':
							$text = $this->p->cf['full_pro'].' can use the WordPress locale to dynmically adjust the language for the Open Graph 
							and Rich Pin meta tags'.( empty( $this->p->is_avail['ssb'] ) ? '' : ', along with the Google, Facebook, and Twitter social sharing buttons' ).
							'. If your website or webpages are available in multiple languages, this can be a useful feature.
							Uncheck to ignore the WordPress locale and use all default language settings.'; 
							break;
						case 'tooltip-side-twitter-cards':
							$text = 'Twitter Cards extend the standard Open Graph and Rich Pin meta tags with content-specific information for image galleries, 
							photographs, eCommerce products, etc. Twitter Cards are displayed differently on Twitter, either online or from mobile Twitter 
							clients, allowing you to better feature your content. The Twitter Cards addon can be enabled from the '.
							$this->p->util->get_admin_url( 'general', 'General settings page' ).'.';
							break;
						case 'tooltip-side-slideshare-api':
							$text = 'If the embedded Slideshare Presentations option on the '.
							$this->p->util->get_admin_url( 'advanced', 'Advanced settings page' ).' is checked, '.
							$this->p->cf['full_pro'].' will load an integration addon for Slideshare to detect embedded Slideshare 
							presentations, and retrieve information using Slideshare\'s oEmbed API (media dimentions, preview image, etc).';
							break;
						case 'tooltip-side-vimeo-video-api':
							$text = 'If the embedded Vimeo Videos option on the '.
							$this->p->util->get_admin_url( 'advanced', 'Advanced settings page' ).' is checked, '.
							$this->p->cf['full_pro'].' will detect embedded Vimeo 
							videos, and retrieve information using Vimeo\'s oEmbed API (media dimentions, preview image, etc).';
							break;
						case 'tooltip-side-wistia-video-api':
							$text = 'If the embedded Wistia Videos option on the '.
							$this->p->util->get_admin_url( 'advanced', 'Advanced settings page' ).' is checked, '.
							$this->p->cf['full_pro'].' will load an integration addon for Wistia to detect embedded Wistia 
							videos, and retrieve information using Wistia\'s oEmbed API (media dimentions, preview image, etc).';
							break;
						case 'tooltip-side-youtube-video-playlist-api':
							$text = 'If the embedded Youtube Videos and Playlists option on the '.
							$this->p->util->get_admin_url( 'advanced', 'Advanced settings page' ).' is checked, '.
							$this->p->cf['full_pro'].' will detect embedded Youtube 
							videos and playlists, and retrieve information using Youtube\'s XML and oEmbed APIs
							(media dimentions, preview image, etc).';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_side', $text, $idx );
							break;
					}
					break;

				/*
				 * Post Meta settings
				 */
				case ( strpos( $idx, 'tooltip-postmeta-' ) !== false ? true : false ):
					$ptn = empty( $atts['ptn'] ) ? 'Post' : $atts['ptn'];
					switch ( $idx ) {
						/*
						 * 'Webpage Header' settings
						 */
						 case 'tooltip-postmeta-og_art_section':
							$text = 'A custom topic for this '.$ptn.', different from the default 
							Website Topic chosen in the General Settings.';
						 	break;
						 case 'tooltip-postmeta-og_title':
							$text = 'A custom title for the Open Graph, Rich Pin, Twitter Card meta tags (all Twitter Card formats), 
							and possibly the Pinterest, Tumblr, and Twitter sharing caption / text, depending on some option 
							settings. The default title value is refreshed when the (draft or published) '.$ptn.' is saved.';
						 	break;
						 case 'tooltip-postmeta-og_desc':
							$text = 'A custom description for the Open Graph, Rich Pin meta tags, and the fallback description 
							for all other meta tags and social sharing buttons.
							The default description value is based on the content, or excerpt if one is available, 
							and is refreshed when the (draft or published) '.$ptn.' is saved.
							Update and save this description to change the default value of all other meta tag and 
							social sharing button descriptions.';
						 	break;
						 case 'tooltip-postmeta-meta_desc':
							$text = 'A custom description for the Google Search description meta tag.
							The default description value is refreshed when the '.$ptn.' is saved.';
						 	break;
						 case 'tooltip-postmeta-tc_desc':
							$text = 'A custom description for the Twitter Card description meta tag (all Twitter Card formats).
							The default description value is refreshed when the '.$ptn.' is saved.';
						 	break;
						 case 'tooltip-postmeta-og_img_id':
							$text = 'A custom Image ID to include first in the Open Graph, Rich Pin, 
							and \'Large Image Summary\' Twitter Card meta tags, along with the Pinterest 
							and Tumblr social sharing buttons.';
						 	break;
						 case 'tooltip-postmeta-og_img_url':
							$text = 'A custom image URL, instead of an Image ID, to include first in the Open Graph, Rich Pin, 
							and \'Large Image Summary\' Twitter Card meta tags. Please make sure your custom image
							is large enough, or it may be ignored by the social website(s). Facebook recommends 
							an image size of 1200x630, 600x315 as a minimum, and will ignore any images less than 200x200.';
						 	break;
						 case 'tooltip-postmeta-og_vid_url':
							$text = 'A custom Video URL to include first in the Open Graph, Rich Pin, and \'Player\' Twitter Card meta tags'.
							( empty( $this->p->is_avail['ssb'] ) ? '' : ', along with the Tumblr social sharing button' ).
							'. If the URL is from Youtube, Vimeo or Wistia, an API connection will be made to retrieve the preferred 
							sharing URL, video dimensions, and video preview image. The '.
							$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_custom', 'Video URL Custom Field' ).
							' Advanced option also allows a 3rd-party to provide a Video URL value for this option.';
						 	break;
						 case 'tooltip-postmeta-og_img_max':
							$text = 'The maximum number of images to include in the Open Graph meta tags for this '.$ptn.'.';
						 	break;
						 case 'tooltip-postmeta-og_vid_max':
							$text = 'The maximum number of embedded videos to include in the Open Graph meta tags for this '.$ptn.'.';
						 	break;
						 case 'tooltip-postmeta-sharing_url':
							$text = 'A custom sharing URL used in the Open Graph, Rich Pin meta tags and social sharing buttons.
							The default sharing URL may be influenced by settings from supported SEO plugins.
							Please make sure any custom URL you enter here is functional and redirects correctly.';
						 	break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_postmeta', $text, $idx, $atts );
							break;
					}
					break;

				/*
				 * Open Graph settings
				 */
				case ( strpos( $idx, 'tooltip-og_' ) !== false ? true : false ):
					switch ( $idx ) {
						/*
						 * 'Image and Video' settings
						 */
						case 'tooltip-og_img_dimensions':
							$text = 'The dimension of images used in the Open Graph / Rich Pin meta tags. The width and height must be 
							greater than '.$this->p->cf['head']['min_img_dim'].'x'.$this->p->cf['head']['min_img_dim'].', 
							and preferably smaller than 1500x1500
							(the defaults is '.$this->p->opt->get_defaults( 'og_img_width' ).'x'.$this->p->opt->get_defaults( 'og_img_height' ).', '.
							( $this->p->opt->get_defaults( 'og_img_crop' ) == 0 ? 'un' : '' ).'cropped). 
							Facebook recommends an image size of 1200x630, 600x315 as a minimum, and will ignore any images smaller than 200x200.
							Your original images in the WordPress Media Library and/or NextGEN Gallery should be larger than your chosen image dimensions.';
							break;
						case 'tooltip-og_def_img_id':
							$text = 'The ID number and location of your default image (example: 123). The <em>Default Image ID</em> 
							will be used as a fallback for Posts and Pages that do not have any images <em>featured</em>, 
							<em>attached</em>, or in their content. The Image ID number for images in the 
							WordPress Media Library can be found in the URL when editing an image (post=123 in the URL, for example). 
							The NextGEN Gallery Image IDs are easier to find -- it\'s the number in the first column when viewing a Gallery.';
							break;
						case 'tooltip-og_def_img_url':
							$text = 'You can also specify a <em>Default Image URL</em> (including the http:// prefix) instead of choosing a 
							<em>Default Image ID</em>. This allows you to use an image outside of a managed collection (WordPress Media Library or NextGEN Gallery). 
							The image should be at least '.$this->p->cf['head']['min_img_dim'].'x'.$this->p->cf['head']['min_img_dim'].' or more in width and height. 
							If both the <em>Default Image ID</em> and <em>Default Image URL</em> are defined, the <em>Default Image ID</em>
							will take precedence.';
							break;
						case 'tooltip-og_def_img_on_index':
							$text = 'Check this option if you would like to use the default image on index webpages (<strong>non-static</strong> 
							homepage, archives, categories, author, etc.). If you leave this unchecked, '.$this->p->cf['full'].' will attempt to 
							use image(s) from the first entry on the webpage (default is checked).';
							break;
						case 'tooltip-og_def_img_on_search':
							$text = 'Check this option if you would like to use the default image on search result webpages as well (default is checked).';
							break;
						case 'tooltip-og_ngg_tags':
							$text = 'If the <em>featured</em> image in a Post or Page is from a NextGEN Gallery, then add that image\'s tags to the 
							Open Graph / Rich Pin tag list (default is unchecked).';
							break;
						case 'tooltip-og_img_max':
							$text = 'The maximum number of images to list in the Open Graph / Rich Pin meta property tags -- this includes 
							the <em>featured</em> or <em>attached</em> images, and any images found in the Post or Page content.
							If you select \'0\', then no images will be listed in the Open Graph / Rich Pin meta tags.';
							break;
						case 'tooltip-og_vid_max':
							$text = 'The maximum number of videos, found in the Post or Page content, to include in the Open Graph / Rich Pin meta property tags. 
							If you select \'0\', then no videos will be listed in the Open Graph / Rich Pin meta tags.';
							break;
						case 'tooltip-og_vid_https':
							$text = 'Use an HTTPS connection whenever possible to retrieve information about videos from YouTube, Vimeo, Wistia, etc. (default is checked).';
							break;
						/*
						 * 'Title and Description' settings
						 */
						case 'tooltip-og_art_section':
							$text = 'The topic that best describes the Posts and Pages on your website.
							This name will be used in the \'article:section\' Open Graph / Rich Pin meta tag. 
							Select \'[none]\' if you prefer to exclude the \'article:section\' meta tag.
							The Pro version also allows you to select a custom Topic for each individual Post and Page.';
							break;
						case 'tooltip-og_site_name':
							$text = 'By default, the Site Title from the <a href="'.get_admin_url( null, 'options-general.php' ).'">WordPress General Settings</a>
							page is used for the Open Graph, Rich Pin site name (\'og:site_name\' meta tag). You may override the default Site Title value here.';
							break;
						case 'tooltip-og_site_description':
							$text = 'By default, the Tagline in the <a href="'.get_admin_url( null, 'options-general.php' ).'">WordPress General Settings</a>
							page is used as a description for the index home page, and as fallback for the Open Graph, Rich Pin 
							description field (\'og:description\' meta tag). You may override that default value here.';
							break;
						case 'tooltip-og_title_sep':
							$text = 'One or more characters used to separate values (category parent names, page numbers, etc.) 
							within the Open Graph / Rich Pin title string (default is \''.$this->p->opt->get_defaults( 'og_title_sep' ).'\').';
							break;
						case 'tooltip-og_title_len':
							$text = 'The maximum length of text used in the Open Graph / Rich Pin title tag 
							(default is '.$this->p->opt->get_defaults( 'og_title_len' ).' characters).';
							break;
						case 'tooltip-og_desc_len':
							$text = 'The maximum length of text used in the Open Graph / Rich Pin description tag. 
							The length should be at least '.$this->p->cf['head']['min_desc_len'].' characters or more, and the
							default is '.$this->p->opt->get_defaults( 'og_desc_len' ).' characters.';
							break;
						case 'tooltip-og_page_title_tag':
							$text = 'Add the title of the <em>Page</em> to the Open Graph / Rich Pin article tags and Hashtag list (default is unchecked). 
							If the <em>Add Page Ancestor Tags</em> option is checked, all the titles of the ancestor Pages will be added as well. 
							This option works well if the title of your Pages are short (one or two words) and subject-oriented.';
							break;
						case 'tooltip-og_page_parent_tags':
							$text = 'Add the WordPress tags from the <em>Page</em> ancestors (parent, parent of parent, etc.) 
							to the Open Graph / Rich Pin article tags and Hashtag list (default is unchecked).';
							break;
						case 'tooltip-og_desc_hashtags':
							$text = 'The maximum number of tag names (not their slugs), converted to hashtags, to include in the 
							Open Graph / Rich Pin description, tweet text, and social captions.
							Each tag name is converted to lowercase with any whitespaces removed. 
							Select \'0\' to disable the additiona of hashtags.';
							break;
						case 'tooltip-og_desc_strip':
							$text = 'For a Page or Post <em>without</em> an excerpt, if this option is checked, 
							the plugin will ignore all text until the first html paragraph tag in the content. 
							If an excerpt exists, then this option is ignored, and the complete text of that 
							excerpt is used instead.';
							break;
						/*
						 * 'Authorship' settings
						 */
						case 'tooltip-og_author_field':
							$text = 'Select a profile field to use in the \'article:author\' Open Graph / Rich Pin meta tag(s).
							The preferred and default value is the author\'s Facebook URL (recommended setting). 
							See the Publisher settings Google tab bellow for an <em>Author Link URL</em> option, which is used by Google Search.';
							break;
						case 'tooltip-og_author_fallback':
							$text = 'If the <em>Author Profile URL</em> (and the <em>Author Link URL</em> in the Google Settings below) 
							is not a valid URL, then '.$this->p->cf['full'].' can fallback to using the author index on this 
							website (\''.trailingslashit( site_url() ).'author/username\' for example). 
							Uncheck this option to disable the fallback feature (default is unchecked).';
							break;
						case 'tooltip-og_def_author_id':
							$text = 'A default author for webpages <em>missing authorship information</em> (for example, an index webpage without posts). 
							If you have several authors on your website, you should probably leave this option set to <em>[none]</em> (the default).';
							break;
						case 'tooltip-og_def_author_on_index':
							$text = 'Check this option if you would like to force the <em>Default Author</em> on index webpages 
							(<strong>non-static</strong> homepage, archives, categories, author, etc.). If this option is checked, 
							index webpages will be labeled as a an \'article\' with authorship attributed to the <em>Default Author</em>
							(default is unchecked). If the <em>Default Author</em> is <em>[none]</em>, then the index webpages will be 
							labeled as a \'website\'.';
							break;
						case 'tooltip-og_def_author_on_search':
							$text = 'Check this option if you would like to force the <em>Default Author</em> on search result webpages as well.
							If this option is checked, search results will be labeled as a an \'article\' with authorship
							attributed to the <em>Default Author </em> (default is unchecked).';
							break;
						case 'tooltip-og_publisher_url':
							$text = 'The URL of your website\'s social page (usually a Facebook page). 
							For example, the Publisher Page URL for <a href="http://surniaulula.com/" target="_blank">Surnia Ulula</a> 
							is <a href="https://www.facebook.com/SurniaUlulaCom" target="_blank">https://www.facebook.com/SurniaUlulaCom</a>.
							The Publisher Page URL will be included on <em>article</em> type webpages (not indexes).
							See the Google Settings below for a <em>Publisher Link URL</em> for Google.';
							break;
						/*
						 * 'Meta Tag List' settings
						 */
						case 'tooltip-og_empty_tags':
							$text = 'Include meta property tags of type og:* without any content (default is unchecked).';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_og', $text, $idx );
							break;
					}
					break;

				/*
				 * Advanced plugin settings
				 */
				case ( strpos( $idx, 'tooltip-plugin_' ) !== false ? true : false ):
					switch ( $idx ) {
						/*
						 * 'Activate and Update' settings
						 */
						case 'tooltip-plugin_tid':
							if ( is_multisite() && ! empty( $this->p->site_options['plugin_tid:use'] ) && $this->p->site_options['plugin_tid:use'] == 'force' )
								$text = 'The Authentication ID value has been locked in the Network Admin settings.';
							elseif ( $this->p->is_avail['aop'] )
								$text = 'After purchasing a Pro version license, an email will be sent to you with a unique Authentication ID 
								and installation instructions. Enter the Authentication ID here to activate the Pro version features.';
							else
								$text = 'After purchasing the Pro version, an email will be sent to you with a unique Authentication ID 
								and installation instructions. Enter this Authentication ID here, and after saving the changes, an update 
								for '.$this->p->cf['full'].' will appear on the <a href="'.get_admin_url( null, 'update-core.php' ).'">WordPress 
								Updates</a> page. Update the \''.$this->p->cf['full'].'\' plugin to download and activate the Pro version.';
							break;
						case 'tooltip-plugin_preserve':
							$text = 'Check this option if you would like to preserve all '.$this->p->cf['full'].
							' settings when you <em>uninstall</em> the plugin (default is unchecked).';
							break;
						case 'tooltip-plugin_debug':
							$text = 'Include hidden debug information with the Open Graph meta tags (default is unchecked).';
							break;
						/*
						 * 'Content and Filters' settings
						 */
						case 'tooltip-plugin_filter_content':
							$text = 'Apply the standard WordPress \'the_content\' filter to render the content text (default is checked).
							This renders all shortcodes, and allows '.$this->p->cf['full'].' to detect images and 
							embedded videos that may be provided by these.';
							break;
						case 'tooltip-plugin_filter_excerpt':
							$text = 'Apply the standard WordPress \'get_the_excerpt\' filter to render the excerpt text (default is unchecked).
							Check this option if you use shortcodes in your excerpt, for example.';
							break;
						case 'tooltip-plugin_filter_lang':
							return self::get( 'tooltip-side-wp-locale-language' );
							break;
						case 'tooltip-plugin_shortcodes':
							$text = 'Enable the '.$this->p->cf['full'].' shortcode features (default is checked).';
							break;
						case 'tooltip-plugin_widgets':
							$text = 'Enable the '.$this->p->cf['full'].' widget features (default is checked).';
							break;
						case 'tooltip-plugin_auto_img_resize':
							$text = 'Automatically generate missing or incorrect image sizes for previously uploaded images in the 
							WordPress Media Library (default is checked).';
							break;
						case 'tooltip-plugin_ignore_small_img':
							$text = $this->p->cf['full'].' will attempt to include images from the img html tags it finds in the content.
							The img html tags must have a width and height attribute, and their size must be equal or larger than the 
							<em>Image Dimensions</em> you\'ve entered on the General Settings page. 
							Uncheck this option to include smaller images from the content, Media Library, etc.
							<strong>Unchecking this option is not advised</strong> - 
							images that are much too small for some social websites may be included in your meta tags.';
							break;
						case 'tooltip-plugin_embedded_media':
							$text = 'Check the Post and Page content, along with the Custom Settings, for embedded media URLs 
							from supported media providers (Youtube, Wistia, etc.). If a supported URL is found, an API connection 
							to the provider will be made to retrieve information about the media (preview image, flash player url,
							oembed player url, video width / height, etc.).';
							break;
						/*
						 * 'Custom Settings' settings
						 */
						case 'tooltip-plugin_add_to':
							$text = 'The Custom Settings metabox, which allows you to enter custom Open Graph values (among other options), 
							is available on the Posts, Pages, Media, and Product admin pages by default. 
							If your theme (or another plugin) supports additional custom post types, and you would like to 
							include the Custom Settings metabox on their admin pages, check the appropriate option(s) here.';
							break;
						case 'tooltip-plugin_cf_vid_url':
							$text = 'If your theme (or another plugin) provides a custom field for embedded video URLs, 
							you may enter that custom field name here. If a custom field matching that name is found, 
							it\'s value will be used for the Video URL in the '.$this->p->cf['menu'].' Custom Settings
							for Posts and Pages. The default value is "'.$this->p->opt->get_defaults( 'plugin_cf_vid_url' ).'".';
							break;
						/*
						 * 'File and Object Cache' settings
						 */
						case 'tooltip-plugin_object_cache_exp':
							$text = $this->p->cf['full'].' saves filtered / rendered content to a non-persistant cache 
							(aka <a href="http://codex.wordpress.org/Class_Reference/WP_Object_Cache" target="_blank">WP Object Cache</a>), 
							and Open Graph, Rich Pin, Twitter Card meta tags, and sharing buttons to a persistant (aka 
							<a href="http://codex.wordpress.org/Transients_API" target="_blank">Transient</a>) cache. 
							The default is '.$this->p->opt->get_defaults( 'plugin_object_cache_exp' ).' seconds, and the minimum value is 
							1 second (such a low value is not recommended).';
							break;
						case 'tooltip-plugin_file_cache_hrs':
							$text = $this->p->cf['full'].' can save social sharing JavaScript and images to a cache folder, 
							providing URLs to these cached files instead of the originals. 
							A value of 0 Hours (the default) disables the file caching feature. 
							If your hosting infrastructure performs reasonably well, this option can improve page load times significantly.
							All social sharing images and javascripts will be cached, except for the Facebook JavaScript SDK, 
							which does not work correctly when cached.';
							break;
						case 'tooltip-plugin_verify_certs':
							$text = 'Enable verification of peer SSL certificates when fetching content to be cached using HTTPS. 
							The PHP \'curl\' function will use the '.WPSSO_CURL_CAINFO.' certificate file by default. 
							You can define a WPSSO_CURL_CAINFO constant in your wp-config.php file to use an alternate certificate file.';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_plugin', $text, $idx );
							break;
					}
					break;

				/*
				 * Publisher 'Facebook' settings
				 */
				case ( strpos( $idx, 'tooltip-fb_' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'tooltip-fb_admins':
							$text = 'The <em>Facebook Admin(s)</em> user names are used by Facebook to allow access to 
							<a href="https://developers.facebook.com/docs/insights/" target="_blank">Facebook Insight</a> data.
							Note that these are <em>user</em> account names, not Facebook <em>page</em> names.
							<p>Enter one or more Facebook user names, separated with commas. 
							When viewing your own Facebook wall, your user name is located in the URL 
							(example: https://www.facebook.com/<strong>user_name</strong>). 
							Enter only the user user name(s), not the URL(s).</p>
							<a href="https://www.facebook.com/settings?tab=account&section=username&view" target="_blank">Update 
							your user name in the Facebook General Account Settings</a>.';
							break;
						case 'tooltip-fb_app_id':
							$text = 'If you have a <a href="https://developers.facebook.com/apps" target="_blank">Facebook Application</a> 
							ID for your website, enter it here. The Facebook Application ID will appear in your webpage meta tags,
							and is used by Facebook to allow access to <a href="https://developers.facebook.com/docs/insights/" 
							target="_blank">Facebook Insight</a> data for <em>accounts associated with that Application ID</em>.';
							break;
						case 'tooltip-fb_lang':
							$text = 'The default language of your website content, used in the Open Graph and Rich Pin meta tags. 
							The Pro version can also use the WordPress locale to adjust the language value dynamically
							(useful for websites with multilingual content).';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_fb', $text, $idx );
							break;
					}
					break;

				/*
				 * Publisher 'Google' settings
				 */
				case ( strpos( $idx, 'tooltip-google_' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'tooltip-google_desc_len':
							$text = 'The maximum length of text used for the Google Search description meta tag.
							The length should be at least '.$this->p->cf['head']['min_desc_len'].' characters or more 
							(the default is '.$this->p->opt->get_defaults( 'meta_desc_len' ).' characters).';
							break;
						case 'tooltip-google_author_field':
							$text = $this->p->cf['full'].' can include an <em>author</em> and <em>publisher</em> link in your webpage headers.
							These are not Open Graph / Rich Pin meta property tags - they are used primarily by Google\'s search engine 
							to associate Google+ profiles with search results.';
							break;
						case 'tooltip-google_def_author_id':
							$text = 'A default author for webpages missing authorship information (for example, an index webpage without posts). 
							If you have several authors on your website, you should probably leave this option set to <em>[none]</em> (the default).
							This option is similar to the Open Graph / Rich Pin <em>Default Author</em>, except that it\'s applied to the Link meta tag instead.';
							break;
						case 'tooltip-google_def_author_on_index':
							$text = 'Check this option if you would like to force the <em>Default Author</em> on index webpages 
							(<strong>non-static</strong> homepage, archives, categories, author, etc.).';
							break;
						case 'tooltip-google_def_author_on_search':
							$text = 'Check this option if you would like to force the <em>Default Author</em> on search result webpages as well.';
							break;
						case 'tooltip-google_publisher_url':
							$text = 'If you have a <a href="http://www.google.com/+/business/" target="_blank">Google+ business page for your website</a>, 
							you may use it\'s URL as the Publisher Link. 
							For example, the Publisher Link URL for <a href="http://surniaulula.com/" target="_blank">Surnia Ulula</a> 
							is <a href="https://plus.google.com/u/1/103457833348046432604/posts" target="_blank">https://plus.google.com/u/1/103457833348046432604/posts</a>.
							The <em>Publisher Link URL</em> may take precedence over the <em>Author Link URL</em> in Google\'s search results.';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_google', $text, $idx );
							break;
					}
					break;

				/*
				 * Publisher 'Twitter Card' settings
				 */
				case ( strpos( $idx, 'tooltip-tc_' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'tooltip-tc_enable':
							$text = 'Add Twitter Card meta tags to all webpage headers.
							<strong>Your website must be "authorized" by Twitter for each type of Twitter Card you support</strong>. 
							See the FAQ entry titled <a href="http://surniaulula.com/codex/plugins/wpsso/faq/why-dont-my-twitter-cards-show-on-twitter/" 
							target="_blank">Why don’t my Twitter Cards show on Twitter?</a> for more information on Twitter\'s 
							authorization process.';
							break;
						case 'tooltip-tc_desc_len':
							$text = 'The maximum length of text used for the Twitter Card description.
							The length should be at least '.$this->p->cf['head']['min_desc_len'].' characters or more 
							(the default is '.$this->p->opt->get_defaults( 'tc_desc_len' ).' characters).';
							break;
						case 'tooltip-tc_site':
							$text = 'The Twitter username for your website and / or company (not your personal Twitter username).
							As an example, the Twitter username for <a href="http://surniaulula.com/" target="_blank">Surnia Ulula</a> 
							is <a href="https://twitter.com/surniaululacom" target="_blank">@surniaululacom</a>.';
							break;
						case 'tooltip-tc_sum_dimensions':
							$card = 'sum';
							$text = 'The dimension of content images provided for the
							<a href="https://dev.twitter.com/docs/cards/types/summary-card" target="_blank">Summary Card</a>
							(should be at least 120x120, larger than 60x60, and less than 1MB).
							The default image dimensions are '.$this->p->opt->get_defaults( 'tc_'.$card.'_width' ).'x'.
							$this->p->opt->get_defaults( 'tc_'.$card.'_height' ).', '.
							( $this->p->opt->get_defaults( 'tc_'.$card.'_crop' ) ? '' : 'un' ).'cropped.';
							break;
						case 'tooltip-tc_lrgimg_dimensions':
							$card = 'lrgimg';
							$text = 'The dimension of Post Meta, Featured or Attached images provided for the
							<a href="https://dev.twitter.com/docs/cards/large-image-summary-card" target="_blank">Large Image Summary Card</a>
							(must be larger than 280x150 and less than 1MB).
							The default image dimensions are '.$this->p->opt->get_defaults( 'tc_'.$card.'_width' ).'x'.
							$this->p->opt->get_defaults( 'tc_'.$card.'_height' ).', '.
							( $this->p->opt->get_defaults( 'tc_'.$card.'_crop' ) ? '' : 'un' ).'cropped.';
							break;
						case 'tooltip-tc_photo_dimensions':
							$card = 'photo';
							$text = 'The dimension of ImageBrowser or Attachment Page images provided for the 
							<a href="https://dev.twitter.com/docs/cards/types/photo-card" target="_blank">Photo Card</a> 
							(should be at least 560x750 and less than 1MB).
							The default image dimensions are '.$this->p->opt->get_defaults( 'tc_'.$card.'_width' ).'x'.
							$this->p->opt->get_defaults( 'tc_'.$card.'_height' ).', '.
							( $this->p->opt->get_defaults( 'tc_'.$card.'_crop' ) ? '' : 'un' ).'cropped.';
							break;
						case 'tooltip-tc_gal_minimum':
							$text = 'The minimum number of images found in a gallery to qualify for the
							<a href="https://dev.twitter.com/docs/cards/types/gallery-card" target="_blank">Gallery Card</a>.';
							break;
						case 'tooltip-tc_gal_dimensions':
							$card = 'gal';
							$text = 'The dimension of gallery images provided for the
							<a href="https://dev.twitter.com/docs/cards/types/gallery-card" target="_blank">Gallery Card</a>.
							The default image dimensions are '.$this->p->opt->get_defaults( 'tc_'.$card.'_width' ).'x'.
							$this->p->opt->get_defaults( 'tc_'.$card.'_height' ).', '.
							( $this->p->opt->get_defaults( 'tc_'.$card.'_crop' ) ? '' : 'un' ).'cropped.';
							break;
						case 'tooltip-tc_prod_dimensions':
							$card = 'prod';
							$text = 'The dimension of a <em>featured product image</em> for the
							<a href="https://dev.twitter.com/docs/cards/types/product-card" target="_blank">Product Card</a>.
							The product card requires an image of size 160 x 160 or greater. A square (aka cropped) image is better, 
							but Twitter can crop/resize oddly shaped images to fit, as long as both dimensions are greater 
							than or equal to 160 pixels.
							The default image dimensions are '.$this->p->opt->get_defaults( 'tc_'.$card.'_width' ).'x'.
							$this->p->opt->get_defaults( 'tc_'.$card.'_height' ).', '.
							( $this->p->opt->get_defaults( 'tc_'.$card.'_crop' ) ? '' : 'un' ).'cropped.';
							break;
						case 'tooltip-tc_prod_defaults':
							$text = 'The <em>Product</em> Twitter Card needs a <strong>minimum of two product attributes</strong>.
							The first attribute will be the product price, and if your product has additional attribute fields associated with it 
							(weight, size, color, etc), these will be included in the <em>Product</em> Card as well (maximum of 4 attributes). 
							<strong>If your product does not have additional attributes beyond its price</strong>, then this default second 
							attribute label and value will be used. 
							You may modify both the Label <em>and</em> Value for whatever is most appropriate for your website and/or products.
							Some examples: Promotion / Free Shipping, Ships from / Hong Kong, Made in / China, etc.';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_tc', $text, $idx );
							break;
					}
					break;

				/*
				 * Publisher 'Pinterest' (Rich Pin) settings
				 */
				case ( strpos( $idx, 'tooltip-rp_' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'tooltip-rp_author_name':
							$text = 'Pinterest ignores Facebook-style <em>Author Profile URLs</em> in the \'article:author\'
							Open Graph / Rich Pin meta tags. An additional \'article:author\' meta tag may be included 
							when the Pinterest crawler is detected. Select a User\'s Name Format to include for the Pinterest
							crawler, or \'none\' to disable this feature (the default and recommended value is \'Display Name\').';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_rp', $text, $idx );
							break;
					}
					break;

				/*
				 * 'Profile Contact Methods' settings
				 */
				case 'tooltip-custom-cm-field-name':
					$text = '<strong>You should not modify the contact field names unless you have a specific reason to do so.</strong>
					As an example, to match the contact field name of a theme or other plugin, you might change \'gplus\' to \'googleplus\'.
					If you change the Facebook or Google+ field names, please make sure to update the Open Graph 
					<em>Author Profile URL</em> and Google <em>Author Link URL</em> options in the '.
					$this->p->util->get_admin_url( 'general', 'General Settings' ).' as well.';
					break;
				case 'tooltip-wp-cm-field-name':
					$text = 'The built-in WordPress contact field names cannot be changed.';
					break;

				/*
				 * Misc informational messages
				 */
				case 'pro-feature-msg':
					if ( $this->p->is_avail['aop'] == true )
						$text = '<p class="pro-feature-msg"><a href="'.$this->p->cf['url']['purchase'].'" target="_blank">Purchase 
						additional licence(s) to enable Pro version features</p>';
					else
						$text = '<p class="pro-feature-msg"><a href="'.$this->p->cf['url']['purchase'].'" target="_blank">Upgrade 
						to the Pro version to enable the following features</a></p>';
					break;
				case 'pro-activate-nag':
					// in multisite, only show the activation message on our own plugin pages
					if ( ! is_multisite() || ( is_multisite() && preg_match( '/^.*\?page='.$this->p->cf['lca'].'-/', $_SERVER['REQUEST_URI'] ) ) ) {
						$url = $this->p->util->get_admin_url( 'advanced' );
						$text = '<p>The '.$this->p->cf['full'].' Authentication ID option value is empty.<br/>
						To activate Pro version features, and allow the plugin to authenticate itself for updates,<br/>
						<a href="'.$url.'">enter the unique Authenticaton ID you receive following your purchase
						on the Advanced Settings page</a>.</p>';
					}
					break;
				case 'pro-advert-nag':
					$text .= '<center><p style="font-size:1.15em;">What do you think of '.$this->p->cf['full'].'?</p>
					<p style="font-size:1.05em;">Please take a moment <a href="'.$this->p->cf['url']['review'].'" target="_blank">to share your opinion,                     
					and rate the plugin on wordpress.org</a>...</p><p class="sig">Thank you!</p></center>';
					/*
					$text .= '<p style="font-size:1.1em;">Have you considered encouraging the continued development and support of '.
					$this->p->cf['full'].' by purchasing the Pro version?</p>
					<p>'.$this->p->cf['full_pro'].' supports several types of Twitter Cards, including the <em>Gallery</em>, <em>Player</em> and <em>Product</em> 
					Cards, allows you to customize the meta tags of individual Posts and Pages, and integrates with a growing list of 3rd party plugins.</p>
					<p><strong>Improve your social presence on Facebook, Twitter, Pinterest, LinkedIn, etc. by providing these social websites
					complete and accurate meta tags, correctly sized images, information on your products and embedded videos</strong>.</p>
					<p style="font-size:1.05em;">Have the Pro version running in just two minutes!<br/>
					<a href="'.$this->p->cf['url']['purchase'].'" target="_blank">Purchase a '.$this->p->cf['full_pro'].' license right now</a>.</p>
					';
					*/
					break;
				case 'side-purchase':
					$text = '<p>Developing and supporting the '.$this->p->cf['full'].' plugin takes most of my work days (and week-ends).
					If you compare this plugin with others, I think you\'ll find that the result was worth all the hard work and long hours.
					If you would like to show your appreciation, and access the full range of features this plugin offers, please purchase ';
					if ( $this->p->is_avail['aop'] == true )
						$text .= 'a Pro version license.</p>';
					else $text .= 'the Pro version.</p>';
					break;
				case 'side-thankyou':
					$text = '<p>Thank you for your purchase. I hope '.$this->p->cf['full'].' will exceed all of your expectations for many years to come.</p>';
					break;
				case 'side-help':
					$text = '<p>Individual option boxes (like this one) can be opened / closed by clicking on their title bar, 
					moved and re-ordered by dragging them, and removed / added from the <em>Screen Options</em> tab (top-right).
					Values in multiple tabs can be edited before clicking the \'Save All Changes\' button.</p>';
					if ( $this->p->is_avail['aop'] == true )
						$text .= '<p><strong>Need help with the Pro version?</strong>
						Review the <a href="'.$this->p->cf['url']['faq'].'" target="_blank">FAQs</a>, 
						the <a href="'.$this->p->cf['url']['notes'].'" target="_blank">Notes</a>,
						and / or <a href="'.$this->p->cf['url']['pro_ticket'].'" target="_blank">Submit a new Support Ticket</a>.</p>';
					else
						$text .= '<p><strong>Need help with the GPL version?</strong>
						Review the <a href="'.$this->p->cf['url']['faq'].'" target="_blank">FAQs</a>, 
						the <a href="'.$this->p->cf['url']['notes'].'" target="_blank">Notes</a>, 
						and / or visit the <a href="'.$this->p->cf['url']['support'].'" target="_blank">Support Forum</a> on WordPress.org.</p>';
					break;
				case 'tid-info':
					$text = '<p>'.$this->p->cf['full'].' must be active in order to check for Pro version updates.
					If you de-activate the plugin, update checks will be made against WordPress.org, and update notices will be for the GPL version. 
					Always update the Pro version when it is active. If you accidentally re-install the GPL version, your Authentication ID
					will always allow you to upgrade back to the Pro version easily.</p>';
					break;
				case 'taglist-info':
					$text = '<p>'.$this->p->cf['full'].' will add the following Facebook and Open Graph meta tags to your webpages. 
					If your theme or another plugin already generates one or more of these meta tags, you may uncheck them here to 
					prevent duplicates from being added (for example, the "description" meta tag is unchecked by default if any 
					known SEO plugin was detected).</p>';
					break;
				case 'cm-info':
					$text = '<p>The following options allow you to customize the contact field names and labels shown on the
					<a href="'.get_admin_url( null, 'profile.php' ).'">user profile page</a>.
					'.$this->p->cf['full'].' uses the Facebook, Google+ and Twitter contact field values for Open Graph and Twitter Card meta tags 
					(along with the Twitter social sharing button).
					<strong>You should not modify the <em>Contact Field Name</em> unless you have a very good reason to do so.</strong>
					The <em>Profile Contact Label</em> on the other hand, is for <strong>display purposes only</strong>, and its text can be changed as you wish.
					Although the following contact methods may be shown on user profile pages, your theme is responsible for displaying these
					contact fields in the appropriate template locations (see <a href="http://codex.wordpress.org/Function_Reference/get_the_author_meta" 
					target="_blank">get_the_author_meta()</a> for examples).</p>
					<p><center><strong>DO NOT ENTER YOUR CONTACT INFORMATION HERE &ndash; THESE ARE CONTACT FIELD LABELS ONLY</strong><br/>
					(enter your contact information on the <a href="'.get_admin_url( null, 'profile.php' ).'">user profile page</a>).</p>';
					break;
				case 'sharing-buttons-info':
					$text = '<p>The following social sharing buttons can be added to the content, excerpt, and/or enabled within the '.
					$this->p->cf['menu'].' Social Sharing widget as well (see the <a href="'.get_admin_url( null, 'widgets.php' ).'">widgets admin page</a>).</p>';
					break;
				case 'pub-pinterest-info':
					$text = '<p>Pinterest uses Open Graph meta tags for their Rich Pins (see the Open Graph Settings above).
					The following settings allow you to manage a few Pinterest-specific options.</p>';
					break;
				/*
				 * Other messages
				 */
				default:
					$text = apply_filters( $this->p->cf['lca'].'_messages', $text, $idx );
					break;

			}
			if ( strpos( $idx, 'tooltip-' ) !== false && ! empty( $text ) )
				return '<img src="'.WPSSO_URLPATH.'images/question-mark.png" width="14" height="14" class="'.
					$class.'" alt="'.esc_attr( $text ).'" />';
			else return $text;
		}
	}
}

?>
