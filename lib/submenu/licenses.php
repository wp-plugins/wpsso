<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSubmenuLicenses' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSubmenuLicenses extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_licenses', 'Licenses', array( &$this, 'show_metabox_licenses' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_licenses() {
			echo '<table class="sucom-setting licenses-metabox">';
			echo '<tr><td colspan="3">'.$this->p->msgs->get( 'info-plugin-tid' ).'</td></tr>';
			echo '<tr><td></td>'.$this->p->util->th( 'Authentication ID', 'left' ).'</tr>';
			foreach ( $this->p->cf['plugin'] as $lca => $info ) {
				$qty_used = class_exists( 'SucomUpdate' ) ?
					SucomUpdate::get_option( $lca, 'qty_used' ) : false;

				echo '<tr>';
				if ( empty( $info['purchase'] ) )
					echo $this->p->util->th( $info['name'], 'nowrap' );
				else echo $this->p->util->th( '<a href="'.$info['purchase'].'" target="_blank">'.$info['name'].'</a>', 'nowrap' );

				if ( $this->p->cf['lca'] === $lca || $this->p->check->is_aop() )
					echo '<td class="medium">'.$this->form->get_input( 'plugin_'.$lca.'_tid', 'medium mono' );
				else echo '<td class="medium blank">'.$this->form->get_fake_input( 'plugin_'.$lca.'_tid', 'medium mono' );

				echo '</td><td>';
				if ( ! empty( $qty_used ) ) 
					echo '<p>'.$qty_used.' Licenses Assigned</p>';
				echo '</td></tr>';
			}
			echo '</table>';
		}
	}
}

?>
