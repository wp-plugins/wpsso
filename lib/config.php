<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoConfig' ) ) {

	class WpssoConfig {

		private static $cf = array(
			'version' => '1.21.0',		// plugin version
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

		}

		public static function require_libs( $plugin_filepath ) {
			
			$cf = self::get_config();
			$plugin_dir = WPSSO_PLUGINDIR;

			require_once( $plugin_dir.'lib/com/functions.php' );
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

				// setting and submenu classes extend lib/admin.php, and objects are created by lib/admin.php
				// some setting classes extend submenu classes, so load the submenu array first
				foreach ( array( 'submenu', 'setting' ) as $sub )
					foreach ( $cf['lib'][$sub] as $id => $name )
						if ( file_exists( $plugin_dir.'lib/'.$sub.'/'.$id.'.php' ) )
							require_once( $plugin_dir.'lib/'.$sub.'/'.$id.'.php' );

				// load the network settings if we're a multisite
				if ( is_multisite() )
					foreach ( $cf['lib']['site_submenu'] as $id => $name )
						require_once( $plugin_dir.'lib/site_submenu/'.$id.'.php' );

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
			if ( file_exists( WPSSO_PLUGINDIR.'lib/'.$sub.'/'.$id.'.php' ) )
				require_once( WPSSO_PLUGINDIR.'lib/'.$sub.'/'.$id.'.php' );
		}
	}
}

?>
