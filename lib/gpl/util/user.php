<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUtilUser' ) && class_exists( 'WpssoUser' ) ) {

	class WpssoUtilUser extends WpssoUser {

		// parent __construct calls add_actions()
		protected function add_actions() {
			add_filter( 'user_contactmethods', array( &$this, 'add_contact_methods' ), 20, 1 );

			if ( ! is_admin() )
				return;

			if ( $this->p->is_avail['opengraph'] )
				add_action( 'admin_head', array( &$this, 'set_header_tags' ) );

			add_action( 'admin_init', array( &$this, 'add_metaboxes' ) );
			add_action( 'show_user_profile', array( $this, 'show_metabox' ) );
			add_action( 'edit_user_profile', array( $this, 'show_metabox' ) );
			add_action( 'edit_user_profile_update', array( &$this, 'sanitize_contact_methods' ) );
			add_action( 'personal_options_update', array( &$this, 'sanitize_contact_methods' ) ); 
		}
	}
}

?>
