<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoOptionsUpgrade' ) && class_exists( 'WpssoOptions' ) ) {

	class WpssoOptionsUpgrade extends WpssoOptions {

		private $renamed_site_keys = array(
			'plugin_tid_use' => 'plugin_tid:use',
		);

		private $renamed_keys = array(
			'add_meta_desc' => 'inc_description',
			'og_def_img' => 'og_def_img_url',
			'og_def_home' => 'og_def_img_on_index',
			'og_def_on_home' => 'og_def_img_on_index',
			'og_def_on_search' => 'og_def_img_on_search',
			'buttons_on_home' => 'buttons_on_index',
			'buttons_lang' => 'gp_lang',
			'wpsso_cache_hours' => 'plugin_file_cache_hrs',
			'fb_enable' => 'fb_on_the_content', 
			'gp_enable' => 'gp_on_the_content',
			'twitter_enable' => 'twitter_on_the_content',
			'linkedin_enable' => 'linkedin_on_the_content',
			'pin_enable' => 'pin_on_the_content',
			'stumble_enable' => 'stumble_on_the_content',
			'tumblr_enable' => 'tumblr_on_the_content',
			'buttons_location' => 'buttons_location_the_content',
			'plugin_pro_tid' => 'plugin_tid',
			'og_admins' => 'fb_admins',
			'og_app_id' => 'fb_app_id',
			'link_desc_len' => 'meta_desc_len',
			'wpsso_version' => 'options_version',
			'wpsso_opts_ver' => 'options_version',
			'wpsso_pro_tid' => 'plugin_tid',
			'wpsso_preserve' => 'plugin_preserve',
			'wpsso_reset' => 'plugin_reset',
			'wpsso_debug' => 'plugin_debug',
			'wpsso_enable_shortcode' => 'plugin_shortcode_wpsso',
			'wpsso_skip_small_img' => 'plugin_ignore_small_img',
			'wpsso_filter_content' => 'plugin_filter_content',
			'wpsso_filter_excerpt' => 'plugin_filter_excerpt',
			'wpsso_add_to_post' => 'plugin_add_to_post',
			'wpsso_add_to_page' => 'plugin_add_to_page',
			'wpsso_add_to_attachment' => 'plugin_add_to_attachment',
			'wpsso_verify_certs' => 'plugin_verify_certs',
			'wpsso_file_cache_hrs' => 'plugin_file_cache_hrs',
			'wpsso_object_cache_exp' => 'plugin_object_cache_exp',
			'wpsso_min_shorten' => 'plugin_min_shorten',
			'wpsso_googl_api_key' => 'plugin_google_api_key',
			'wpsso_bitly_login' => 'plugin_bitly_login',
			'wpsso_bitly_api_key' => 'plugin_bitly_api_key',
			'wpsso_cdn_urls' => 'plugin_cdn_urls',
			'wpsso_cdn_folders' => 'plugin_cdn_folders',
			'wpsso_cdn_excl' => 'plugin_cdn_excl',
			'wpsso_cdn_not_https' => 'plugin_cdn_not_https',
			'wpsso_cdn_www_opt' => 'plugin_cdn_www_opt',
			'wpsso_cm_fb_name' => 'plugin_cm_fb_name', 
			'wpsso_cm_fb_label' => 'plugin_cm_fb_label', 
			'wpsso_cm_fb_enabled' => 'plugin_cm_fb_enabled',
			'wpsso_cm_gp_name' => 'plugin_cm_gp_name', 
			'wpsso_cm_gp_label' => 'plugin_cm_gp_label', 
			'wpsso_cm_gp_enabled' => 'plugin_cm_gp_enabled',
			'wpsso_cm_linkedin_name' => 'plugin_cm_linkedin_name', 
			'wpsso_cm_linkedin_label' => 'plugin_cm_linkedin_label', 
			'wpsso_cm_linkedin_enabled' => 'plugin_cm_linkedin_enabled',
			'wpsso_cm_pin_name' => 'plugin_cm_pin_name', 
			'wpsso_cm_pin_label' => 'plugin_cm_pin_label', 
			'wpsso_cm_pin_enabled' => 'plugin_cm_pin_enabled',
			'wpsso_cm_tumblr_name' => 'plugin_cm_tumblr_name', 
			'wpsso_cm_tumblr_label' => 'plugin_cm_tumblr_label', 
			'wpsso_cm_tumblr_enabled' => 'plugin_cm_tumblr_enabled',
			'wpsso_cm_twitter_name' => 'plugin_cm_twitter_name', 
			'wpsso_cm_twitter_label' => 'plugin_cm_twitter_label', 
			'wpsso_cm_twitter_enabled' => 'plugin_cm_twitter_enabled',
			'wpsso_cm_yt_name' => 'plugin_cm_yt_name', 
			'wpsso_cm_yt_label' => 'plugin_cm_yt_label', 
			'wpsso_cm_yt_enabled' => 'plugin_cm_yt_enabled',
			'wpsso_cm_skype_name' => 'plugin_cm_skype_name', 
			'wpsso_cm_skype_label' => 'plugin_cm_skype_label', 
			'wpsso_cm_skype_enabled' => 'plugin_cm_skype_enabled',
			'plugin_googl_api_key' => 'plugin_google_api_key',
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		// def_opts accepts output from functions, so don't force reference
		public function options( $options_name, &$opts = array(), $def_opts = array() ) {

			$opts = $this->p->util->rename_keys( $opts, $this->renamed_keys );

			// custom value changes for regular options
			if ( $options_name == constant( $this->p->cf['uca'].'_OPTIONS_NAME' ) ) {
				// these option names may have been used in the past, so remove them, just in case
				if ( $opts['options_version'] < 30 ) {
					unset( $opts['og_img_width'] );
					unset( $opts['og_img_height'] );
					unset( $opts['og_img_crop'] );
				}
				if ( $opts['options_version'] < 218 ) {
					foreach ( array(
						'tc_gal_size',
						'tc_photo_size',
						'tc_large_size',
						'tc_sum_size',
						'tc_prod_size',
						'pin_img_size',
						'tumblr_img_size',
					) as $key ) {
						// look for default values and update
						switch ( $opts[$key] ) {
							case 'thumbnail':
							case 'medium':
							case 'large':
								$opts[$key] = $this->p->cf['lca'].'-'.$opts[$key];
								break;
						}
					}
				}
	
				if ( ! empty( $opts['twitter_shorten'] ) )
					$opts['twitter_shortener'] = 'googl';
	
				// upgrade the old og_img_size name into width / height / crop values
				if ( array_key_exists( 'og_img_size', $opts ) ) {
					if ( ! empty( $opts['og_img_size'] ) && $opts['og_img_size'] !== 'medium' ) {
						$size_info = $this->p->media->get_size_info( $opts['og_img_size'] );
						if ( $size_info['width'] > 0 && $size_info['height'] > 0 ) {
							$opts['og_img_width'] = $size_info['width'];
							$opts['og_img_height'] = $size_info['height'];
							$opts['og_img_crop'] = $size_info['crop'];
						}
						unset( $opts['og_img_size'] );
					}
				}
			}

			$opts = $this->sanitize( $opts, $def_opts );	// cleanup excess options and sanitize
			return $opts;
		}
	}
}
?>
