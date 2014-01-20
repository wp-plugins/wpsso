<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSubmenuAdvanced' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSubmenuAdvanced extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_plugin', 'Plugin Settings', array( &$this, 'show_metabox_plugin' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_contact', 'Profile Contact Methods', array( &$this, 'show_metabox_contact' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_taglist', 'Meta Tag List', array( &$this, 'show_metabox_taglist' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_plugin() {
			$metabox = 'plugin';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'activation' => 'Activate and Update',
				'content' => 'Content and Filters',
				'cache' => 'File and Object Cache' ) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_contact() {
			$metabox = 'cm';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'custom' => 'Custom Contacts',
				'builtin' => 'Built-In Contacts' ) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );

			echo '<table class="sucom-setting" style="padding-bottom:0"><tr><td>'.
			$this->p->msgs->get( $metabox.'-info' ).'</td></tr></table>';
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_taglist() {
			$metabox = 'taglist';
			echo '<table class="sucom-setting" style="padding-bottom:0;"><tr><td>'.
			$this->p->msgs->get( $metabox.'-info' ).'</td></tr></table>';
			echo '<table class="sucom-setting" style="padding-bottom:0;">';
			foreach ( apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tags_rows', array(), $this->form ) as $num => $row ) 
				echo '<tr>', $row, '</tr>';
			echo '</table>';
			echo '<table class="sucom-setting"><tr>';
			echo $this->p->util->th( 'Include Empty og:* Meta Tags', null, 'og_empty_tags' );
			echo '<td'.( $this->p->check->is_aop() ? '>'.$this->form->get_checkbox( 'og_empty_tags' ) :
				' class="blank checkbox">'.$this->form->get_fake_checkbox( 'og_empty_tags' ) ).'</td>';
			echo '<td width="100%"></td></tr></table>';

		}

		protected function get_rows( $metabox, $key ) {
			$ret = array();
			switch ( $metabox.'-'.$key ) {
				case 'cm-custom' :
					if ( ! $this->p->check->is_aop() )
						$ret[] = '<td colspan="4" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

					$ret[] = '<td></td>'.
					$this->p->util->th( 'Show', 'left checkbox' ).
					$this->p->util->th( 'Contact Field Name', 'left medium', 'custom-cm-field-name' ).
					$this->p->util->th( 'Profile Contact Label', 'left wide' );

					$sorted_opt_pre = $this->p->cf['opt']['pre'];
					ksort( $sorted_opt_pre );
					foreach ( $sorted_opt_pre as $id => $pre ) {
						$cm_opt = 'plugin_cm_'.$pre.'_';

						// check for the lib website classname for a nice 'display name'
						$name = empty( $this->p->cf['lib']['website'][$id] ) ? 
							ucfirst( $id ) : $this->p->cf['lib']['website'][$id];
						$name = $name == 'GooglePlus' ? 'Google+' : $name;

						// not all social websites have a contact method field
						if ( array_key_exists( $cm_opt.'enabled', $this->p->options ) ) {
							if ( $this->p->check->is_aop() ) {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="checkbox">'.$this->form->get_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'label' ).'</td>';
							} else {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="blank checkbox">'.$this->form->get_fake_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td class="blank medium">'.$this->form->get_hidden( $cm_opt.'name' ).
								$this->p->options[$cm_opt.'name'].'</td>'.
								'<td class="blank">'.$this->form->get_hidden( $cm_opt.'label' ).
								$this->p->options[$cm_opt.'label'].'</td>';
							}
						}
					
					}
					break;

				case 'cm-builtin' :
					if ( ! $this->p->check->is_aop() )
						$ret[] = '<td colspan="4" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

					$ret[] = '<td></td>'.
					$this->p->util->th( 'Show', 'left checkbox' ).
					$this->p->util->th( 'Contact Field Name', 'left medium', 'wp-cm-field-name' ).
					$this->p->util->th( 'Profile Contact Label', 'left wide' );

					$sorted_wp_contact = $this->p->cf['wp']['cm'];
					ksort( $sorted_wp_contact );
					foreach ( $sorted_wp_contact as $id => $name ) {
						$cm_opt = 'wp_cm_'.$id.'_';
						if ( array_key_exists( $cm_opt.'enabled', $this->p->options ) ) {
							if ( $this->p->check->is_aop() ) {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="checkbox">'.$this->form->get_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_fake_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'label' ).'</td>';
							} else {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="blank checkbox">'.$this->form->get_hidden( $cm_opt.'enabled' ).
									$this->form->get_fake_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_fake_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td class="blank">'.$this->form->get_hidden( $cm_opt.'label' ).
									$this->p->options[$cm_opt.'label'].'</td>';
							}
						}
					}
					break;

				case 'plugin-activation':
					if ( is_multisite() && ! empty( $this->p->site_options['plugin_tid:use'] ) && 
						$this->p->site_options['plugin_tid:use'] == 'force' )
							$input = $this->form->get_fake_input( 'plugin_tid', 'mono' );
					else $input = $this->form->get_input( 'plugin_tid', 'mono' );

					$ret[] = $this->p->util->th( 'Pro Version Authentication ID', 'highlight', 'plugin_tid' ).'<td>'.$input.'</td>';

					if ( $this->p->is_avail['aop'] )
						$ret[] = '<th></th><td>'.$this->p->msgs->get( 'auth-id-info' ).'</td>';

					$ret[] = $this->p->util->th( 'Preserve Settings on Uninstall', 'highlight', 'plugin_preserve' ).
					'<td>'.$this->form->get_checkbox( 'plugin_preserve' ).'</td>';

					$ret[] = $this->p->util->th( 'Add Hidden Debug Info', null, 'plugin_debug' ).
					'<td>'.$this->form->get_checkbox( 'plugin_debug' ).'</td>';
					break;

				case 'plugin-content':
					$ret[] = $this->p->util->th( 'Apply Content Filters', null, 'plugin_filter_content' ).
					'<td>'.$this->form->get_checkbox( 'plugin_filter_content' ).'</td>';

					$ret[] = $this->p->util->th( 'Apply Excerpt Filters', null, 'plugin_filter_excerpt' ).
					'<td>'.$this->form->get_checkbox( 'plugin_filter_excerpt' ).'</td>';

					if ( $this->p->is_avail['ssb'] )
						$ret[] = $this->p->util->th( 'Enable Shortcode(s)', 'highlight', 'plugin_shortcode_wpsso' ).
						'<td>'.$this->form->get_checkbox( 'plugin_shortcode_wpsso' ).'</td>';

					$ret[] =  $this->p->util->th( 'Auto-Resize Images', null, 'plugin_auto_img_resize' ).
					'<td>'.$this->form->get_checkbox( 'plugin_auto_img_resize' ).'</td>';

					$ret[] =  $this->p->util->th( 'Ignore Small Images', null, 'plugin_ignore_small_img' ).
					'<td>'.$this->form->get_checkbox( 'plugin_ignore_small_img' ).'</td>';
					break;

				case 'plugin-cache':
					$ret[] = $this->p->util->th( 'Object Cache Expiry', null, 'plugin_object_cache_exp' ).
					'<td nowrap>'.$this->form->get_input( 'plugin_object_cache_exp', 'short' ).' seconds</td>';
					break;
			}
			return $ret;
		}
	}
}

?>
