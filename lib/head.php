<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoHead' ) ) {

	class WpssoHead {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			add_action( 'wp_head', array( &$this, 'add_header' ), WPSSO_HEAD_PRIORITY );
		}

		// called by WP wp_head action
		public function add_header() {
			// add various function test results top-most in the debug log
			// hook into wpsso_is_functions to extend the default array of function names
			if ( $this->p->debug->is_on() ) {
				$is_functions = array( 
					'is_author',
					'is_archive',
					'is_category',
					'is_tag',
					'is_home',
					'is_search',
					'is_singular',
					'is_attachment',
					'is_product',
					'is_product_category',
					'is_product_tag',
				);
				$is_functions = apply_filters( $this->p->cf['lca'].'_is_functions', $is_functions );
				foreach ( $is_functions as $function ) 
					if ( function_exists( $function ) && $function() )
						$this->p->debug->log( $function.'() = true' );
			}
			if ( $this->p->is_avail['metatags'] ) {
				if ( $this->p->is_avail['opengraph'] )
					echo $this->get_header_html( $this->p->og->get_array() );
				else echo $this->get_header_html();
			} else echo "\n<!-- ".$this->p->cf['lca']." meta tags are disabled -->\n";

			if ( $this->p->debug->is_on() ) {
				$defined_constants = get_defined_constants( true );
				$defined_constants['user']['WPSSO_NONCE'] = '********';
				$this->p->debug->show_html( SucomUtil::preg_grep_keys( '/^WPSSO_/', $defined_constants['user'] ), 'wpsso constants' );

				$opts = $this->p->options;
				foreach ( $opts as $key => $val ) {
					switch ( $key ) {
						case ( strpos( $key, 'buttons_css_' ) ):
						case ( strpos( $key, 'buttons_js_' ) ):
						case ( preg_match( '/_key$/', $key ) ? true : false ):
						case 'plugin_tid':
							$opts[$key] = '********';
					}
				}
				$this->p->debug->show_html( print_r( $this->p->is_avail, true ), 'available features' );
				$this->p->debug->show_html( print_r( $this->p->check->get_active(), true ), 'active plugins' );
				$this->p->debug->show_html( null, 'debug log' );
				$this->p->debug->show_html( $opts, 'wpsso settings' );

				if ( ( $obj = $this->p->util->get_the_object() ) !== false ) {
					$post_id = empty( $obj->ID ) ? 0 : $obj->ID;
					if ( ! empty( $post_id ) && isset( $this->p->addons['util']['postmeta'] ) ) {
						$post_opts = $this->p->addons['util']['postmeta']->get_options( $post_id );
						$this->p->debug->show_html( $post_opts, 'wpsso post_id '.$post_id.' custom settings' );
					}
				}
			}
		}

		// called from add_header() and the work/header.php template
		// input array should be from transient cache
		public function get_header_html( $meta = array(), $use_post = false ) {
		
			$obj = $this->p->util->get_the_object( $use_post );
			$post_id = empty( $obj->ID ) ? 0 : $obj->ID;
			$this->p->debug->log( 'using post_id '.$post_id );
		
			$html = "\n<!-- ".$this->p->cf['lca']." meta tags begin -->\n";
			if ( $this->p->is_avail['aop'] )
				$html .= "<!-- updates: ".$this->p->cf['url']['pro_update']." -->\n";

			// show the array structure before the html block
			if ( $this->p->debug->is_on() ) {
				$html .= $this->p->debug->get_html( print_r( $meta, true ), 'open graph array' );
				$html .= $this->p->debug->get_html( print_r( $this->p->util->get_urls_found(), true ), 'media urls found' );
			}

			$html .= '<meta name="generator" content="'.$this->p->cf['full'].' '.$this->p->cf['version'];
			if ( $this->p->check->is_aop() ) $html .= 'L';
			elseif ( $this->p->is_avail['aop'] ) $html .= 'U';
			else $html .= 'G';
			$html .= '" />'."\n";

			/*
			 * Schema meta tags
			 */
			$meta_schema = array();
			if ( array_key_exists( 'og:description', $meta ) )
				$meta_schema['description'] = $meta['og:description'];
			$meta_schema = apply_filters( $this->p->cf['lca'].'_meta_schema', $meta_schema );

			/*
			 * Link rel tags
			 */
			$link_rel = array();
			if ( array_key_exists( 'link_rel:publisher', $meta ) ) {
				$link_rel['publisher'] = $meta['link_rel:publisher'];
				unset ( $meta['link_rel:publisher'] );
			} elseif ( ! empty( $this->p->options['link_publisher_url'] ) )
				$link_rel['publisher'] = $this->p->options['link_publisher_url'];

			if ( array_key_exists( 'link_rel:author', $meta ) ) {
				$link_rel['author'] = $meta['link_rel:author'];
				unset ( $meta['link_rel:author'] );
			} else {
				// check for single/attachment page, or admin editing page
				if ( is_singular() || $use_post !== false ) {
					if ( ! empty( $obj->post_author ) )
						$link_rel['author'] = $this->p->user->get_author_url( $obj->post_author, 
							$this->p->options['link_author_field'] );
					elseif ( ! empty( $this->p->options['link_def_author_id'] ) )
						$link_rel['author'] = $this->p->user->get_author_url( $this->p->options['link_def_author_id'], 
							$this->p->options['link_author_field'] );

				// check for default author info on indexes and searches
				} elseif ( ( ! ( is_singular() || $use_post !== false ) && 
					! is_search() && ! empty( $this->p->options['link_def_author_on_index'] ) && ! empty( $this->p->options['link_def_author_id'] ) )
					|| ( is_search() && ! empty( $this->p->options['link_def_author_on_search'] ) && ! empty( $this->p->options['link_def_author_id'] ) ) ) {

					$link_rel['author'] = $this->p->user->get_author_url( $this->p->options['link_def_author_id'], 
						$this->p->options['link_author_field'] );
				}
			}
			$link_rel = apply_filters( $this->p->cf['lca'].'_link_rel', $link_rel );

			/*
			 * Additional meta name / property tags
			 */
			if ( ! empty( $this->p->options['meta_name_description'] ) ) {
				if ( ! array_key_exists( 'description', $meta ) ) {
					if ( ! empty( $post_id ) && ( is_singular() || $use_post !== false ) )
						$meta['description'] = $this->p->addons['util']['postmeta']->get_options( $post_id, 'seo_desc' );
					if ( empty( $meta['description'] ) )
						$meta['description'] = $this->p->webpage->get_description( $this->p->options['seo_desc_len'], '...',
							$use_post, true, false );	// use_post = false, use_cache = true, add_hashtags = false
				}
			}
			$meta = apply_filters( $this->p->cf['lca'].'_meta', $meta );

			/*
			 * Print all the link / meta arrays as HTML
			 */
			foreach ( $link_rel as $key => $val )
				if ( ! empty( $val ) )
					$html .= '<link rel="'.$key.'" href="'.$val.'" />'."\n";

			$html .= $this->get_meta_for_type( 'itemprop', $meta_schema );
			$html .= $this->get_meta_for_type( 'property', $meta );
			$html .= "<!-- ".$this->p->cf['lca']." meta tags end -->\n";
			return $html;
		}

	
		private function get_meta_for_type( $type = 'property', $meta ) {
			$this->p->debug->log( count( $meta ).' meta '.$type.' to process' );
			$this->p->debug->log( $meta );
			$html = '';
			foreach ( $meta as $f_name => $f_val ) {					// 1st-dimension array (associative)
				if ( is_array( $f_val ) ) {
					if ( empty( $f_val ) )
						$html .= $this->get_single_meta( $type, $f_name );	// possibly show an empty tag (depends on og_empty_tags value)
					else {
						foreach ( $f_val as $s_num => $s_val ) {		// 2nd-dimension array
							if ( SucomUtil::is_assoc( $s_val ) ) {
								ksort( $s_val );
								foreach ( $s_val as $t_name => $t_val )	// 3rd-dimension array (associative)
									$html .= $this->get_single_meta( $type, $t_name, $t_val, $f_name.':'.( $s_num + 1 ) );
							} else $html .= $this->get_single_meta( $type, $f_name, $s_val, $f_name.':'.( $s_num + 1 ) );
						}
					}
				} else $html .= $this->get_single_meta( $type, $f_name, $f_val );
			}
			return $html;
		}

		private function get_single_meta( $type = 'property', $prop_val, $content = '', $comment = '' ) {

			// known exceptions for the property $type
			if ( $type === 'property' && 
				( $prop_val === 'description' || 
					strpos( $prop_val, 'twitter:' ) === 0 ) )
						$type = 'name';

			$html = '';
			$log_pre = 'meta '.$type.' '.$prop_val;

			if ( empty( $this->p->options['meta_'.$type.'_'.$prop_val] ) ) {
				$this->p->debug->log( $log_pre.' is disabled (skipped)' );
				return $html;

			} elseif ( $content === -1 ) {
				$this->p->debug->log( $log_pre.' is -1 (skipped)' );
				return $html;

			// ignore empty values, except for open graph (og, article, etc.) meta tags when og_empty_tags option is checked
			} elseif ( ( $content === '' || $content === null ) && 
				( preg_match( '/^description|fb:|twitter:/', $prop_val ) || 
					empty( $this->p->options['og_empty_tags'] ) ) ) {

				$this->p->debug->log( $log_pre.' is empty (skipped)' );
				return $html;

			} elseif ( is_object( $content ) ) {
				$this->p->debug->log( $log_pre.' value is an object (skipped)' );
				return $html;
			}

			$charset = get_bloginfo( 'charset' );
			$content = htmlentities( $content, ENT_QUOTES, $charset, false );	// double_encode = false

			$this->p->debug->log( 'meta '.$type.' '.$prop_val.' = "'.$content.'"' );
			if ( $comment ) 
				$html .= "<!-- $comment -->";

			// add an additional secure_url meta tag for open graph images and videos
			if ( ( $prop_val === 'og:image' || $prop_val === 'og:video' ) && 
				strpos( $content, 'https:' ) === 0 && 
				! empty( $this->p->options['meta_'.$type.'_'.$prop_val.':secure_url'] ) ) {

				$http_url = preg_replace( '/^https:/', 'http:', $content );
				$html .= '<meta '.$type.'="'.$prop_val.'" content="'.$http_url.'" />'."\n";
				if ( $comment ) 
					$html .= "<!-- $comment -->";
				$html .= '<meta '.$type.'="'.$prop_val.':secure_url" content="'.$content.'" />'."\n";

			} else $html .= '<meta '.$type.'="'.$prop_val.'" content="'.$content.'" />'."\n";

			return $html;
		}
	}
}

?>
