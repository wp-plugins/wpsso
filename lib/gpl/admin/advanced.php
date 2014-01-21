<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoAdminAdvanced' ) ) {

	class WpssoAdminAdvanced {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->p->util->add_plugin_filters( $this, array( 
				'plugin_content_rows' => 2,
				'taglist_tags_rows' => 2,
			), 50 );
		}

		public function filter_plugin_content_rows( $rows, $form ) {
			$checkboxes = '';
			foreach ( $this->p->util->get_post_types( 'plugin' ) as $post_type )
				$checkboxes .= '<p>'.$form->get_fake_checkbox( 'plugin_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ? '' : '('.$post_type->description.')' ).'</p>';

			$rows[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] =  $this->p->util->th( 'Auto-Resize Images', null, 'plugin_auto_img_resize' ).
			'<td class="blank">'.$form->get_fake_checkbox( 'plugin_auto_img_resize' ).'</td>';

			$rows[] =  $this->p->util->th( 'Ignore Small Images', null, 'plugin_ignore_small_img' ).
			'<td class="blank">'.$form->get_fake_checkbox( 'plugin_ignore_small_img' ).'</td>';

			$rows[] = $this->p->util->th( 'Check for Wistia Videos', null, 'plugin_wistia_api' ).
			'<td class="blank">'.$form->get_fake_checkbox( 'plugin_wistia_api' ).'</td>';

			$rows[] = $this->p->util->th( 'Show Custom Settings on', null, 'plugin_add_to' ).
			'<td class="blank">'.$checkboxes.'</td>';
			
			return $rows;
		}

		public function filter_taglist_tags_rows( $rows, $form ) {
			$og_cols = 4;
			$cells = array();
			$rows = array( '<td colspan="'.($og_cols * 2).'" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>' );
			foreach ( $this->p->opt->get_defaults() as $opt => $val ) {
				if ( preg_match( '/^inc_(.*)$/', $opt, $match ) ) {
					$cells[] = '<td class="taglist blank checkbox">'.
						$form->get_fake_checkbox( $opt ).'</td>'.
						'<th class="taglist">'.$match[1].'</th>'."\n";
				}
			}
			$col_rows = array();
			$per_col = ceil( count( $cells ) / $og_cols );
			foreach ( $cells as $num => $cell ) {
				if ( empty( $col_rows[ $num % $per_col ] ) )
					$col_rows[ $num % $per_col ] = '';	// initialize the array
				$col_rows[ $num % $per_col ] .= $cell;		// create the html for each row
			}
			return array_merge( $rows, $col_rows );
		}
	}
}

?>
