<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoOptions' ) ) {

	class WpssoOptions {

		private $upg;

		protected $p;

		// increment when changing default options
		public $options_version = '219';

		public $site_defaults = array(
			'options_version' => '',
			'plugin_version' => '',
			'plugin_tid' => '',
			'plugin_tid:use' => 'default',
		);

		public $defaults = array(
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
			'plugin_get_img_size' => 0,
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
		);

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_site_defaults( $idx = '' ) {
			$defs = apply_filters( $this->p->cf['lca'].'_get_site_defaults', $this->site_defaults );
			if ( ! empty( $idx ) ) {
				if ( array_key_exists( $idx, $defs ) )
					return $defs[$idx];
				else return false;
			} else return $defs;
		}

		public function get_defaults( $idx = '' ) {

			$this->defaults = $this->add_post_type_options( $this->defaults );

			$this->defaults['link_author_field'] = empty( $this->p->options['plugin_cm_gp_name'] ) ? 
				$this->defaults['plugin_cm_gp_name'] : $this->p->options['plugin_cm_gp_name'];

			$this->defaults['og_author_field'] = empty( $this->p->options['plugin_cm_fb_name'] ) ? 
				$this->defaults['plugin_cm_fb_name'] : $this->p->options['plugin_cm_fb_name'];

			// add description meta tag if no known SEO plugin was detected
			$this->defaults['inc_description'] = empty( $this->p->is_avail['seo']['*'] ) ? 1 : 0;

			// check for default values from network admin settings
			if ( is_multisite() && is_array( $this->p->site_options ) ) {
				foreach ( $this->p->site_options as $key => $val ) {
					if ( array_key_exists( $key, $this->defaults ) && 
						array_key_exists( $key.':use', $this->p->site_options ) ) {

						if ( $this->p->site_options[$key.':use'] == 'default' )
							$this->defaults[$key] = $this->p->site_options[$key];
					}
				}
			}

			$this->defaults = apply_filters( $this->p->cf['lca'].'_get_defaults', $this->defaults );
			if ( ! empty( $idx ) ) 
				if ( array_key_exists( $idx, $this->defaults ) )
					return $this->defaults[$idx];
				else return false;
			else return $this->defaults;
		}

		public function add_post_type_options( &$opts = array() ) {
			foreach ( array( 'plugin' ) as $prefix ) {
				foreach ( $this->p->util->get_post_types( $prefix ) as $post_type ) {
					$option_name = $prefix.'_add_to_'.$post_type->name;
					if ( ! array_key_exists( $option_name, $opts ) ) {
						switch ( $post_type->name ) {
							case 'product':
								$opts[$option_name] = 1;
								break;
							default:
								$opts[$option_name] = 0;
								break;
						}
					}
				}
			}
			return $opts;
		}

		public function check_options( $options_name, &$opts = array() ) {
			$opts_err_msg = '';
			if ( ! empty( $opts ) && is_array( $opts ) ) {

				// check version in saved options, upgrade if they don't match
				$options_version = apply_filters( $this->p->cf['lca'].'_options_version', $this->options_version );
				if ( ( empty( $opts['plugin_version'] ) || $opts['plugin_version'] !== $this->p->cf['version'] ) ||
					( empty( $opts['options_version'] ) || $opts['options_version'] !== $options_version ) ) {

					// upgrade the options if options version mismatch
					if ( empty( $opts['options_version'] ) || $opts['options_version'] !== $options_version ) {
						$this->p->debug->log( $options_name.' version different than saved' );
						// only load upgrade class when needed to save a few Kb
						if ( ! is_object( $this->upg ) ) {
							require_once( WPSSO_PLUGINDIR.'lib/upgrade.php' );
							$this->upg = new WpssoOptionsUpgrade( $this->p );
						}
						$opts = $this->upg->options( $options_name, $opts, $this->get_defaults() );
					}

					if ( $options_name == WPSSO_OPTIONS_NAME &&
						$this->p->is_avail['aop'] !== true && 
						empty( $this->p->options['plugin_tid'] ) ) {

						// show the nag and update the options only if we have someone with access
						if ( current_user_can( 'manage_options' ) ) {
							if ( ! is_object( $this->p->msg ) ) {
								require_once( WPSSO_PLUGINDIR.'lib/messages.php' );
								$this->p->msg = new WpssoMessages( $this->p );
							}
							$this->p->notice->nag( $this->p->msg->get( 'pro-advert-nag' ), true );
							$this->save_options( $options_name, $opts );
						}
					} else $this->save_options( $options_name, $opts );
				}

				// add support for post types that may have been added since options last saved
				if ( $options_name == WPSSO_OPTIONS_NAME )
					$opts = $this->add_post_type_options( $opts );

				if ( ! empty( $this->p->is_avail['seo']['*'] ) &&
					array_key_exists( 'inc_description', $opts ) ) {
					$opts['inc_description'] = 0;
					$opts['inc_description:is'] = 'disabled';
				}
			} else {
				if ( $opts === false )
					$opts_err_msg = 'could not find an entry for '.$options_name.' in';
				elseif ( ! is_array( $opts ) )
					$opts_err_msg = 'returned a non-array value when reading '.$options_name.' from';
				elseif ( empty( $opts ) )
					$opts_err_msg = 'returned an empty array when reading '.$options_name.' from';
				else $opts_err_msg = 'returned an unknown condition when reading '.$options_name.' from';

				$this->p->debug->log( 'WordPress '.$opts_err_msg.' the options database table.' );
				if ( $options_name == WPSSO_SITE_OPTIONS_NAME )
					$opts = $this->get_site_defaults();
				else $opts = $this->get_defaults();
			}

			if ( is_admin() ) {
				if ( ! empty( $opts_err_msg ) ) {
					if ( $options_name == WPSSO_SITE_OPTIONS_NAME )
						$url = $this->p->util->get_admin_url( 'network' );
					else $url = $this->p->util->get_admin_url( 'general' );

					$this->p->notice->err( 'WordPress '.$opts_err_msg.' the options table. 
						Plugin settings have been returned to their default values. 
						<a href="'.$url.'">Please review and save the new settings</a>.' );
				}
				if ( $options_name == WPSSO_OPTIONS_NAME ) {
					if ( $this->p->options['og_img_width'] < $this->p->cf['head']['min_img_width'] || 
						$this->p->options['og_img_height'] < $this->p->cf['head']['min_img_height'] ) {

						$url = $this->p->util->get_admin_url( 'general' );
						$size_desc = $this->p->options['og_img_width'].'x'.$this->p->options['og_img_height'];
						$this->p->notice->inf( 'The image size of '.$size_desc.' for images in the Open Graph meta tags
							is smaller than the minimum of '.$this->p->cf['head']['min_img_width'].'x'.$this->p->cf['head']['min_img_height'].'. 
							<a href="'.$url.'">Please enter a larger image dimensions on the General Settings page</a>.' );
					}
					if ( $this->p->check->is_aop() &&
						! empty( $this->p->is_avail['ecom']['*'] ) &&
						$opts['tc_prod_def_l2'] === 'Location' &&
						$opts['tc_prod_def_d2'] === 'Unknown' ) {
	
						$this->p->notice->inf( 'An eCommerce plugin has been detected. Please update Twitter\'s
							<em>Product Card Default 2nd Attribute</em> option values on the '.
							$this->p->util->get_admin_url( 'general', 'General settings page' ). ' 
							(to something else than \'Location\' and \'Unknown\').' );
					}
				}
				if ( $this->p->is_avail['aop'] === true && empty( $this->p->options['plugin_tid'] ) )
					$this->p->notice->nag( $this->p->msg->get( 'pro-activate-nag' ) );
			}
			return $opts;
		}

		// sanitize and validate input
		public function sanitize( $opts = array(), $def_opts = array() ) {

			// make sure we have something to work with
			if ( empty( $def_opts ) || ! is_array( $def_opts ) )
				return $opts;

			$charset = get_bloginfo( 'charset' );

			// unset options that no longer exist
			foreach ( $opts as $key => $val )
				// check that the key doesn't exist in the default options (which is a complete list of the current options used)
				if ( ! empty( $key ) && ! array_key_exists( $key, $def_opts ) )
					unset( $opts[$key] );

			// add missing options and set to defaults
			foreach ( $def_opts as $key => $def_val ) {

				if ( ! empty( $key ) && ! array_key_exists( $key, $opts ) ) {
					$opts[$key] = $def_val;
					continue;
				}

				$opts[$key] = stripslashes( $opts[$key] );
				$opts[$key] = wp_filter_nohtml_kses( $opts[$key] );
				$opts[$key] = htmlentities( $opts[$key], ENT_QUOTES, $charset, false );	// double_encode = false

				switch ( $key ) {
					/* 
					 * twitter-style usernames (prepend with an at).
					 */
					case 'tc_site':
						$opts[$key] = substr( preg_replace( '/[^a-z0-9_]/', '', 
							strtolower( $opts[$key] ) ), 0, 15 );
						if ( ! empty( $opts[$key] ) ) 
							$opts[$key] = '@'.$opts[$key];
						break;
					/* 
					 * strip leading urls off facebook usernames
					 */
					case 'fb_admins':
						$opts[$key] = preg_replace( '/(http|https):\/\/[^\/]*?\//', '', 
							$opts[$key] );
						break;
					/* 
					 * must be a url
					 */
					case ( preg_match( '/^[a-z_]+_urls?$/', $key ) ? true : false ):
						if ( ! empty( $opts[$key] ) && strpos( $opts[$key], '://' ) === false ) {
							$this->p->notice->inf( 'The value of option \''.$key.'\' must be a URL'.
								' - resetting the option to its default value.', true );
							$opts[$key] = $def_val;
						}
						break;
					/* 
					 * must be numeric (blank or zero is ok)
					 */
					case 'link_def_author_id':
					case 'og_desc_hashtags': 
					case 'og_img_max':
					case 'og_vid_max':
					case 'og_img_id':
					case 'og_def_img_id':
					case 'og_def_author_id':
					case 'plugin_file_cache_hrs':
						if ( ! empty( $opts[$key] ) && ! is_numeric( $opts[$key] ) ) {
							$this->p->notice->inf( 'The value of option \''.$key.'\' must be numeric'.
								' - resetting the option to its default value.', true );
							$opts[$key] = $def_val;
						}
						break;
					/* 
					 * integer options that must be 1 or more (not zero)
					 */
					case 'meta_desc_len': 
					case 'og_desc_len': 
					case 'og_img_width': 
					case 'og_img_height': 
					case 'og_title_len': 
					case 'plugin_object_cache_exp':
						if ( empty( $opts[$key] ) || ! is_numeric( $opts[$key] ) ) {
							$this->p->notice->inf( 'The value of option \''.$key.'\' must be greater or equal to 1'.
								' - resetting the option to its default value.', true );
							$opts[$key] = $def_val;
						}
						break;
					/* 
					 * must be texturized 
					 */
					case 'og_title_sep':
						$opts[$key] = trim( wptexturize( ' '.$opts[$key].' ' ) );
						break;
					/* 
					 * must be alpha-numeric uppercase
					 */
					case 'plugin_tid':
						if ( ! empty( $opts[$key] ) && preg_match( '/[^A-Z0-9]/', $opts[$key] ) ) {
							$this->p->notice->inf( '\''.$opts[$key].'\' is not an accepted value for option \''.$key.'\''.
								' - resetting the option to its default value.', true );
							$opts[$key] = $def_val;
						}
						break;
					/* 
					 * text strings that can be blank
					 */
					case 'og_art_section':
					case 'fb_app_id':
					case 'og_title':
					case 'og_desc':
					case 'og_site_name':
					case 'og_site_description':
					case 'meta_desc':
						if ( ! empty( $opts[$key] ) )
							$opts[$key] = trim( $opts[$key] );
						break;
					/* 
					 * options that cannot be blank
					 */
					case ( preg_match( '/^(plugin|wp)_cm_[a-z]+_(name|label)$/', $key ) ? true : false ):
					case 'link_author_field':
					case 'og_img_id_pre': 
					case 'og_def_img_id_pre': 
					case 'og_author_field':
					case 'fb_lang': 
					case 'plugin_tid:use':
						if ( empty( $opts[$key] ) ) {
							$this->p->notice->inf( 'The value of option \''.$key.'\' cannot be empty'.
								' - resetting the option to its default value.', true );
							$opts[$key] = $def_val;
						}
						break;
					/* 
					 * everything else is a 1/0 checkbox option 
					 */
					default:
						// make sure the default option is also 1/0
						if ( $def_val === 0 || $def_val === 1 )
							$opts[$key] = empty( $opts[$key] ) ? 0 : 1;
						break;
				}
			}

			/*
			 * Adjust dependent options
			 */
			if ( empty( $opts['plugin_file_cache_hrs'] ) || empty( $opts['plugin_ignore_small_img'] ) ) {
				$opts['plugin_get_img_size'] = 0;
				$opts['plugin_get_img_size:is'] = 'disabled';
			}

			// og_desc_len must be at least 156 chars (defined in config)
			if ( array_key_exists( 'og_desc_len', $opts ) && $opts['og_desc_len'] < $this->p->cf['head']['min_desc_len'] ) 
				$opts['og_desc_len'] = $this->p->cf['head']['min_desc_len'];

			return $opts;
		}

		// saved both options and site options
		public function save_options( $options_name, &$opts ) {
			// make sure we have something to work with
			if ( empty( $opts ) || ! is_array( $opts ) ) {
				$this->p->debug->log( 'exiting early: options variable is empty and/or not array' );
				return $opts;
			}
			// mark the new options as current
			$previous_opts_version = $opts['options_version'];
			$opts['options_version'] = apply_filters( $this->p->cf['lca'].'_options_version', $this->options_version );
			$opts['plugin_version'] = $this->p->cf['version'];

			// update_option() returns false if options are the same or there was an error, 
			// so check to make sure they need to be updated to avoid throwing a false error
			if ( $options_name == WPSSO_SITE_OPTIONS_NAME )
				$opts_current = get_site_option( $options_name, $opts );
			else $opts_current = get_option( $options_name, $opts );

			if ( $opts_current !== $opts ) {
				if ( $options_name == WPSSO_SITE_OPTIONS_NAME )
					$saved = update_site_option( $options_name, $opts );
				else $saved = update_option( $options_name, $opts );

				if ( $saved === true ) {
					// if we're just saving a new plugin version string, don't bother showing the upgrade message
					if ( $previous_opts_version !== $opts['options_version'] ) {
						$this->p->debug->log( 'upgraded '.$options_name.' settings have been saved' );
						$this->p->notice->inf( 'Plugin settings ('.$options_name.') have been upgraded and saved.', true );
					}
				} else {
					$this->p->debug->log( 'failed to save the upgraded '.$options_name.' settings.' );
					$this->p->notice->err( 'Plugin settings have been upgraded, but WordPress returned an error when saving them.', true );
					return false;
				}
			} else $this->p->debug->log( 'new and old options array is identical' );

			return true;
		}
	}
}

?>
