<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSitesubmenuSitelicenses' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSitesubmenuSitelicenses extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function set_form() {
			$def_site_opts = $this->p->opt->get_site_defaults();
			$this->form = new SucomForm( $this->p, WPSSO_SITE_OPTIONS_NAME, $this->p->site_options, $def_site_opts );
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_licenses', 'License Network Settings', array( &$this, 'show_metabox_licenses' ), $this->pagehook, 'normal' );

			// add a class to set a minimum width for the network postboxes
			add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_licenses', array( &$this, 'add_class_postbox_network' ) );
		}

		public function add_class_postbox_network( $classes ) {
			array_push( $classes, 'admin_postbox_network' );
			return $classes;
		}

		public function show_metabox_licenses() {
			echo '<table class="sucom-setting licenses-metabox" style="padding-bottom:10px">';
			echo '<tr><td colspan="6">'.$this->p->msgs->get( 'info-plugin-tid' ).'</td></tr>';

			foreach ( $this->p->cf['plugin'] as $lca => $info ) {
				$qty_used = class_exists( 'SucomUpdate' ) ?
					SucomUpdate::get_option( $lca, 'qty_used' ) : false;

				if ( ! empty( $info['url']['purchase'] ) )
					$url = $info['url']['purchase'];
				elseif ( ! empty( $info['url']['download'] ) )
					$url = $info['url']['download'];
				else $url = '';

				// logo image
				echo '<tr><td style="width:140px;padding:10px 0;" rowspan="4" valign="top" align="left">';
				if ( ! empty( $info['img']['logo-125x125'] ) ) {
					if ( ! empty( $url ) )
						echo '<a href="'.$url.'" target="_blank">';
					echo '<img src="'.$info['img']['logo-125x125'].'" width="125" height="125">';
					if ( ! empty( $url ) )
						echo '</a>';
				}
				echo '</td>'."\n";

				// plugin name
				echo '<td colspan="3" style="padding-top:10px;"><strong>';
				if ( ! empty( $url ) )
					echo '<a href="'.$url.'" target="_blank">'.$info['name'].'</a>';
				else echo $info['name'];
				echo '</strong></td></tr>';

				// plugin description
				echo '<tr><td colspan="3">';
				if ( ! empty( $info['desc'] ) )
					echo '<p>'.$info['desc'].'</p>';
				echo '</td></tr>';

				// authentication ID and site use rows
				if ( ! empty( $info['url']['purchase'] ) || ! empty( $this->p->options['plugin_'.$lca.'_tid'] ) ) {
					echo '<tr>'.$this->p->util->th( 'Authentication ID', 'medium' );
					if ( $this->p->cf['lca'] === $lca || $this->p->check->is_aop() )
						echo '<td class="medium">'.$this->form->get_input( 'plugin_'.$lca.'_tid', 'medium mono' );
					else echo '<td class="medium blank">'.$this->form->get_no_input( 'plugin_'.$lca.'_tid', 'medium mono' );
					echo '</td><td>';
					if ( ! empty( $qty_used ) ) 
						echo '<p>'.$qty_used.' Licenses Assigned</p>';
					else echo '&nbsp;';
					echo '</td></tr>';

					echo '<tr>'.$this->p->util->th( 'Site Use', 'medium' );
					echo '<td colspan="2">';
					echo $this->form->get_select( 'plugin_'.$lca.'_tid:use', $this->p->cf['form']['site_option_use'], ' site_use' );
					echo '</td></tr>';
				} else {
					echo '<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>';
				}
			}
			echo '</table>';
		}
	}
}

?>
