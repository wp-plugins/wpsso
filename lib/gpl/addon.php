<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoAddonGpl' ) ) {

	class WpssoAddonGpl {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->load_addons();
		}

		private function load_addons() {

			if ( is_admin() &&
				file_exists( WPSSO_PLUGINDIR.'lib/gpl/admin.php' ) ) {
				require_once ( WPSSO_PLUGINDIR.'lib/gpl/admin.php' );
				foreach ( $this->p->cf['lib']['submenu'] as $id => $name ) {
					$classname = $this->p->cf['cca'].'Admin'.ucfirst( $id ).'Gpl';
					if ( class_exists( $classname ) )
						$this->p->admin->submenu[$id] = new $classname( $this->p, $id, $name );
				}
			}

			if ( file_exists( WPSSO_PLUGINDIR.'lib/gpl/postmeta.php' ) ) {
				require_once ( WPSSO_PLUGINDIR.'lib/gpl/postmeta.php' );
				$this->p->meta = new WpssoPostMetaGpl( $this->p );
			}
		}
	}
}

?>
