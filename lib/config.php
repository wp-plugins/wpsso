<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoConfig' ) ) {

	class WpssoConfig {

		private static $cf = array(
			'version' => '1.22.1',		// plugin version
			'lca' => 'wpsso',		// lowercase acronym
			'cca' => 'Wpsso',		// camelcase acronym
			'uca' => 'WPSSO',		// uppercase acronym
			'slug' => 'wpsso',
			'menu' => 'SSO',		// menu item label
			'full' => 'WPSSO',		// full plugin name
			'full_pro' => 'WPSSO Pro',
			'update_hours' => 12,		// check for pro updates
			'cache' => array(
				'file' => true,
				'object' => true,
				'transient' => true,
			),
			'lib' => array(			// libraries
				'setting' => array (
					'contact' => 'Contact Methods',
				),
				'submenu' => array (
					'general' => 'General',
					'advanced' => 'Advanced',
					'about' => 'About',
				),
				'site_submenu' => array(
					'network' => 'Network',
				),
				'pro' => array(
					'seo' => array(
						'aioseop' => 'All in One SEO Pack',
						'seou' => 'SEO Ultimate',
						'wpseo' => 'WordPress SEO',
					),
					'ecom' => array(
						'woocommerce' => 'WooCommerce',
						'marketpress' => 'MarketPress',
						'wpecommerce' => 'WP e-Commerce',
					),
					'forum' => array(
						'bbpress' => 'bbPress',
					),
					'social' => array(
						'buddypress' => 'BuddyPress',
					),
					'media' => array(
						'ngg' => 'NextGEN Gallery',
						'photon' => 'Jetpack Photon',
						'wistia' => 'Wistia Video API',
					),
				),
			),
			'opt' => array(				// options
				'version' => '222',
				'defaults' => array(
					'meta_desc_len' => 156,
					'link_author_field' => '',
					'link_def_author_id' => 0,
					'link_def_author_on_index' => 0,
					'link_def_author_on_search' => 0,
					'link_publisher_url' => '',
					'fb_admins' => '',
					'fb_app_id' => '',
					'fb_lang' => 'en_US',
					'og_site_name' => '',
					'og_site_description' => '',
					'og_publisher_url' => '',
					'og_art_section' => '',
					'og_img_width' => 1200,
					'og_img_height' => 630,
					'og_img_crop' => 1,
					'og_img_resize' => 1,
					'og_img_max' => 1,
					'og_vid_max' => 1,
					'og_vid_https' => 1,
					'og_def_img_id_pre' => 'wp',
					'og_def_img_id' => '',
					'og_def_img_url' => '',
					'og_def_img_on_index' => 1,
					'og_def_img_on_search' => 1,
					'og_def_author_id' => 0,
					'og_def_author_on_index' => 0,
					'og_def_author_on_search' => 0,
					'og_ngg_tags' => 0,
					'og_page_parent_tags' => 0,
					'og_page_title_tag' => 0,
					'og_author_field' => '',
					'og_author_fallback' => 0,
					'og_title_sep' => '-',
					'og_title_len' => 70,
					'og_desc_len' => 300,
					'og_desc_hashtags' => 0,
					'og_desc_strip' => 0,
					'og_empty_tags' => 0,
					'tc_enable' => 0,
					'tc_site' => '',
					'tc_desc_len' => 200,
					'tc_gal_min' => 4,
					'tc_gal_size' => 'wpsso-medium',
					'tc_photo_size' => 'wpsso-large',
					'tc_large_size' => 'wpsso-medium',
					'tc_sum_size' => 'wpsso-thumbnail',
					'tc_prod_size' => 'wpsso-medium',
					'tc_prod_def_l2' => 'Location',
					'tc_prod_def_d2' => 'Unknown',
					'inc_description' => 0,
					'inc_fb:admins' => 1,
					'inc_fb:app_id' => 1,
					'inc_og:locale' => 1,
					'inc_og:site_name' => 1,
					'inc_og:description' => 1,
					'inc_og:title' => 1,
					'inc_og:type' => 1,
					'inc_og:url' => 1,
					'inc_og:image' => 1,
					'inc_og:image:secure_url' => 1,
					'inc_og:image:width' => 1,
					'inc_og:image:height' => 1,
					'inc_og:video' => 1,
					'inc_og:video:secure_url' => 1,
					'inc_og:video:width' => 1,
					'inc_og:video:height' => 1,
					'inc_og:video:type' => 1,
					'inc_article:author' => 1,
					'inc_article:publisher' => 1,
					'inc_article:published_time' => 1,
					'inc_article:modified_time' => 1,
					'inc_article:section' => 1,
					'inc_article:tag' => 1,
					'inc_product:price:amount' => 1,
					'inc_product:price:currency' => 1,
					'inc_product:availability' => 1,
					'inc_twitter:card' => 1,
					'inc_twitter:creator' => 1,
					'inc_twitter:site' => 1,
					'inc_twitter:title' => 1,
					'inc_twitter:description' => 1,
					'inc_twitter:image' => 1,
					'inc_twitter:image:width' => 1,
					'inc_twitter:image:height' => 1,
					'inc_twitter:image0' => 1,
					'inc_twitter:image1' => 1,
					'inc_twitter:image2' => 1,
					'inc_twitter:image3' => 1,
					'inc_twitter:player' => 1,
					'inc_twitter:player:width' => 1,
					'inc_twitter:player:height' => 1,
					'inc_twitter:data1' => 1,
					'inc_twitter:label1' => 1,
					'inc_twitter:data2' => 1,
					'inc_twitter:label2' => 1,
					'inc_twitter:data3' => 1,
					'inc_twitter:label3' => 1,
					'inc_twitter:data4' => 1,
					'inc_twitter:label4' => 1,
					'options_version' => '',
					'plugin_version' => '',
					'plugin_tid' => '',
					'plugin_preserve' => 0,
					'plugin_debug' => 0,
					'plugin_filter_content' => 1,
					'plugin_filter_excerpt' => 0,
					'plugin_shortcode_wpsso' => 0,
					'plugin_ignore_small_img' => 1,
					'plugin_wistia_api' => 1,
					'plugin_add_to_post' => 1,
					'plugin_add_to_page' => 1,
					'plugin_add_to_attachment' => 1,
					'plugin_verify_certs' => 0,
					'plugin_file_cache_hrs' => 0,
					'plugin_object_cache_exp' => 3600,
					'plugin_cm_fb_name' => 'facebook', 
					'plugin_cm_fb_label' => 'Facebook URL', 
					'plugin_cm_fb_enabled' => 1,
					'plugin_cm_gp_name' => 'gplus', 
					'plugin_cm_gp_label' => 'Google+ URL', 
					'plugin_cm_gp_enabled' => 1,
					'plugin_cm_linkedin_name' => 'linkedin', 
					'plugin_cm_linkedin_label' => 'LinkedIn URL', 
					'plugin_cm_linkedin_enabled' => 0,
					'plugin_cm_pin_name' => 'pinterest', 
					'plugin_cm_pin_label' => 'Pinterest URL', 
					'plugin_cm_pin_enabled' => 0,
					'plugin_cm_tumblr_name' => 'tumblr', 
					'plugin_cm_tumblr_label' => 'Tumblr URL', 
					'plugin_cm_tumblr_enabled' => 0,
					'plugin_cm_twitter_name' => 'twitter', 
					'plugin_cm_twitter_label' => 'Twitter @username', 
					'plugin_cm_twitter_enabled' => 1,
					'plugin_cm_yt_name' => 'youtube', 
					'plugin_cm_yt_label' => 'YouTube Channel URL', 
					'plugin_cm_yt_enabled' => 0,
					'plugin_cm_skype_name' => 'skype', 
					'plugin_cm_skype_label' => 'Skype Username', 
					'plugin_cm_skype_enabled' => 0,
					'wp_cm_aim_name' => 'aim', 
					'wp_cm_aim_label' => 'AIM', 
					'wp_cm_aim_enabled' => 1,
					'wp_cm_jabber_name' => 'jabber', 
					'wp_cm_jabber_label' => 'Jabber / Google Talk', 
					'wp_cm_jabber_enabled' => 1,
					'wp_cm_yim_name' => 'yim',
					'wp_cm_yim_label' => 'Yahoo IM', 
					'wp_cm_yim_enabled' => 1,
				),
				'site_defaults' => array(
					'options_version' => '',
					'plugin_version' => '',
					'plugin_tid' => '',
					'plugin_tid:use' => 'default',
				),
				'pre' => array(
					'facebook' => 'fb', 
					'gplus' => 'gp',
					'twitter' => 'twitter',
					'linkedin' => 'linkedin',
					'managewp' => 'managewp',
					'pinterest' => 'pin',
					'stumbleupon' => 'stumble',
					'tumblr' => 'tumblr',
					'youtube' => 'yt',
					'skype' => 'skype',
				),
			),
			'wp' => array(				// wordpress
				'min_version' => '3.0',		// minimum wordpress version
				'cm' => array(
					'aim' => 'AIM',
					'jabber' => 'Jabber / Google Talk',
					'yim' => 'Yahoo IM',
				),
			),
			'url' => array(
				'feed' => 'http://feed.surniaulula.com/category/application/wordpress/wp-plugins/wpsso/feed/',
				'readme' => 'http://plugins.svn.wordpress.org/wpsso/trunk/readme.txt',
				'purchase' => 'http://plugin.surniaulula.com/extend/plugins/wpsso/',
				'faq' => 'http://wordpress.org/plugins/wpsso/faq/',
				'notes' => 'http://wordpress.org/plugins/wpsso/other_notes/',
				'changelog' => 'http://wordpress.org/plugins/wpsso/changelog/',
				'support' => 'http://wordpress.org/support/plugin/wpsso',
				'pro_codex' => 'http://codex.wpsso.surniaulula.com/',
				'pro_support' => 'http://support.wpsso.surniaulula.com/',
				'pro_ticket' => 'http://ticket.wpsso.surniaulula.com/',
				'pro_update' => 'http://update.surniaulula.com/extend/plugins/wpsso/update/',
			),
			'follow' => array(
				'size' => 32,
				'src' => array(
					'facebook.png' => 'https://www.facebook.com/SurniaUlulaCom',
					'gplus.png' => 'https://plus.google.com/b/112667121431724484705/112667121431724484705/posts',
					'linkedin.png' => 'https://www.linkedin.com/in/jsmoriss',
					'twitter.png' => 'https://twitter.com/surniaululacom',
					'youtube.png' => 'https://www.youtube.com/user/SurniaUlulaCom',
					'feed.png' => 'http://feed.surniaulula.com/category/application/wordpress/wp-plugins/wpsso/feed/',
				),
			),
			'form' => array(
				'max_desc_hashtags' => 10,
				'max_media_items' => 20,
				'file_cache_hours' => array( 0, 1, 3, 6, 9, 12, 24, 36, 48, 72, 168 ),
				'tooltip_class' => 'sucom_tooltip',
			),
			'head' => array(
				'min_img_width' => 200,
				'min_img_height' => 200,
				'min_desc_len' => 156,
			),
		);

		public static function get_config( $idx = '' ) { 
			if ( ! empty( $idx ) ) {
				if ( array_key_exists( $idx, self::$cf ) )
					return self::$cf[$idx];
				else return false;
			} else return self::$cf;
		}

		public static function set_constants( $plugin_filepath ) { 

			$cf = self::get_config();

			define( 'WPSSO_FILEPATH', $plugin_filepath );						
			define( 'WPSSO_PLUGINDIR', trailingslashit( plugin_dir_path( $plugin_filepath ) ) );
			define( 'WPSSO_PLUGINBASE', plugin_basename( $plugin_filepath ) );
			define( 'WPSSO_TEXTDOM', $cf['slug'] );
			define( 'WPSSO_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'WPSSO_NONCE', md5( WPSSO_PLUGINDIR.'-'.$cf['version'] ) );

			/*
			 * Allow some constants to be pre-defined in wp-config.php
			 */

			if ( defined( 'WPSSO_DEBUG' ) && 
				! defined( 'WPSSO_HTML_DEBUG' ) )
					define( 'WPSSO_HTML_DEBUG', WPSSO_DEBUG );

			if ( ! defined( 'WPSSO_CACHEDIR' ) )
				define( 'WPSSO_CACHEDIR', WPSSO_PLUGINDIR.'cache/' );

			if ( ! defined( 'WPSSO_CACHEURL' ) )
				define( 'WPSSO_CACHEURL', WPSSO_URLPATH.'cache/' );

			if ( ! defined( 'WPSSO_OPTIONS_NAME' ) )
				define( 'WPSSO_OPTIONS_NAME', $cf['lca'].'_options' );

			if ( ! defined( 'WPSSO_SITE_OPTIONS_NAME' ) )
				define( 'WPSSO_SITE_OPTIONS_NAME', $cf['lca'].'_site_options' );

			if ( ! defined( 'WPSSO_META_NAME' ) )
				define( 'WPSSO_META_NAME', '_'.$cf['lca'].'_meta' );

			if ( ! defined( 'WPSSO_MENU_PRIORITY' ) )
				define( 'WPSSO_MENU_PRIORITY', '99.10' );

			if ( ! defined( 'WPSSO_INIT_PRIORITY' ) )
				define( 'WPSSO_INIT_PRIORITY', 12 );

			if ( ! defined( 'WPSSO_HEAD_PRIORITY' ) )
				define( 'WPSSO_HEAD_PRIORITY', 10 );

			if ( ! defined( 'WPSSO_DEBUG_FILE_EXP' ) )
				define( 'WPSSO_DEBUG_FILE_EXP', 300 );

			if ( ! defined( 'WPSSO_CURL_USERAGENT' ) )
				define( 'WPSSO_CURL_USERAGENT', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:18.0) Gecko/20100101 Firefox/18.0' );

			if ( ! defined( 'WPSSO_CURL_CAINFO' ) )
				define( 'WPSSO_CURL_CAINFO', WPSSO_PLUGINDIR.'share/curl/cacert.pem' );

			if ( ! defined( 'WPSSO_TOPICS_LIST' ) )
				define( 'WPSSO_TOPICS_LIST', WPSSO_PLUGINDIR.'share/topics.txt' );
		}

		public static function require_libs( $plugin_filepath ) {
			
			$cf = self::get_config();
			$plugin_dir = WPSSO_PLUGINDIR;

			require_once( $plugin_dir.'lib/com/util.php' );
			require_once( $plugin_dir.'lib/com/cache.php' );
			require_once( $plugin_dir.'lib/com/notice.php' );
			require_once( $plugin_dir.'lib/com/script.php' );
			require_once( $plugin_dir.'lib/com/style.php' );
			require_once( $plugin_dir.'lib/com/webpage.php' );
			require_once( $plugin_dir.'lib/com/opengraph.php' );

			require_once( $plugin_dir.'lib/check.php' );
			require_once( $plugin_dir.'lib/util.php' );
			require_once( $plugin_dir.'lib/options.php' );
			require_once( $plugin_dir.'lib/user.php' );
			require_once( $plugin_dir.'lib/postmeta.php' );
			require_once( $plugin_dir.'lib/media.php' );
			require_once( $plugin_dir.'lib/head.php' );

			if ( is_admin() ) {
				require_once( $plugin_dir.'lib/messages.php' );
				require_once( $plugin_dir.'lib/admin.php' );
				require_once( $plugin_dir.'lib/com/form.php' );
				require_once( $plugin_dir.'lib/ext/parse-readme.php' );
			}

			if ( file_exists( $plugin_dir.'lib/opengraph.php' ) &&
				( ! defined( 'WPSSO_OPEN_GRAPH_DISABLE' ) || ! WPSSO_OPEN_GRAPH_DISABLE ) &&
				empty( $_SERVER['WPSSO_OPEN_GRAPH_DISABLE'] ) )
					require_once( $plugin_dir.'lib/opengraph.php' );	// extends lib/com/opengraph.php

			// additional classes are loaded and extended by the pro addon construct
			if ( file_exists( $plugin_dir.'lib/pro/addon.php' ) )
				require_once( $plugin_dir.'lib/pro/addon.php' );

			add_action( 'wpsso_load_lib', array( 'WpssoConfig', 'load_lib' ), 10, 2 );
		}

		public static function load_lib( $sub, $id ) {
			if ( empty( $sub ) && ! empty( $id ) )
				$filepath = WPSSO_PLUGINDIR.'lib/'.$id.'.php';
			elseif ( ! empty( self::$cf['lib'][$sub][$id] ) )
				$filepath = WPSSO_PLUGINDIR.'lib/'.$sub.'/'.$id.'.php';
			else return false;
			if ( file_exists( $filepath ) ) 
				require_once( $filepath );
		}

	}
}

?>
