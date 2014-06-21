<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSubmenuGuide' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSubmenuGuide extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_review', 'Good Plugin or Support?', array( &$this, 'show_metabox_review' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_guide', 'A Setup Guide', array( &$this, 'show_metabox_guide' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_review() {
			echo '<table class="sucom-setting readme-metabox"><tr><td>';
			echo $this->p->msgs->get( 'review-info' );
			echo '</td></tr></table>';
		}
		
		public function show_metabox_guide() {
			echo '<table class="sucom-setting readme-metabox"><tr><td>';
			echo '</td></tr></table>';
		}
	}
}

?>
