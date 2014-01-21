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
			foreach ( $this->p->cf['lib']['gpl'] as $sub => $libs ) {
				if ( $sub === 'admin' && ! is_admin() )	// only load admin menus and tabs in admin
					continue;
				foreach ( $libs as $id => $name ) {
					if ( $this->p->is_avail[$sub][$id] && 
						file_exists( WPSSO_PLUGINDIR.'lib/gpl/'.$sub.'/'.$id.'.php' ) ) {
						require_once ( WPSSO_PLUGINDIR.'lib/gpl/'.$sub.'/'.$id.'.php' );
						$classname = $this->p->cf['cca'].ucfirst( $sub ).ucfirst( $id );
						if ( class_exists( $classname ) )
							$this->p->addons[$id] = new $classname( $this->p );
					}
				}
			}
		}
	}
}

?>
