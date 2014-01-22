<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomUtil' ) ) {

	class SucomUtil {

		private $urls_found = array();	// array to detect duplicate images, etc.

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public static function is_assoc( $arr ) {
			if ( ! is_array( $arr ) ) 
				return false;
			return is_numeric( implode( array_keys( $arr ) ) ) ? false : true;
		}

		public static function preg_grep_keys( $preg, $arr, $invert = false, $replace = false ) {
			if ( ! is_array( $arr ) ) 
				return false;
			$invert = $invert == false ? 
				null : PREG_GREP_INVERT;
			$match = preg_grep( $preg, array_keys( $arr ), $invert );
			$found = array();
			foreach ( $match as $key ) {
				if ( $replace !== false ) {
					$fixed = preg_replace( $preg, $replace, $key );
					$found[$fixed] = $arr[$key]; 
				} else $found[$key] = $arr[$key]; 
			}
			return $found;
		}

		public static function rename_keys( &$opts = array(), &$keys = array() ) {
			// move old option values to new option names
			foreach ( $keys as $old => $new )
				// rename if the old array key exists, but not the new one (we don't want to overwrite current values)
				if ( ! empty( $old ) && ! empty( $new ) && 
					array_key_exists( $old, $opts ) && 
					! array_key_exists( $new, $opts ) ) {

					$opts[$new] = $opts[$old];
					unset( $opts[$old] );
				}
			return $opts;
		}

		public static function restore_checkboxes( &$opts ) {
			// unchecked checkboxes are not provided, so re-create them here based on hidden values
			$checkbox = self::preg_grep_keys( '/^is_checkbox_/', $opts, false, '' );
			foreach ( $checkbox as $key => $val ) {
				if ( ! array_key_exists( $key, $opts ) )
					$opts[$key] = 0;	// add missing checkbox as empty
				unset ( $opts['is_checkbox_'.$key] );
			}
			return $opts;
		}

		public function reset_urls_found() {
			$this->urls_found = array();
			return;
		}

		public function get_urls_found() {
			return $this->urls_found;
		}

		public function is_uniq_url( $url = '' ) {
			if ( empty( $url ) ) 
				return false;

			// complete the url with a protocol name
			if ( strpos( $url, '//' ) === 0 )
				$url = empty( $_SERVER['HTTPS'] ) ? 'http:'.$url : 'https:'.$url;

			if ( ! preg_match( '/[a-z]+:\/\//i', $url ) )
				$this->p->debug->log( 'incomplete url given: '.$url );

			if ( empty( $this->urls_found[$url] ) ) {
				$this->urls_found[$url] = 1;
				return true;
			} else {
				$this->p->debug->log( 'duplicate url rejected: '.$url ); 
				return false;
			}
		}

		public function get_the_object( $use_post = false ) {
			$obj = false;
			if ( $use_post === false ) {
				$obj = get_queried_object();
				// fallback to $post if object is empty
				if ( ! isset( $obj->ID ) ) {
					global $post; 
					return $post;
				}
			} elseif ( $use_post === true ) {
				global $post; 
				return $post;
			} elseif ( is_numeric( $use_post ) ) 
				return get_post( $use_post );

			if ( $obj === false )
				$this->p->debug->log( 'cannot determine object type' );
			return $obj;
		}

		public function get_meta_sharing_url( $post_id ) {
			$url = false;
			if ( empty( $post_id ) || 
				! array_key_exists( 'postmeta', $this->p->addons ) )
					return $url;
			$url = $this->p->addons['util']['postmeta']->get_options( $post_id, 'sharing_url' );
			if ( ! empty( $url ) )
				$this->p->debug->log( 'found custom meta sharing url = '.$url );
			return $url;
		}

		// use_post = false when used for open graph meta tags and buttons in widget,
		// true when buttons are added to individual posts on an index webpage
		// most of this code is from yoast wordpress seo, to try and match its canonical url value
		public function get_sharing_url( $use_post = false, $add_page = true, $source_id = '' ) {
			$url = false;
			if ( is_singular() || $use_post !== false ) {
				if ( ( $obj = $this->get_the_object( $use_post ) ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return $url;
				}
				$post_id = empty( $obj->ID ) ? 0 : $obj->ID;
				if ( ! empty( $post_id ) ) {
					$url = $this->get_meta_sharing_url( $post_id );

					if ( empty( $url ) )
						$url = get_permalink( $post_id );
				
					if ( $add_page && get_query_var( 'page' ) > 1 ) {
						global $wp_rewrite;
						$numpages = substr_count( $obj->post_content, '<!--nextpage-->' ) + 1;
						if ( $numpages && get_query_var( 'page' ) <= $numpages ) {
							if ( ! $wp_rewrite->using_permalinks() || strpos( $url, '?' ) !== false )
								$url = add_query_arg( 'page', get_query_var( 'page' ), $url );
							else $url = user_trailingslashit( trailingslashit( $url ).get_query_var( 'page' ) );
						}
					}
				}
				$url = apply_filters( $this->p->cf['lca'].'_post_url', $url, $post_id, $use_post, $add_page, $source_id );
			} else {
				if ( is_search() )
					$url = get_search_link();
				elseif ( is_front_page() )
					$url = home_url( '/' );
				elseif ( $this->is_posts_page() )
					$url = get_permalink( get_option( 'page_for_posts' ) );
				elseif ( is_tax() || is_tag() || is_category() ) {
					$term = get_queried_object();
					$url = get_term_link( $term, $term->taxonomy );
					$url = apply_filters( $this->p->cf['lca'].'_term_url', $url, $term );
				}
				elseif ( function_exists( 'get_post_type_archive_link' ) && is_post_type_archive() )
					$url = get_post_type_archive_link( get_query_var( 'post_type' ) );
				elseif ( is_author() )
					$url = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
				elseif ( is_archive() ) {
					if ( is_date() ) {
						if ( is_day() )
							$url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
						elseif ( is_month() )
							$url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
						elseif ( is_year() )
							$url = get_year_link( get_query_var( 'year' ) );
					}
				}
				if ( ! empty( $url ) && $add_page && get_query_var( 'paged' ) > 1 ) {
					global $wp_rewrite;
					if ( ! $wp_rewrite->using_permalinks() )
						$url = add_query_arg( 'paged', get_query_var( 'paged' ), $url );
					else {
						if ( is_front_page() ) {
							$base = $GLOBALS['wp_rewrite']->using_index_permalinks() ? 'index.php/' : '/';
							$url = home_url( $base );
						}
						$url = user_trailingslashit( trailingslashit( $url ).trailingslashit( $wp_rewrite->pagination_base ).get_query_var( 'paged' ) );
					}
				}
			}

			// fallback for themes and plugins that don't use the standard wordpress functions/variables
			if ( empty ( $url ) ) {
				$url = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';
				$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				// strip out tracking query arguments by facebook, google, etc.
				$url = preg_replace( '/([\?&])(fb_action_ids|fb_action_types|fb_source|fb_aggregation_id|utm_source|utm_medium|utm_campaign|utm_term|gclid|pk_campaign|pk_kwd)=[^&]*&?/i', '$1', $url );
			}

			return apply_filters( $this->p->cf['lca'].'_sharing_url', $url, $use_post, $add_page, $source_id );
		}

		public function is_posts_page() {
			return ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}

		public function get_cache_url( $url ) {
			// make sure the cache expiration is greater than 0 hours
			if ( empty( $this->p->cache->file_expire ) ) 
				return $url;
			// facebook javascript does not work when hosted locally
			if ( preg_match( '/:\/\/connect.facebook.net/', $url ) ) 
				return $url;
			return ( apply_filters( $this->p->cf['lca'].'_rewrite_url',
				$this->p->cache->get( $url ) ) );
		}

		public function fix_relative_url( $url = '' ) {
			if ( ! empty( $url ) && strpos( $url, '://' ) === false ) {
				$this->p->debug->log( 'relative url found = '.$url );
				$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
				if ( strpos( $url, '//' ) === 0 )
					$url = $prot.$url;
				elseif ( strpos( $url, '/' ) === 0 ) 
					$url = home_url( $url );
				else {
					$base = $prot.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
					if ( strpos( $base, '?' ) !== false ) {
						$base_parts = explode( '?', $base );
						$base = reset( $base_parts );
					}
					$url = trailingslashit( $base, false ).$url;
				}
				$this->p->debug->log( 'relative url fixed = '.$url );
			}
			return $url;
		}
	
		public static function encode_utf8( $decoded ) {
			if ( ! mb_detect_encoding( $decoded, 'UTF-8') == 'UTF-8' )
				$encoded = utf8_encode( $decoded );
			else $encoded = $decoded;
			return $encoded;
		}

		public static function decode_utf8( $encoded ) {
			// if we don't have something to decode, return immediately
			if ( strpos( $encoded, '&#' ) === false )
				return $encoded;

			// convert certain entities manually to something non-standard
			$encoded = preg_replace( '/&#8230;/', '...', $encoded );

			// if mb_decode_numericentity is not available, return the string un-converted
			if ( ! function_exists( 'mb_decode_numericentity' ) )
				return $encoded;

			$decoded = preg_replace( '/&#\d{2,5};/ue', 
				'self::decode_utf8_entity( \'$0\' )', $encoded );

			return $decoded;
		}

		public static function decode_utf8_entity( $entity ) {
			$convmap = array( 0x0, 0x10000, 0, 0xfffff );
			return mb_decode_numericentity( $entity, $convmap, 'UTF-8' );
		}

		public function limit_text_length( $text, $textlen = 300, $trailing = '' ) {
			$charset = get_bloginfo( 'charset' );
			$text = html_entity_decode( self::decode_utf8( $text ), ENT_QUOTES, $charset );
			$text = preg_replace( '/<\/p>/i', ' ', $text);					// replace end of paragraph with a space
			$text = $this->cleanup_html_tags( $text );					// remove any remaining html tags
			if ( $textlen > 0 ) {
				if ( strlen( $trailing ) > $textlen )
					$trailing = substr( $trailing, 0, $textlen );			// trim the trailing string, if too long
				if ( strlen( $text ) > $textlen ) {
					$text = substr( $text, 0, $textlen - strlen( $trailing ) );
					$text = trim( preg_replace( '/[^ ]*$/', '', $text ) );		// remove trailing bits of words
					$text = preg_replace( '/[,\.]*$/', '', $text );			// remove trailing puntuation
				} else $trailing = '';							// truncate trailing string if text is shorter than limit
				$text = $text.$trailing;						// trim and add trailing string (if provided)
			}
			$text = htmlentities( $text, ENT_QUOTES, $charset, false );			// double_encode = false
			$text = preg_replace( '/&nbsp;/', ' ', $text);					// just in case
			return $text;
		}

		public function cleanup_html_tags( $text, $strip_tags = true ) {
			$text = strip_shortcodes( $text );							// remove any remaining shortcodes
			$text = preg_replace( '/[\r\n\t ]+/s', ' ', $text );					// put everything on one line
			$text = preg_replace( '/<\?.*\?>/i', ' ', $text);					// remove php
			$text = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/i', ' ', $text);			// remove javascript
			$text = preg_replace( '/<style\b[^>]*>(.*?)<\/style>/i', ' ', $text);			// remove inline stylesheets
			$text = preg_replace( '/<!--'.$this->p->cf['lca'].'-ignore-->(.*?)<!--\/'.
				$this->p->cf['lca'].'-ignore-->/i', ' ', $text);				// remove text between comment strings
			if ( $strip_tags == true ) 
				$text = strip_tags( $text );							// remove remaining html tags
			$text = preg_replace( '/  +/s', ' ', $text );						// truncate multiple spaces
			return trim( $text );
		}

		public function parse_readme( $expire_secs = 0 ) {
			$this->p->debug->args( array( 'expire_secs' => $expire_secs ) );
			$readme = '';
			$use_local = false;	// fetch readme from wordpress.org by default
			$plugin_info = array();

			if ( $this->p->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'(file:'.$this->p->cf['url']['readme'].')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': plugin_info transient salt '.$cache_salt );
				$plugin_info = get_transient( $cache_id );
				if ( is_array( $plugin_info ) ) {
					$this->p->debug->log( $cache_type.': plugin_info retrieved from transient '.$cache_id );
					return $plugin_info;
				}
			} else $use_local = true;	// use local if we cannot cache the readme

			// get remote readme.txt file
			if ( ! $use_local )
				$readme = $this->p->cache->get( $this->p->cf['url']['readme'], 'raw', 'file', $expire_secs );

			// fallback to local readme.txt file
			if ( empty( $readme ) && $fh = @fopen( constant( $this->p->cf['uca'].'_PLUGINDIR' ).'readme.txt', 'rb' ) ) {
				$use_local = true;
				$readme = fread( $fh, filesize( constant( $this->p->cf['uca'].'_PLUGINDIR' ).'readme.txt' ) );
				fclose( $fh );
			}

			if ( ! empty( $readme ) ) {
				$parser = new SuextParseReadme( $this->p->debug );
				$plugin_info = $parser->parse_readme_contents( $readme );

				// remove possibly inaccurate information from local file
				if ( $use_local ) {
					foreach ( array( 'stable_tag', 'upgrade_notice' ) as $key )
						if ( array_key_exists( $key, $plugin_info ) )
							unset( $plugin_info[$key] );
				}
			}

			// save the parsed readme (aka $plugin_info) to the transient cache
			if ( $this->p->is_avail['cache']['transient'] ) {
				set_transient( $cache_id, $plugin_info, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': plugin_info saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			return $plugin_info;
		}

		public function get_admin_url( $submenu = '', $link_text = '' ) {
			$query = '';
			$hash = '';
			$url = '';

			if ( strpos( $submenu, '#' ) !== false )
				list( $submenu, $hash ) = explode( '#', $submenu );
			if ( strpos( $submenu, '?' ) !== false )
				list( $submenu, $query ) = explode( '?', $submenu );

			if ( $submenu == '' ) {
				$current = $_SERVER['REQUEST_URI'];
				if ( preg_match( '/^.*\?page='.$this->p->cf['lca'].'-([^&]*).*$/', $current, $match ) )
					$submenu = $match[1];
				else $submenu = key( $this->p->cf['lib']['submenu'] );
			}

			if ( array_key_exists( $submenu, $this->p->cf['lib']['setting'] ) ) {
				$page = 'options-general.php?page='.$this->p->cf['lca'].'-'.$submenu;
				$url = admin_url( $page );
			} elseif ( array_key_exists( $submenu, $this->p->cf['lib']['submenu'] ) ) {
				$page = 'admin.php?page='.$this->p->cf['lca'].'-'.$submenu;
				$url = admin_url( $page );
			} elseif ( array_key_exists( $submenu, $this->p->cf['lib']['sitesubmenu'] ) ) {
				$page = 'admin.php?page='.$this->p->cf['lca'].'-'.$submenu;
				$url = network_admin_url( $page );
			}

			if ( ! empty( $query ) ) 
				$url .= '&'.$query;

			if ( ! empty( $hash ) ) 
				$url .= '#'.$hash;

			if ( empty( $link_text ) ) 
				return $url;
			else return '<a href="'.$url.'">'.$link_text.'</a>';
		}

		public function delete_expired_transients( $all = false ) { 
			global $wpdb, $_wp_using_ext_object_cache;
			if ( $_wp_using_ext_object_cache ) 
				return; 
			$deleted = 0;
			$time = isset ( $_SERVER['REQUEST_TIME'] ) ? (int) $_SERVER['REQUEST_TIME'] : time() ; 
			$dbquery = 'SELECT option_name FROM '.$wpdb->options.' WHERE option_name LIKE \'_transient_timeout_'.$this->p->cf['lca'].'_%\'';
			$dbquery .= $all === true ? ';' : ' AND option_value < '.$time.';'; 
			$expired = $wpdb->get_col( $dbquery ); 
			foreach( $expired as $transient ) { 
				$key = str_replace('_transient_timeout_', '', $transient);
				delete_transient( $key );
				$deleted++;
			}
			return $deleted;
		}

		public function delete_expired_file_cache( $all = false ) {
			$deleted = 0;
			$time = isset ( $_SERVER['REQUEST_TIME'] ) ? (int) $_SERVER['REQUEST_TIME'] : time() ; 
			$time = empty( $this->p->options['plugin_file_cache_hrs'] ) ? 
				$time : $time - ( $this->p->options['plugin_file_cache_hrs'] * 60 * 60 );
			$cachedir = constant( $this->p->cf['uca'].'_CACHEDIR' );
			if ( $dh = opendir( $cachedir ) ) {
				while ( $fn = readdir( $dh ) ) {
					$filepath = $cachedir.$fn;
					if ( ! preg_match( '/^(\..*|index\.php)$/', $fn ) && is_file( $filepath ) && 
						( $all === true || filemtime( $filepath ) < $time ) ) {
						unlink( $filepath );
						$deleted++;
					}
				}
				closedir( $dh );
			}
			return $deleted;
		}

		public function get_max_nums( $post_id ) {
			$og_max = array();
			foreach ( array( 'og_vid_max', 'og_img_max' ) as $max_name ) {
				$num_meta = false;
				if ( ! empty( $post_id ) && 
					array_key_exists( 'postmeta', $this->p->addons ) )
						$num_meta = $this->p->addons['util']['postmeta']->get_options( $post_id, $max_name );
				if ( $num_meta !== false ) {
					$og_max[$max_name] = $num_meta;
					$this->p->debug->log( 'found custom meta '.$max_name.' = '.$num_meta );
				} else $og_max[$max_name] = $this->p->options[$max_name];
			}
			return $og_max;
		}

		public function push_max( &$dst, &$src, $num = 0 ) {
			if ( ! is_array( $dst ) || ! is_array( $src ) ) return false;
			// if the array is not empty, or contains some non-empty values, then push it
			if ( ! empty( $src ) && array_filter( $src ) ) array_push( $dst, $src );
			return $this->slice_max( $dst, $num );	// returns true or false
		}

		public function slice_max( &$arr, $num = 0 ) {
			if ( ! is_array( $arr ) ) return false;
			$has = count( $arr );
			if ( $num > 0 ) {
				if ( $has == $num ) {
					$this->p->debug->log( 'max values reached ('.$has.' == '.$num.')' );
					return true;
				} elseif ( $has > $num ) {
					$this->p->debug->log( 'max values reached ('.$has.' > '.$num.') - slicing array' );
					$arr = array_slice( $arr, 0, $num );
					return true;
				}
			}
			return false;
		}

		public function is_maxed( &$arr, $num = 0 ) {
			if ( ! is_array( $arr ) ) return false;
			if ( $num > 0 && count( $arr ) >= $num ) return true;
			return false;
		}

		// table header with optional tooltip text
		public function th( $title = '', $class = '', $id = '', $atts = null ) {
			if ( ! empty( $this->p->msgs ) ) {
				if ( empty( $id ) ) 
					$tooltip_idx = 'tooltip-'.$title;
				else $tooltip_idx = 'tooltip-'.$id;
				$tooltip_text = $this->p->msgs->get( $tooltip_idx, $atts );	// text is esc_attr()
			}
			return '<th'.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? '' : ' id="'.$id.'"' ).'><p>'.$title.
				( empty( $tooltip_text ) ? '' : $tooltip_text ).'</p></th>';
		}

		public function do_tabs( $prefix = '', $tabs = array(), $tab_rows = array(), $args = array() ) {
			$tab_keys = array_keys( $tabs );
			$default_tab = reset( $tab_keys );
			$prefix = empty( $prefix ) ? '' : '_'.$prefix;
			$class_tabs = 'sucom-metabox-tabs'.( empty( $prefix ) ? '' : ' sucom-metabox-tabs'.$prefix );
			$class_link = 'sucom-tablink'.( empty( $prefix ) ? '' : ' sucom-tablink'.$prefix );
			$class_tab = 'sucom-tab';
			extract( array_merge( array(
				'scroll_to' => '',
			), $args ) );
			echo '<script type="text/javascript">jQuery(document).ready(function(){ 
				sucomTabs(\'', $prefix, '\', \'', $default_tab, '\', \'', $scroll_to, '\'); });</script>
			<div class="', $class_tabs, '">
			<ul class="', $class_tabs, '">';
			foreach ( $tabs as $key => $title ) {
				$href_key = $class_tab.$prefix.'_'.$key;
				echo '<li class="', $href_key, '"><a class="', $class_link, '" href="#', $href_key, '">', $title, '</a></li>';
			}
			echo '</ul>';
			foreach ( $tabs as $key => $title ) {
				$href_key = $class_tab.$prefix.'_'.$key;
				echo '<div class="', $class_tab, ( empty( $prefix ) ? '' : ' '.$class_tab.$prefix ), ' ', $href_key, '">';
				echo '<table class="sucom-setting">';
				if ( ! empty( $tab_rows[$key] ) && is_array( $tab_rows[$key] ) )
					foreach ( $tab_rows[$key] as $num => $row ) 
						echo '<tr class="alt'.( $num % 2 ).'">'.$row.'</tr>';
				echo '</table>';
				echo '</div>';
			}
			echo '</div>';
		}

		public function tweet_max_len( $long_url ) {
			$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url', 
				$long_url, $this->p->options['twitter_shortener'] );
			$twitter_cap_len = $this->p->options['twitter_cap_len'] - strlen( $short_url ) - 1;
			if ( ! empty( $this->p->options['tc_site'] ) && ! empty( $this->p->options['twitter_via'] ) )
				$twitter_cap_len = $twitter_cap_len - strlen( preg_replace( '/^@/', '', 
					$this->p->options['tc_site'] ) ) - 5;	// include 'via' and 2 spaces
			return $twitter_cap_len;
		}

		public function get_source_id( $src_name, $atts = array() ) {
			global $post;
			$use_post = empty( $atts['is_widget'] ) || is_singular() ? true : false;
			$source_id = $src_name.( empty( $atts['css_id'] ) ? 
				'' : '-'.preg_replace( '/^'.$this->p->cf['lca'].'-/','', $atts['css_id'] ) );
			if ( $use_post == true && ! empty( $post ) ) 
				$source_id = $source_id.'-post-'.$post->ID;
			return $source_id;
		}

		public static function array_merge_recursive_distinct( array &$array1, array &$array2 ) {
			$merged = $array1; 
			foreach ( $array2 as $key => &$value ) {
				if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) )
					$merged[$key] = self::array_merge_recursive_distinct( $merged[$key], $value ); 
				else $merged[$key] = $value;
			} 
			return $merged;
		}

		public static function get_lang( $lang = '' ) {
			$ret = array();
			switch ( $lang ) {
				case 'fb' :
				case 'facebook' :
					$ret = array(
						'af_ZA' => 'Afrikaans',
						'sq_AL' => 'Albanian',
						'ar_AR' => 'Arabic',
						'hy_AM' => 'Armenian',
						'az_AZ' => 'Azerbaijani',
						'eu_ES' => 'Basque',
						'be_BY' => 'Belarusian',
						'bn_IN' => 'Bengali',
						'bs_BA' => 'Bosnian',
						'bg_BG' => 'Bulgarian',
						'ca_ES' => 'Catalan',
						'zh_HK' => 'Chinese (Hong Kong)',
						'zh_CN' => 'Chinese (Simplified)',
						'zh_TW' => 'Chinese (Traditional)',
						'hr_HR' => 'Croatian',
						'cs_CZ' => 'Czech',
						'da_DK' => 'Danish',
						'nl_NL' => 'Dutch',
						'en_GB' => 'English (UK)',
						'en_PI' => 'English (Pirate)',
						'en_UD' => 'English (Upside Down)',
						'en_US' => 'English (US)',
						'eo_EO' => 'Esperanto',
						'et_EE' => 'Estonian',
						'fo_FO' => 'Faroese',
						'tl_PH' => 'Filipino',
						'fi_FI' => 'Finnish',
						'fr_CA' => 'French (Canada)',
						'fr_FR' => 'French (France)',
						'fy_NL' => 'Frisian',
						'gl_ES' => 'Galician',
						'ka_GE' => 'Georgian',
						'de_DE' => 'German',
						'el_GR' => 'Greek',
						'he_IL' => 'Hebrew',
						'hi_IN' => 'Hindi',
						'hu_HU' => 'Hungarian',
						'is_IS' => 'Icelandic',
						'id_ID' => 'Indonesian',
						'ga_IE' => 'Irish',
						'it_IT' => 'Italian',
						'ja_JP' => 'Japanese',
						'km_KH' => 'Khmer',
						'ko_KR' => 'Korean',
						'ku_TR' => 'Kurdish',
						'la_VA' => 'Latin',
						'lv_LV' => 'Latvian',
						'fb_LT' => 'Leet Speak',
						'lt_LT' => 'Lithuanian',
						'mk_MK' => 'Macedonian',
						'ms_MY' => 'Malay',
						'ml_IN' => 'Malayalam',
						'ne_NP' => 'Nepali',
						'nb_NO' => 'Norwegian (Bokmal)',
						'nn_NO' => 'Norwegian (Nynorsk)',
						'ps_AF' => 'Pashto',
						'fa_IR' => 'Persian',
						'pl_PL' => 'Polish',
						'pt_BR' => 'Portuguese (Brazil)',
						'pt_PT' => 'Portuguese (Portugal)',
						'pa_IN' => 'Punjabi',
						'ro_RO' => 'Romanian',
						'ru_RU' => 'Russian',
						'sk_SK' => 'Slovak',
						'sl_SI' => 'Slovenian',
						'es_LA' => 'Spanish',
						'es_ES' => 'Spanish (Spain)',
						'sr_RS' => 'Serbian',
						'sw_KE' => 'Swahili',
						'sv_SE' => 'Swedish',
						'ta_IN' => 'Tamil',
						'te_IN' => 'Telugu',
						'th_TH' => 'Thai',
						'tr_TR' => 'Turkish',
						'uk_UA' => 'Ukrainian',
						'vi_VN' => 'Vietnamese',
						'cy_GB' => 'Welsh',
					);
					break;
				case 'gplus' :
				case 'google' :
					$ret = array(
						'af'	=> 'Afrikaans',
						'am'	=> 'Amharic',
						'ar'	=> 'Arabic',
						'eu'	=> 'Basque',
						'bn'	=> 'Bengali',
						'bg'	=> 'Bulgarian',
						'ca'	=> 'Catalan',
						'zh-HK'	=> 'Chinese (Hong Kong)',
						'zh-CN'	=> 'Chinese (Simplified)',
						'zh-TW'	=> 'Chinese (Traditional)',
						'hr'	=> 'Croatian',
						'cs'	=> 'Czech',
						'da'	=> 'Danish',
						'nl'	=> 'Dutch',
						'en-GB'	=> 'English (UK)',
						'en-US'	=> 'English (US)',
						'et'	=> 'Estonian',
						'fil'	=> 'Filipino',
						'fi'	=> 'Finnish',
						'fr'	=> 'French',
						'fr-CA'	=> 'French (Canadian)',
						'gl'	=> 'Galician',
						'de'	=> 'German',
						'el'	=> 'Greek',
						'gu'	=> 'Gujarati',
						'iw'	=> 'Hebrew',
						'hi'	=> 'Hindi',
						'hu'	=> 'Hungarian',
						'is'	=> 'Icelandic',
						'id'	=> 'Indonesian',
						'it'	=> 'Italian',
						'ja'	=> 'Japanese',
						'kn'	=> 'Kannada',
						'ko'	=> 'Korean',
						'lv'	=> 'Latvian',
						'lt'	=> 'Lithuanian',
						'ms'	=> 'Malay',
						'ml'	=> 'Malayalam',
						'mr'	=> 'Marathi',
						'no'	=> 'Norwegian',
						'fa'	=> 'Persian',
						'pl'	=> 'Polish',
						'pt-BR'	=> 'Portuguese (Brazil)',
						'pt-PT'	=> 'Portuguese (Portugal)',
						'ro'	=> 'Romanian',
						'ru'	=> 'Russian',
						'sr'	=> 'Serbian',
						'sk'	=> 'Slovak',
						'sl'	=> 'Slovenian',
						'es'	=> 'Spanish',
						'es-419'	=> 'Spanish (Latin America)',
						'sw'	=> 'Swahili',
						'sv'	=> 'Swedish',
						'ta'	=> 'Tamil',
						'te'	=> 'Telugu',
						'th'	=> 'Thai',
						'tr'	=> 'Turkish',
						'uk'	=> 'Ukrainian',
						'ur'	=> 'Urdu',
						'vi'	=> 'Vietnamese',
						'zu'	=> 'Zulu',
					);
					break;
				case 'twitter' :
					$ret = array(
						'ar'	=> 'Arabic',
						'ca'	=> 'Catalan',
						'cs'	=> 'Czech',
						'da'	=> 'Danish',
						'de'	=> 'German',
						'el'	=> 'Greek',
						'en'	=> 'English',
						'en-gb'	=> 'English UK',
						'es'	=> 'Spanish',
						'eu'	=> 'Basque',
						'fa'	=> 'Farsi',
						'fi'	=> 'Finnish',
						'fil'	=> 'Filipino',
						'fr'	=> 'French',
						'gl'	=> 'Galician',
						'he'	=> 'Hebrew',
						'hi'	=> 'Hindi',
						'hu'	=> 'Hungarian',
						'id'	=> 'Indonesian',
						'it'	=> 'Italian',
						'ja'	=> 'Japanese',
						'ko'	=> 'Korean',
						'msa'	=> 'Malay',
						'nl'	=> 'Dutch',
						'no'	=> 'Norwegian',
						'pl'	=> 'Polish',
						'pt'	=> 'Portuguese',
						'ro'	=> 'Romanian',
						'ru'	=> 'Russian',
						'sv'	=> 'Swedish',
						'th'	=> 'Thai',
						'tr'	=> 'Turkish',
						'uk'	=> 'Ukrainian',
						'ur'	=> 'Urdu',
						'xx-lc'	=> 'Lolcat',
						'zh-tw'	=> 'Traditional Chinese',
						'zh-cn'	=> 'Simplified Chinese',
	
					);
					break;
			}
			asort( $ret );
			return $ret;
		}
	}
}

?>
