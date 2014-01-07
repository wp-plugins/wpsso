<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-014 - Jean-Sebastien Morisset - http://surniaulula.com/
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

		public function get_post_types( $prefix, $output = 'objects' ) {
			$include = false;
			$post_types = array();
			switch ( $prefix ) {
				case 'buttons':
					$include = array( 'public' => true );
					break;
				case 'plugin':
					$include = array( 'show_ui' => true, 'public' => true );
					break;
			}
			$post_types = $include !== false ? 
				get_post_types( $include, $output ) : array();
			return apply_filters( $this->p->cf['lca'].'_post_types', $post_types, $prefix, $output );
		}

		public function flush_post_cache( $post_id ) {
			switch ( get_post_status( $post_id ) ) {

			case 'draft' :
			case 'pending' :
			case 'private' :
			case 'publish' :
				$lang = get_locale();
				$name = is_page( $post_id ) ? 'Page' : 'Post';
				$cache_type = 'object cache';
				$sharing_url = $this->p->util->get_sharing_url( $post_id );
				$transients = array(
					'WpssoOpengraph::get_array' => array(
						'og array' => 'lang:'.$lang.'_sharing_url:'.$sharing_url,
					),
					'WpssoSocial::filter' => array(
						'the_excerpt' => 'lang:'.$lang.'_post:'.$post_id.'_type:the_excerpt',
						'the_content' => 'lang:'.$lang.'_post:'.$post_id.'_type:the_content',
						'admin_sharing' => 'lang:'.$lang.'_post:'.$post_id.'_type:admin_sharing',
					),
				);
				$objects = array(
					'SucomWebpage::get_content' => array(
						'filtered content' => 'lang:'.$lang.'_post:'.$post_id.'_filtered',
						'unfiltered content' => 'lang:'.$lang.'_post:'.$post_id.'_unfiltered',
					),
					'SucomWebpage::get_hashtags' => array(
						'hashtags' => 'lang:'.$lang.'_post:'.$post_id,
					),
				);
				$deleted = 0;
				foreach ( $transients as $group => $arr ) {
					foreach ( $arr as $name => $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( delete_transient( $cache_id ) ) $deleted++;
					}
				}
				foreach ( $objects as $group => $arr ) {
					foreach ( $arr as $name => $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( wp_cache_delete( $cache_id, $group ) ) $deleted++;
					}
				}
				if ( $deleted > 0 && $this->p->debug->is_on() )
					$this->p->notice->inf( $deleted.' items flushed from the WordPress object and transient caches for post ID #'.$post_id, true );
				break;
			}
		}
	}
}

?>
