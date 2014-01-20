<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoAdminSharing' ) ) {

	class WpssoAdminSharing {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_XXXXXXX_rows' => 2,
			) );
		}

		public function filter_plugin_XXXXXXX_rows( $rows, $form ) {
			return $rows;
		}
	}
}

?>
