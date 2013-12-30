<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPluginConfig' ) ) {

	class WpssoPluginConfig {

		private static $cf = array(
			'version' => '0.20dev2',	// plugin version
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
					'general' => 'General',
					'advanced' => 'Advanced',
					'contact' => 'Contact Methods',
					'about' => 'About',
				),
				'site_setting' => array(
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
						'wistia' => 'Wistia Video API',
					),
				),
			),
			'opt' => array(				// options
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
			// original list from http://en.wikipedia.org/wiki/Category:Websites_by_topic
			'topics' => array(
				'Animation',
				'Architecture',
				'Art',
				'Automotive',
				'Aviation',
				'Chat',
				'Children\'s',
				'Comics',
				'Commerce',
				'Community',
				'Dance',
				'Dating',
				'Digital Media',
				'Documentary',
				'Download',
				'Economics',
				'Educational',
				'Employment',
				'Entertainment',
				'Environmental',
				'Erotica and Pornography',
				'Fashion',
				'File Sharing',
				'Food and Drink',
				'Fundraising',
				'Genealogy',
				'Health',
				'History',
				'Humor',
				'Law Enforcement',
				'Legal',
				'Literature',
				'Medical',
				'Military',
				'Nature',
				'News',
				'Nostalgia',
				'Parenting',
				'Pets',
				'Photography',
				'Political',
				'Religious',
				'Review',
				'Reward',
				'Route Planning',
				'Satirical',
				'Science Fiction',
				'Science',
				'Shock',
				'Social Networking',
				'Spiritual',
				'Sport',
				'Technology',
				'Travel',
				'Vegetarian',
				'Webmail',
				'Women\'s',
			),
		);
		private static $cf_filtered = false;

		public static function get_config( $idx = '' ) { 
			if ( self::$cf_filtered === false ) {
				self::$cf = apply_filters( self::$cf['lca'].'_get_config', self::$cf );
				self::$cf_filtered = true;
			}
			if ( ! empty( $idx ) ) {
				if ( array_key_exists( $idx, self::$cf ) )
					return self::$cf[$idx];
				else return false;
			} else return self::$cf;
		}

		public static function set_constants( $plugin_filepath ) { 

			$cf = self::get_config();
			$cp = $cf['uca'].'_';	// constant prefix

			// .../wordpress/wp-content/plugins/wpsso/wpsso.php
			define( $cp.'FILEPATH', $plugin_filepath );						

			// .../wordpress/wp-content/plugins/wpsso/
			define( $cp.'PLUGINDIR', trailingslashit( plugin_dir_path( $plugin_filepath ) ) );

			// wpsso/wpsso.php
			define( $cp.'PLUGINBASE', plugin_basename( $plugin_filepath ) );

			// wpsso
			define( $cp.'TEXTDOM', $cf['slug'] );

			// http://.../wp-content/plugins/wpsso/
			define( $cp.'URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );

			define( $cp.'NONCE', md5( constant( $cp.'PLUGINDIR' ).'-'.$cf['version'] ) );

			/*
			 * Allow some constants to be pre-defined in wp-config.php
			 */

			if ( defined( $cp.'DEBUG' ) && 
				! defined( $cp.'HTML_DEBUG' ) )
					define( $cp.'HTML_DEBUG', constant( $cp.'DEBUG' ) );

			if ( ! defined( $cp.'CACHEDIR' ) )
				define( $cp.'CACHEDIR', constant( $cp.'PLUGINDIR' ).'cache/' );

			if ( ! defined( $cp.'CACHEURL' ) )
				define( $cp.'CACHEURL', constant( $cp.'URLPATH' ).'cache/' );

			if ( ! defined( $cp.'OPTIONS_NAME' ) )
				define( $cp.'OPTIONS_NAME', $cf['lca'].'_options' );

			if ( ! defined( $cp.'SITE_OPTIONS_NAME' ) )
				define( $cp.'SITE_OPTIONS_NAME', $cf['lca'].'_site_options' );

			if ( ! defined( $cp.'META_NAME' ) )
				define( $cp.'META_NAME', '_'.$cf['lca'].'_meta' );

			if ( ! defined( $cp.'MENU_PRIORITY' ) )
				define( $cp.'MENU_PRIORITY', '99.10' );

			if ( ! defined( $cp.'INIT_PRIORITY' ) )
				define( $cp.'INIT_PRIORITY', 12 );

			if ( ! defined( $cp.'HEAD_PRIORITY' ) )
				define( $cp.'HEAD_PRIORITY', 10 );

			if ( ! defined( $cp.'FOOTER_PRIORITY' ) )
				define( $cp.'FOOTER_PRIORITY', 100 );
			
			if ( ! defined( $cp.'DEBUG_FILE_EXP' ) )
				define( $cp.'DEBUG_FILE_EXP', 300 );

			if ( ! defined( $cp.'CURL_USERAGENT' ) )
				define( $cp.'CURL_USERAGENT', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:18.0) Gecko/20100101 Firefox/18.0' );

			if ( ! defined( $cp.'CURL_CAINFO' ) )
				define( $cp.'CURL_CAINFO', constant( $cp.'PLUGINDIR' ).'share/curl/cacert.pem' );

		}

		public static function require_libs( $plugin_filepath ) {
			
			$cf = self::get_config();

			$plugin_dir = constant( $cf['uca'].'_'.'PLUGINDIR' );

			require_once( $plugin_dir.'lib/com/functions.php' );
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

				// settings classes extend lib/admin.php, and settings objects are created by lib/admin.php
				foreach ( $cf['lib']['setting'] as $id => $name )
					if ( file_exists( $plugin_dir.'lib/setting/'.$id.'.php' ) )
						require_once( $plugin_dir.'lib/setting/'.$id.'.php' );

				// load the network settings if we're a multisite
				if ( is_multisite() )
					foreach ( $cf['lib']['site_setting'] as $id => $name )
						require_once( $plugin_dir.'lib/site_setting/'.$id.'.php' );

				require_once( $plugin_dir.'lib/com/form.php' );
				require_once( $plugin_dir.'lib/ext/parse-readme.php' );
			}

			if ( file_exists( $plugin_dir.'lib/opengraph.php' ) &&
				( ! defined( $cf['uca'].'_OPEN_GRAPH_DISABLE' ) || ! constant( $cf['uca'].'_OPEN_GRAPH_DISABLE' ) ) &&
				empty( $_SERVER['WPSSO_OPEN_GRAPH_DISABLE'] ) )
					require_once( $plugin_dir.'lib/opengraph.php' );	// extends lib/com/opengraph.php

			// additional classes are loaded and extended by the pro addon construct
			if ( file_exists( $plugin_dir.'lib/pro/addon.php' ) )
				require_once( $plugin_dir.'lib/pro/addon.php' );
		}
	}
}
?>
