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
							$text = 'Debug code is loaded when the \'Add Hidden Debug HTML Messages\' option is checked, or one of the available 
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
						case 'tooltip-side-post-social-settings':
							$text = 'The Post Social Settings feature adds a Social Settings metabox to the Post, Page, and custom post type editing pages.
							Custom descriptions and images can be entered for Open Graph, Rich Pin, and Twitter Card meta tags.';
							break;
						case 'tooltip-side-user-social-settings':
							$text = 'The User Social Settings feature adds a Social Settings metabox to the user profile pages.
							Custom descriptions and images can be entered for Open Graph, Rich Pin, and Twitter Card meta tags.';
							break;
						case 'tooltip-side-publisher-language':
							$text = $this->p->cf['full_pro'].' can use the WordPress locale to select the correct language for the Open Graph / Rich Pin meta tags'.
							( empty( $this->p->is_avail['ssb'] ) ? '' : ', along with the Google, Facebook, and Twitter social sharing buttons' ).
							'. If your website is available in multiple languages, this can be a useful feature.';
							break;
						case 'tooltip-side-twitter-cards':
							$text = 'Twitter Cards extend the standard Open Graph and Rich Pin meta tags with content-specific information for image galleries, 
							photographs, eCommerce products, etc. Twitter Cards are displayed differently on Twitter, either online or from mobile Twitter 
							clients, allowing you to better feature your content. The Twitter Cards addon can be enabled from the '.
							$this->p->util->get_admin_url( 'general', 'General settings page' ).'.';
							break;
						case 'tooltip-side-author-gravatar':
							$text = 'Include Gravatar image URLs in the meta tags for author index webpages. Enable or disable this option from the '.
							$this->p->util->get_admin_url( 'general#sucom-tab_og_author', 'General settings page' ).'.';
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
				 * User Meta settings
				 */
				case ( strpos( $idx, 'tooltip-user-' ) !== false ? true : false ):
					$ptn = empty( $atts['ptn'] ) ? 'Post' : $atts['ptn'];
					switch ( $idx ) {
						/*
						 * 'Header Meta Tags' settings
						 */
						 case 'tooltip-user-og_title':
							$text = 'A custom title for the Open Graph, Rich Pin, Twitter Card meta tags (all Twitter Card formats), 
							and possibly the Pinterest, Tumblr, and Twitter sharing caption / text, depending on some option 
							settings.';
						 	break;
						 case 'tooltip-user-og_desc':
							$text = 'A custom description for the Open Graph, Rich Pin meta tags, and the fallback description 
							for all other meta tags '.( empty( $this->p->is_avail['ssb'] ) ? '' : ' and social sharing buttons' ).'. '.
							'The default description value is based on the biographical info, if one is available.
							Update and save this description to change the default value of all other description fields.';
						 	break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_tooltip_user', $text, $idx, $atts );
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
						 * 'Header Meta Tags' settings
						 */
						 case 'tooltip-postmeta-og_art_section':
							$text = 'A custom topic, different from the default \'Article Topic\' selected in the General settings.
							The Open Graph \'og:type\' meta tag must be an \'article\' to enable this option.
							The value will be used in the \'article:section\' Open Graph / Rich Pin meta tag.
							Select \'[none]\' if you prefer to exclude the \'article:section\' meta tag.';
						 	break;
						 case 'tooltip-postmeta-og_title':
							$text = 'A custom title for the Open Graph, Rich Pin, Twitter Card meta tags (all Twitter Card formats), 
							and possibly the Pinterest, Tumblr, and Twitter sharing caption / text, depending on some option 
							settings. The default title value is refreshed when the (draft or published) '.$ptn.' is saved.';
						 	break;
						 case 'tooltip-postmeta-og_desc':
							$text = 'A custom description for the Open Graph, Rich Pin meta tags, and the fallback description 
							for all other meta tags '.( empty( $this->p->is_avail['ssb'] ) ? '' : ' and social sharing buttons' ).'. '.
							'The default description value is based on the content, or excerpt if one is available, 
							and is refreshed when the (draft or published) '.$ptn.' is saved.
							Update and save this description to change the default value of all other description fields.';
						 	break;
						 case 'tooltip-postmeta-seo_desc':
							$text = 'A custom description for the Google Search / SEO description meta tag.
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
							$text = 'A custom image URL (instead of an Image ID) to include first in the Open Graph, Rich Pin, 
							and \'Large Image Summary\' Twitter Card meta tags. Please make sure your custom image
							is large enough, or it may be ignored by the social website(s). Facebook recommends 
							an image size of 1200x630, 600x315 as a minimum, and will ignore any images less than 200x200.
							This field is disabled if an Image ID has been specified.';
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
							$text = 'The image dimensions used in the Open Graph / Rich Pin meta tags (defaults is '.
							$this->p->opt->get_defaults( 'og_img_width' ).'x'.$this->p->opt->get_defaults( 'og_img_height' ).' '.
							( $this->p->opt->get_defaults( 'og_img_crop' ) == 0 ? 'un' : '' ).'cropped). 
							Facebook recommends 1200x630 cropped, and 600x315 as a minimum.
							<strong>1200x1200 cropped provides the greatest comptibility with all social websites 
							(Facebook, Google+, etc.)</strong>. Note that original images in the WordPress Media Library and/or 
							NextGEN Gallery should be larger than your chosen image dimensions.';
							break;
						case 'tooltip-og_def_img_id':
							$text = 'The ID number and location of your default image (example: 123). The Default Image ID 
							will be used as a <strong>fallback for Posts and Pages that do not have any images</strong> <em>featured</em>, 
							<em>attached</em>, or &lt;img/&gt; HTML tags in their content. The Image ID number for images in the 
							WordPress Media Library can be found in the URL when editing an image (post=123 in the URL, for example). 
							The NextGEN Gallery Image IDs are easier to find -- it\'s the number in the first column when viewing a Gallery.';
							break;
						case 'tooltip-og_def_img_url':
							$text = 'You can enter a Default Image URL (including the http:// prefix) instead of choosing a 
							Default Image ID (if a Default Image ID is specified, the Default Image URL option will be disabled).
							Using an image URL allow you to use an image outside of a managed collection (WordPress Media Library or NextGEN Gallery). 
							The image should be at least '.$this->p->cf['head']['min_img_dim'].'x'.$this->p->cf['head']['min_img_dim'].' 
							or more in width and height (1200x1200px is recommended).
							The Default Image ID or URL is used as a <strong>fallback for Posts and Pages that do not have any images</strong> 
							<em>featured</em>, <em>attached</em>, or &lt;img/&gt; HTML tags in their content.';
							break;
						case 'tooltip-og_def_img_on_index':
							$text = 'Check this option to force the default image on index webpages 
							(<strong>non-static</strong> homepage, archives, categories). 
							If this option is <em>checked</em>, but a Default Image ID or URL has not been defined, 
							then <strong>no image will be included in the meta tags</strong>.
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use image(s) from the first entry on the webpage (default is checked).';
							break;
						case 'tooltip-og_def_img_on_author':
							$text = 'Check this option to force the default image on author index webpages.
							If this option is <em>checked</em>, but a Default Image ID or URL has not been defined, 
							then <strong>no image will be included in the meta tags</strong>. 
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use image(s) returned in the search results (default is unchecked).';
							break;
						case 'tooltip-og_def_img_on_search':
							$text = 'Check this option to force the default image on search results.
							If this option is <em>checked</em>, but a Default Image ID or URL has not been defined, 
							then <strong>no image will be included in the meta tags</strong>. 
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use image(s) returned in the search results (default is unchecked).';
							break;
						case 'tooltip-og_def_vid_url':
							$text = 'The Default Video URL is used as a <strong>fallback value for Posts and Pages 
							that do not have any videos</strong> in their content. Do not specify a Default Video URL
							<strong>unless you want to include video information in all your Posts and Pages</strong>.';
							break;
						case 'tooltip-og_def_vid_on_index':
							$text = 'Check this option to force the default video on index webpages 
							(<strong>non-static</strong> homepage, archives, categories). 
							If this option is <em>checked</em>, but a Default Video URL has not been defined, then 
							<strong>no video will be included in the meta tags</strong> (this is usually preferred).
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use video(s) from the first entry on the webpage (default is checked).';
							break;
						case 'tooltip-og_def_vid_on_author':
							$text = 'Check this option to force the default video on author index webpages.
							If this option is <em>checked</em>, but a Default Video URL has not been defined, 
							then <strong>no video will be included in the meta tags</strong>.
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use video(s) returned in the search results (default is unchecked).';
							break;
						case 'tooltip-og_def_vid_on_search':
							$text = 'Check this option to force the default video on search results.
							If this option is <em>checked</em>, but a Default Video URL has not been defined, 
							then <strong>no video will be included in the meta tags</strong>.
							If the option is <em>unchecked</em>, then '.$this->p->cf['full'].' 
							will use video(s) returned in the search results (default is unchecked).';
							break;
						case 'tooltip-og_ngg_tags':
							$text = 'If the <em>featured</em> image in a Post or Page is from a NextGEN Gallery, then add that image\'s tags to the 
							Open Graph / Rich Pin tag list (default is unchecked).';
							break;
						case 'tooltip-og_img_max':
							$text = 'The maximum number of images to list in the Open Graph / Rich Pin meta tags -- 
							this includes the <em>featured</em> or <em>attached</em> images, and any images found in the Post or Page content.
							If you select \'0\', then no images will be listed in the Open Graph / Rich Pin meta tags (<strong>not recommended</strong>).
							If no images are listed in your meta tags, then social websites may choose an unsuitable image from your webpage
							(including headers, sidebars, etc.).';
							break;
						case 'tooltip-og_vid_max':
							$text = 'The maximum number of videos, found in the Post or Page content, to include in the Open Graph / Rich Pin meta tags. 
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
							This value will be used in the \'article:section\' Open Graph / Rich Pin meta tag. 
							Select \'[none]\' if you prefer to exclude the \'article:section\' meta tag.
							The Pro version also allows you to select a custom Topic for each individual Post and Page.';
							break;
						case 'tooltip-og_site_name':
							$text = 'The WordPress Site Title is used for the Open Graph / Rich Pin site name (og:site_name) meta tag. 
							You may override <a href="'.get_admin_url( null, 'options-general.php' ).'">the default WordPress Site Title</a> value here.';
							break;
						case 'tooltip-og_site_description':
							$text = 'The WordPress Tagline is used as a description for the <em>index</em> (non-static) home page, 
							and as a fallback for the Open Graph / Rich Pin description (og:description) meta tag. 
							You may override <a href="'.get_admin_url( null, 'options-general.php' ).'">the default WordPress Tagline</a> value here
							to provide a longer and more complete description of your website.';
							break;
						case 'tooltip-og_title_sep':
							$text = 'One or more characters used to separate values (category parent names, page numbers, etc.) within the 
							Open Graph / Rich Pin title string (the default is a hyphen \''.$this->p->opt->get_defaults( 'og_title_sep' ).'\'
							character).';
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
							If the Add Page Ancestor Tags option is checked, all the titles of the ancestor Pages will be added as well. 
							This option works well if the title of your Pages are short (one or two words) and subject-oriented.';
							break;
						case 'tooltip-og_page_parent_tags':
							$text = 'Add the WordPress tags from the <em>Page</em> ancestors (parent, parent of parent, etc.) 
							to the Open Graph / Rich Pin article tags and Hashtag list (default is unchecked).';
							break;
						case 'tooltip-og_desc_hashtags':
							$text = 'The maximum number of tag names (converted to hashtags) to include in the 
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
							$text = 'Select which field to use from the author\'s profile for the 
							Open Graph / Rich Pin \'article:author\' meta tag(s).
							The preferred (and default) setting is the Facebook URL field.';
							break;
						case 'tooltip-og_author_fallback':
							$text = 'If the Author Profile URL (and the Author Link URL in the Google Settings below) 
							is not a valid URL, then '.$this->p->cf['full'].' can fallback to using the author index on this 
							website (\''.trailingslashit( site_url() ).'author/username\' for example). 
							Uncheck this option to disable the fallback feature (default is unchecked).';
							break;
						case 'tooltip-og_def_author_id':
							$text = 'A default author for webpages <em>missing authorship information</em> (for example, an index webpage without posts). 
							If you have several authors on your website, you should probably leave this option set to <em>[none]</em> (the default).';
							break;
						case 'tooltip-og_def_author_on_index':
							$text = 'Check this option if you would like to force the Default Author on index webpages 
							(<strong>non-static</strong> homepage, archives, categories, author, etc.). If this option is checked, 
							index webpages will be labeled as a an \'article\' with authorship attributed to the Default Author
							(default is unchecked). If the Default Author is <em>[none]</em>, then the index webpages will be 
							labeled as a \'website\'.';
							break;
						case 'tooltip-og_def_author_on_search':
							$text = 'Check this option if you would like to force the Default Author on search result webpages as well.
							If this option is checked, search results will be labeled as a an \'article\' with authorship
							attributed to the Default Author (default is unchecked).';
							break;
						case 'tooltip-og_author_gravatar':
							$text = 'Check this option to include Gravatar image URLs in the meta tags for author index webpages.
							If the \'Force Default Image on Author Index\' option is also enabled (on the previous \'Images\' tab), 
							that option will take precedence over this one.';
							break;
						case 'tooltip-og_publisher_url':
							$text = 'The URL of your website\'s social page (usually a Facebook page). 
							For example, the Publisher Page URL for <a href="http://surniaulula.com/" target="_blank">Surnia Ulula</a> 
							is <a href="https://www.facebook.com/SurniaUlulaCom" target="_blank">https://www.facebook.com/SurniaUlulaCom</a>.
							The Publisher Page URL will be included on <em>article</em> type webpages (not indexes).
							See the Google Settings below for a Publisher Link URL for Google.';
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
						case 'tooltip-plugin_tid_network':
							$text = 'After purchasing a Pro version license, an email is sent with a unique Authentication ID and installation instructions. 
							Enter the Authentication ID here, to define a value for all sites within the network, or enter the Authentication ID(s) 
							individually on each site\'s Advanced settings page. 
							<strong>Note that the default site / blog must be licensed to allow for plugin updates</strong>.';

							if ( ! $this->p->is_avail['aop'] )
								$text = 'When the default site / blog is licensed, an update for '.$this->p->cf['full'].
								' will appear on the <a href="'.get_admin_url( null, 'update-core.php' ).'">WordPress Updates</a> page. 
								Update the plugin to download and activate the Pro version.';
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
							$text = $this->p->cf['full_pro'].' can use the WordPress locale to select the correct language for the Open Graph / Rich Pin meta tags'.
							( empty( $this->p->is_avail['ssb'] ) ? '' : ', along with the Google, Facebook, and Twitter social sharing buttons' ).
							'. If your website is available in multiple languages, this can be a useful feature.
							Uncheck this option to ignore the WordPress locale and always use the configured language.'; 
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
							$text = $this->p->cf['full'].' will retrieve image URLs from HTML tags in the <strong>content</strong>.
							The &amp;amp;lt;img/&amp;amp;gt; HTML tags must have a width and height attribute, 
							and their size must be equal to (or larger) than the 
							Image Dimensions you\'ve entered on the General settings page. 
							Uncheck this option to include smaller images from the content.
							<strong>Unchecking this option is not advised</strong> - 
							images that are too small for some social websites may be included in your meta tags.';
							break;
						case 'tooltip-plugin_embedded_media':
							$text = 'Check the Post and Page content, along with the custom Social Settings, for embedded media URLs 
							from supported media providers (Youtube, Wistia, etc.). If a supported URL is found, an API connection 
							to the provider will be made to retrieve information about the media (preview image, flash player url,
							oembed player url, video width / height, etc.).';
							break;
						/*
						 * 'Social Settings' settings
						 */
						case 'tooltip-plugin_add_to':
							$text = 'The Social Settings metabox, which allows you to enter custom Open Graph values (among other options), 
							is available on the User, Posts, Pages, Media, and Product admin pages by default. 
							If your theme (or another plugin) supports additional custom post types, and you would like to 
							include the Social Settings metabox on their admin pages, check the appropriate option(s) here.';
							break;
						case 'tooltip-plugin_cf_vid_url':
							$text = 'If your theme (or another plugin) provides a custom field for embedded video URLs, 
							you may enter its custom field name (aka, the "post meta option name") here.
							If a custom field matching that name is found, it\'s value will be used for the Video URL in the
							Social Settings metabox for Posts and Pages. 
							The default value is \''.$this->p->opt->get_defaults( 'plugin_cf_vid_url' ).'\'.';
							break;
						/*
						 * 'File and Object Cache' settings
						 */
						case 'tooltip-plugin_object_cache_exp':
							$text = $this->p->cf['full'].' saves filtered and rendered content to a non-persistant cache 
							(aka <a href="http://codex.wordpress.org/Class_Reference/WP_Object_Cache" target="_blank">WP Object Cache</a>), 
							and Open Graph / Rich Pin, Twitter Card meta tags to a persistant 
							(aka <a href="http://codex.wordpress.org/Transients_API" target="_blank">Transient</a>) cache. 
							The default is '.$this->p->opt->get_defaults( 'plugin_object_cache_exp' ).' seconds, 
							and the minimum value is 1 second (such a low value is not recommended).';
							break;
						case 'tooltip-plugin_file_cache_hrs':
							$text = $this->p->cf['full'].' can save social sharing JavaScript and images to a cache folder, 
							providing URLs to these cached files instead of the originals. 
							A value of 0 hours (the default) disables the file caching feature. 
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
							$text = 'The Facebook Admin(s) user names are used by Facebook to allow access to 
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
							$text = 'The maximum length of text used for the Google Search / SEO description meta tag.
							The length should be at least '.$this->p->cf['head']['min_desc_len'].' characters or more 
							(the default is '.$this->p->opt->get_defaults( 'seo_desc_len' ).' characters).';
							break;
						case 'tooltip-google_author_name':
							$text = 'Select an Author Name Format for the "author" meta tag, or \'none\' to disable this feature 
							(the recommended value is \'Display Name\'). Facebook uses the "author" meta tag value to credit the 
							author on timeline shares, but the <strong>Facebook Debugger will show a warning</strong>
							&mdash; thus it is disabled by default. Now that you know about the false warning from the Facebook
							Debugger, you should set this option to \'Display Name\'. ;-)';
							break;
						case 'tooltip-google_author_field':
							$text = $this->p->cf['full'].' can include an <em>author</em> and <em>publisher</em> link in your webpage headers.
							These are not Open Graph / Rich Pin meta property tags &mdash; they are used primarily by Google\'s search engine 
							to associate Google+ profiles with search results. Select which field to use from the author\'s profile
							for the <em>author</em> link tag.';
							break;
						case 'tooltip-google_def_author_id':
							$text = 'A default author for webpages missing authorship information (for example, an index webpage without posts). 
							If you have several authors on your website, you should probably leave this option set to <em>[none]</em> (the default).
							This option is similar to the Open Graph / Rich Pin Default Author, except that it\'s applied to the Link meta tag instead.';
							break;
						case 'tooltip-google_def_author_on_index':
							$text = 'Check this option if you would like to force the Default Author on index webpages 
							(<strong>non-static</strong> homepage, archives, categories, author, etc.).';
							break;
						case 'tooltip-google_def_author_on_search':
							$text = 'Check this option if you would like to force the Default Author on search result webpages as well.';
							break;
						case 'tooltip-google_publisher_url':
							$text = 'If you have a <a href="http://www.google.com/+/business/" target="_blank">Google+ business page for your website</a>, 
							you may use it\'s URL as the Publisher Link URL. For example, the Publisher Link URL for 
							<a href="http://surniaulula.com/" target="_blank">Surnia Ulula</a> is 
							<a href="https://plus.google.com/+SurniaUlula/" target="_blank">https://plus.google.com/+SurniaUlula/</a>.
							The Publisher Link URL may take precedence over the Author Link URL in Google\'s search results.';
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
							$text = 'Pinterest ignores Facebook-style Author Profile URLs in the \'article:author\'
							Open Graph / Rich Pin meta tags. An <em>additional</em> \'article:author\' meta tag may be included 
							when the Pinterest crawler is detected. Select an Author Name Format, or \'[none]\' to disable this feature 
							(the default and recommended value is \'Display Name\').';
							break;
						case 'tooltip-rp_img_dimensions':
							$text = 'The image dimensions specifically for Rich Pin meta tags when the Pinterest crawler is detected
							(defaults is '.$this->p->opt->get_defaults( 'rp_img_width' ).'x'.$this->p->opt->get_defaults( 'rp_img_height' ).' '.
							( $this->p->opt->get_defaults( 'rp_img_crop' ) == 0 ? 'un' : '' ).'cropped). 
							Images in the Open Graph meta tags are usually cropped, where-as images on Pinterest often look
							better in their original aspect ratio (aka uncropped).
							Note that original images in the WordPress Media Library and/or 
							NextGEN Gallery must be larger than your chosen image dimensions.';
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
					Author Profile URL and Google Author Link URL options in the '.
					$this->p->util->get_admin_url( 'general', 'General settings' ).' as well.';
					break;
				case 'tooltip-wp-cm-field-name':
					$text = 'The built-in WordPress contact field names cannot be changed.';
					break;

				/*
				 * Misc informational messages
				 */
				case ( strpos( $idx, 'info-' ) !== false ? true : false ):
					switch ( $idx ) {
						case 'info-tid':
							$text = '<p>'.$this->p->cf['full'].' must be active in order to check for Pro version updates.
							If you de-activate the plugin, update checks will be made against WordPress.org, and update notices will be for the Free version. 
							Always update the Pro version when it is active. If you accidentally re-install the Free version, your Authentication ID
							will always allow you to upgrade back to the Pro version easily.</p>';
							break;
						case 'info-review':
							$text .= '<p>If you appreciate the features or quality of this plugin, and/or the support we provide, please take a moment to
							<a href="'.$this->p->cf['url']['review'].'" target="_blank">rate the '.WpssoConfig::get_config( 'full' ).' plugin on WordPress.org</a>.</p>
							<p><strong>Your rating will help other WordPress users find higher quality and better supported plugins</strong> &mdash; 
							<em>and encourage us to keep improving '.WpssoConfig::get_config( 'full' ).' as well!</em> :)</p>
							<p>Thank you,</p>
							<p class="sig">js.</p>';
							break;
						case 'info-pub-pinterest':
							$text = '<p>Pinterest uses Open Graph meta tags for their Rich Pins.
							These options allow you to manage and/or override some Pinterest-specific Open Graph settings.
							Please note that if you use a full-page caching plugin, or front-end caching service, 
							it should detect the Pinterest crawler user-agent and bypass the cache, 
							so that different meta tags can be provided to the crawler 
							(for example, look for a "<em>User-Agent Exclusion Pattern</em>" option and add "Pinterest/" to that list).</p>';
							break;
						case 'info-taglist':
							$text = '<p>'.$this->p->cf['full'].' will add the following Google / SEO, Facebook, Open Graph, Schema, 
							and Twitter Card HTML tags to the <code>head</code> section of your webpages. 
							If your theme or another plugin already generates one or more of these HTML tags, you can uncheck them here 
							to prevent duplicates from being added (as an example, the "meta name description" HTML tag is automatically 
							unchecked if a known SEO plugin is detected).</p>';
							break;
						case 'info-cm':
							$text = '<p>The following options allow you to customize the contact field names and labels shown on the
							<a href="'.get_admin_url( null, 'profile.php' ).'">user profile page</a>.
							'.$this->p->cf['full'].' uses the Facebook, Google+ and Twitter contact field values for Open Graph and Twitter Card meta tags 
							(along with the Twitter social sharing button).
							<strong>You should not modify the Contact Field Name unless you have a very good reason to do so.</strong>
							The Profile Contact Label on the other hand, is for <strong>display purposes only</strong>, and its text can be changed as you wish.
							Although the following contact methods may be shown on user profile pages, your theme is responsible for displaying these
							contact fields in the appropriate template locations (see <a href="http://codex.wordpress.org/Function_Reference/get_the_author_meta" 
							target="_blank">get_the_author_meta()</a> for examples).</p>
							<p><center><strong>DO NOT ENTER YOUR CONTACT INFORMATION HERE &ndash; THESE ARE CONTACT FIELD LABELS ONLY</strong><br/>
							(enter your contact information on the <a href="'.get_admin_url( null, 'profile.php' ).'">user profile page</a>).</p>';
							break;
						case 'info-sharing-include':
							$text = '<p>The buttons enabled bellow (along with those in the widget) can be included or excluded from specific webpage types.
							This does not apply to the shortcode and function buttons, which are displayed (or not) based on their parameter settings.</p>';
							break;
						/*
						 * Other settings
						 */
						default:
							$text = apply_filters( $this->p->cf['lca'].'_messages_info', $text, $idx );
							break;
					}
					break;

				case 'pro-feature-msg':
					if ( $this->p->is_avail['aop'] == true )
						$text = '<p class="pro-feature-msg"><a href="'.$this->p->cf['url']['purchase'].'" 
						target="_blank">Purchase additional licence(s) to enable Pro version features and options</p>';
					else
						$text = '<p class="pro-feature-msg"><a href="'.$this->p->cf['url']['purchase'].'" 
						target="_blank">Upgrade to the Pro version to enable the following options</a></p>';
					break;
				case 'pro-activate-nag':
					// in multisite, only show the activation message on our own plugin pages
					if ( ! is_multisite() || ( is_multisite() && preg_match( '/^.*\?page='.$this->p->cf['lca'].'-/', $_SERVER['REQUEST_URI'] ) ) ) {
						$url = $this->p->util->get_admin_url( 'advanced' );
						$text = '<p>The '.$this->p->cf['full'].' Authentication ID option value is empty.<br/>
						To enable Pro version features, and allow the plugin to authenticate itself for future updates,<br/>
						<a href="'.$url.'">please enter the unique Authenticaton ID you received on the '.
						$this->p->cf['menu'].' Advanced settings page</a>.</p>';
					}
					break;
				case 'side-purchase':
					$text = '<p>The Pro version can be purchased and '.( $this->p->is_avail['aop'] == true ? 'licensed' : 'upgraded' ).
					' within a few <em>seconds</em> following your purchase. Pro version licenses do not expire, and there are 
					no recurring or yearly fees for updates and support. Do you have any questions or concerns about licensing? 
					<a href="'.$this->p->cf['url']['pro_ticket'].'" target="_blank">Submit a new support ticket</a> 
					and we will be happy to assist you.';
					break;
				case 'side-help':
					$text = '<p>Individual option boxes (like this one) can be opened / closed by clicking on their title bar, 
					moved and re-ordered by dragging them, and removed / added from the <em>Screen Options</em> tab (top-right).
					Values in multiple tabs can be edited before clicking the \'Save All Changes\' button.</p>';
					if ( $this->p->is_avail['aop'] == true )
						$text .= '<p><strong>Need help with the Pro version?</strong>
						Review the <a href="'.$this->p->cf['url']['faq'].'" target="_blank">FAQs</a>, 
						the <a href="'.$this->p->cf['url']['notes'].'" target="_blank">Notes</a>,
						and / or <a href="'.$this->p->cf['url']['pro_ticket'].'" target="_blank">submit a new support ticket</a>.</p>';
					else
						$text .= '<p><strong>Need help with the Free version?</strong>
						Review the <a href="'.$this->p->cf['url']['faq'].'" target="_blank">FAQs</a>, 
						the <a href="'.$this->p->cf['url']['notes'].'" target="_blank">Notes</a>, 
						and / or visit the <a href="'.$this->p->cf['url']['support'].'" target="_blank">Support Forum</a> on WordPress.org.</p>';
					break;
				/*
				 * Other messages
				 */
				default:
					$text = apply_filters( $this->p->cf['lca'].'_messages', $text, $idx );
					break;

			}
			if ( is_array( $atts ) && ! empty( $atts['is_locale'] ) )
				$text .= ' This option is localized &mdash; you may change the WordPress admin locale with 
				<a href="http://wordpress.org/plugins/polylang/" target="_blank">Polylang</a>,
				<a href="http://wordpress.org/plugins/wp-native-dashboard/" target="_blank">WP Native Dashboard</a>, 
				etc., to define alternate values for different languages.';

			if ( strpos( $idx, 'tooltip-' ) !== false && ! empty( $text ) )
				return '<img src="'.WPSSO_URLPATH.'images/question-mark.png" width="14" height="14" class="'.
					$class.'" alt="'.esc_attr( $text ).'" />';
			else return $text;
		}
	}
}

?>
