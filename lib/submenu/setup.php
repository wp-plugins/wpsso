<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSubmenuSetup' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSubmenuSetup extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_review', 'Good Plugin or Support?', array( &$this, 'show_metabox_review' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_guide', 'A Setup Guide', array( &$this, 'show_metabox_guide' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_review() {
			echo '<table class="sucom-setting setup-metabox"><tr><td>';
			echo $this->p->msgs->get( 'review-info' );
			echo '</td></tr></table>';
		}
		
		public function show_metabox_guide() {
			$content = false;
			$get_remote = true;
			$expire_secs = $this->p->cf['update_hours'] * 3600;
			if ( $this->p->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'(file:'.$this->p->cf['url']['setup'].')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': transient salt '.$cache_salt );
				$content = get_transient( $cache_id );
				if ( $content !== false )
					$this->p->debug->log( $cache_type.': setup guide retrieved from transient '.$cache_id );
			} else $get_remote = false;	// use local if transient cache is disabled

			if ( $content === false && 
				$get_remote === true && 
				$expire_secs > 0 )
					$content = $this->p->cache->get( $this->p->cf['url']['setup'], 'raw', 'file', $expire_secs );

			// fallback to local setup.html file
			if ( empty( $content ) && 
				$fh = @fopen( constant( $this->p->cf['uca'].'_PLUGINDIR' ).'setup.html', 'rb' ) ) {

				$get_remote = false;
				$content = fread( $fh, filesize( constant( $this->p->cf['uca'].'_PLUGINDIR' ).'setup.html' ) );
				fclose( $fh );
			}

			if ( $this->p->is_avail['cache']['transient'] ) {
				set_transient( $cache_id, $content, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': plugin_info saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			echo '<table class="sucom-setting setup-metabox"><tr><td>';
			echo $content;
			echo '</td></tr></table>';
		}
	}
}

?>
