<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUtil' ) && class_exists( 'SucomUtil' ) ) {

	class WpssoUtil extends SucomUtil {

		private $urls_found = array();	// array to detect duplicate images, etc.

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_actions();
		}

		protected function add_actions() {
			add_action( 'wp_scheduled_delete', array( &$this, 'delete_expired_transients' ) );
			add_action( 'wp_scheduled_delete', array( &$this, 'delete_expired_file_cache' ) );
		}

		// add filters to this plugin
		public function add_plugin_filters( &$class, $filters, $prio = 10, $prefix = '' ) {
			$prefix = $prefix === '' ? $this->p->cf['lca'] : $prefix;
			foreach ( $filters as $name => $num ) {
				$filter = $prefix.'_'.$name;
				$method = 'filter_'.$name;
				add_filter( $filter, array( &$class, $method ), $prio, $num );
				$this->p->debug->log( 'filter for '.$filter.' added', 2 );
			}
		}

		public function push_add_to_options( &$opts = array(), $add_to_prefixes = array( 'plugin' ) ) {
			foreach ( $add_to_prefixes as $prefix ) {
				foreach ( $this->get_post_types( $prefix ) as $post_type ) {
					$option_name = $prefix.'_add_to_'.$post_type->name;
					$filter_name = $this->p->cf['lca'].'_add_to_options_'.$post_type->name;
					if ( ! isset( $opts[$option_name] ) ) {	
						switch ( $post_type->name ) {
							case 'download':
							case 'product':
								$def_val = 1;
								break;
							default:
								$def_val = 0;
								break;
						}
						$opts[$option_name] = apply_filters( $filter_name, $def_val );
					}
				}
			}
			return $opts;
		}

		public function get_post_types( $opt_prefix, $output = 'objects' ) {
			$include = false;
			$post_types = array();
			switch ( $opt_prefix ) {
				case 'buttons':
					$include = array( 'public' => true );
					break;
				case 'plugin':
					$include = array( 'public' => true, 'show_ui' => true );
					break;
			}
			$post_types = $include !== false ? 
				get_post_types( $include, $output ) : array();

			return apply_filters( $this->p->cf['lca'].'_post_types', $post_types, $opt_prefix, $output );
		}

		public function flush_post_cache( $post_id ) {
			switch ( get_post_status( $post_id ) ) {
			case 'draft':
			case 'pending':
			case 'private':
			case 'publish':
				$lang = SucomUtil::get_locale();
				$cache_type = 'object cache';
				$sharing_url = $this->p->util->get_sharing_url( $post_id );

				$transients = array(
					'WpssoHead::get_header_array' => array( 
						'lang:'.$lang.'_post:'.$post_id.'_url:'.$sharing_url,
						'lang:'.$lang.'_post:'.$post_id.'_url:'.$sharing_url.'_crawler:pinterest',
					),
				);
				$transients = apply_filters( $this->p->cf['lca'].'_post_cache_transients', $transients, $post_id, $lang, $sharing_url );

				$objects = array(
					'SucomWebpage::get_content' => array(
						'lang:'.$lang.'_post:'.$post_id.'_filtered',
						'lang:'.$lang.'_post:'.$post_id.'_unfiltered',
					),
					'SucomWebpage::get_hashtags' => array(
						'lang:'.$lang.'_post:'.$post_id,
					),
				);
				$objects = apply_filters( $this->p->cf['lca'].'_post_cache_objects', $objects, $post_id, $lang, $sharing_url );

				$deleted = 0;
				foreach ( $transients as $group => $arr ) {
					foreach ( $arr as $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( delete_transient( $cache_id ) ) {
							if ( $this->p->debug->is_on() )
								$this->p->debug->log( 'flushed transient cache salt: '. $cache_salt );
							$deleted++;
						}
					}
				}
				foreach ( $objects as $group => $arr ) {
					foreach ( $arr as $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( wp_cache_delete( $cache_id, $group ) ) {
							if ( $this->p->debug->is_on() )
								$this->p->debug->log( 'flushed object cache salt: '. $cache_salt );
							$deleted++;
						}
					}
				}
				if ( $deleted > 0 && $this->p->debug->is_on() )
					$this->p->notice->inf( $deleted.' items flushed from object and transient cache for post ID #'.$post_id, true );
				break;
			}
		}

		public function get_topics() {
			if ( $this->p->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'('.WPSSO_TOPICS_LIST.')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': transient salt '.$cache_salt );
				$topics = get_transient( $cache_id );
				if ( is_array( $topics ) ) {
					$this->p->debug->log( $cache_type.': topics array retrieved from transient '.$cache_id );
					return $topics;
				}
			}
			if ( ( $topics = file( WPSSO_TOPICS_LIST, 
				FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) ) === false ) {
				$this->p->notice->err( 'Error reading <u>'.WPSSO_TOPICS_LIST.'</u>.' );
				return $topics;
			}
			$topics = apply_filters( $this->p->cf['lca'].'_topics', $topics );
			natsort( $topics );
			$topics = array_merge( array( 'none' ), $topics );	// after sorting the array, put 'none' first

			if ( ! empty( $cache_id ) ) {
				set_transient( $cache_id, $topics, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': topics array saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			return $topics;
		}

		public function add_img_sizes_from_opts( $sizes ) {
			foreach( $sizes as $pre => $suf ) {
				if ( ! empty( $this->p->options[$pre.'_width'] ) &&
					! empty( $this->p->options[$pre.'_height'] ) ) {
					$this->p->debug->log( 'image size '.$this->p->cf['lca'].'-'.$suf.
						' ('.$this->p->options[$pre.'_width'].'x'.$this->p->options[$pre.'_height'].
						( empty( $this->p->options[$pre.'_crop'] ) ? '' : ' cropped' ).') added', 2 );
					add_image_size( $this->p->cf['lca'].'-'.$suf, 
						$this->p->options[$pre.'_width'], 
						$this->p->options[$pre.'_height'], 
						( empty( $this->p->options[$pre.'_crop'] ) ? false : true ) );
				}
			}
		}

		public function sanitize_option_value( $key, $val, $def_val ) {
			$option_type = apply_filters( $this->p->cf['lca'].'_option_type', false, $key );
			$reset_msg = __( 'resetting the option to its default value.', WPSSO_TEXTDOM );
			$charset = get_bloginfo( 'charset' );

			// pre-filter most values to remove html
			switch ( $option_type ) {
				case 'code':	// don't remove / encode html tags from css, js, etc.
					break;
				default:
					$val = stripslashes( $val );
					$val = wp_filter_nohtml_kses( $val );
					$val = htmlentities( $val, ENT_QUOTES, $charset, false );	// double_encode = false
					break;
			}

			switch ( $option_type ) {
				case 'atname':	// twitter-style usernames (prepend with an at)
					$val = substr( preg_replace( '/[^a-z0-9_]/', '', strtolower( $val ) ), 0, 15 );
					if ( ! empty( $val ) ) 
						$val = '@'.$val;
					break;

				case 'urlbase':	// strip leading urls off facebook usernames
					$val = preg_replace( '/(http|https):\/\/[^\/]*?\//', '', $val );
					break;

				case 'url':	// must be a url
					if ( ! empty( $val ) && strpos( $val, '://' ) === false ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be a URL'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				case 'numeric':	// must be numeric (blank or zero is ok)
					if ( ! empty( $val ) && ! is_numeric( $val ) ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be numeric'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				case 'posnum':	// integer options that must be 1 or more (not zero)
				case 'imgdim':	// image dimensions, subject to minimum value (typically, at least 200px)
					if ( $option_type == 'imgdim' )
						$min_int = empty( $this->p->cf['head']['min_img_dim'] ) ? 
							200 : $this->p->cf['head']['min_img_dim'];
					else $min_int = 1;
					if ( empty( $val ) || ! is_numeric( $val ) || $val < $min_int ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be greater or equal to '.$min_int.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				case 'textured':	// must be texturized 
					$val = trim( wptexturize( ' '.$val.' ' ) );
					break;

				case 'anucase':	// must be alpha-numeric uppercase (hyphens and periods allowed as well)
					if ( ! empty( $val ) && preg_match( '/[^A-Z0-9\-\.]/', $val ) ) {
						$this->p->notice->inf( '\''.$val.'\' is not an accepted value for option \''.$key.'\''.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				case 'okblank':	// text strings that can be blank
					if ( ! empty( $val ) )
						$val = trim( $val );
					break;

				case 'code':	// options that cannot be blank
				case 'notblank':
					if ( empty( $val ) ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' cannot be empty'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				case 'checkbox':	// everything else is a 1/0 checkbox option 
				default:
					// make sure the default option is also 1/0, just in case
					if ( $def_val === 0 || $def_val === 1 )
						$val = empty( $val ) ? 0 : 1;
					break;
			}
			return $val;
		}
	}
}

?>
