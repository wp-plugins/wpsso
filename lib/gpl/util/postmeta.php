<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUtilPostmeta' ) && class_exists( 'WpssoPostmeta' ) ) {

	class WpssoUtilPostmeta extends WpssoPostmeta {

		// parent __construct calls add_actions()
		protected function add_actions() {
			if ( ! is_admin() )
				return;

			if ( $this->p->is_avail['opengraph'] )
				add_action( 'admin_head', array( &$this, 'set_header_tags' ) );

			add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );
			add_action( 'save_post', array( &$this, 'flush_cache' ), 20 );
			add_action( 'edit_attachment', array( &$this, 'flush_cache' ), 20 );
		}
	}
}

?>
