<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoOptionsUpgrade' ) && class_exists( 'WpssoOptions' ) ) {

	class WpssoOptionsUpgrade extends WpssoOptions {

		private $renamed_site_keys = array();

		private $renamed_keys = array(
			'og_img_resize' => 'plugin_auto_img_resize',
		);

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		// def_opts accepts output from functions, so don't force reference
		public function options( $options_name, &$opts = array(), $def_opts = array() ) {
			$opts = SucomUtil::rename_keys( $opts, $this->renamed_keys );

			// custom value changes for regular options
			if ( $options_name == constant( $this->p->cf['uca'].'_OPTIONS_NAME' ) ) {
				if ( $opts['options_version'] <= 260 &&
					$opts['og_img_width'] == 1200 &&
					$opts['og_img_height'] == 630 &&
					! empty( $opts['og_img_crop'] ) ) {

					$this->p->notice->inf( 'Open Graph Image Dimentions have been updated from '.
						$opts['og_img_width'].'x'.$opts['og_img_height'].', '.
						( $opts['og_img_crop'] ? '' : 'un' ).'cropped to '.
						$def_opts['og_img_width'].'x'.$def_opts['og_img_height'].', '.
						( $def_opts['og_img_crop'] ? '' : 'un' ).'cropped.', true );

					$opts['og_img_width'] = $def_opts['og_img_width'];
					$opts['og_img_height'] = $def_opts['og_img_height'];
					$opts['og_img_crop'] = $def_opts['og_img_crop'];
				}
			}

			$opts = $this->sanitize( $opts, $def_opts );	// cleanup excess options and sanitize
			return $opts;
		}
	}
}

?>
