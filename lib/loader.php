<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoLoader' ) ) {

	class WpssoLoader {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->addons();
		}

		private function addons() {
			foreach ( $this->p->cf['plugin'] as $lca => $info ) {
				$type = $this->p->check->is_aop( $lca ) ? 'pro' : 'gpl';
				echo $lca.'<br/>';
				echo SucomUpdate::get_umsg( $lca ).'<br/>';
				foreach ( $info['lib'][$type] as $sub => $lib ) {
					if ( $sub === 'admin' && ! is_admin() )
						continue;
					foreach ( $lib as $id => $name ) {
						if ( $this->p->is_avail[$sub][$id] ) {
							$classname = apply_filters( $lca.'_load_lib', false, "$type/$sub/$id" );
							echo $classname.'<br/>';
							if ( $classname !== false && class_exists( $classname ) )
								$this->p->addons[$sub][$id] = new $classname( $this->p );
						}
					}
				}
			}
		}
	}
}

?>
