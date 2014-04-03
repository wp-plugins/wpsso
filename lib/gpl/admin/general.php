<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoAdminGeneral' ) ) {

	class WpssoAdminGeneral {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'pub_twitter_rows' => 2,
			) );
		}

		public function filter_pub_twitter_rows( $rows, $form ) {
			$rows[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = $this->p->util->th( 'Enable Twitter Cards', 'highlight', 'tc_enable' ).
			'<td class="blank">'.$form->get_hidden( 'tc_enable' ).'<input type="checkbox" disabled="disabled" /></td>';

			$rows[] = $this->p->util->th( 'Maximum Description Length', null, 'tc_desc_len' ).
			'<td class="blank">'.$form->get_hidden( 'tc_desc_len' ).
			$this->p->options['tc_desc_len'].' characters or less</td>';

			$rows[] = $this->p->util->th( 'Website @username to Follow', 'highlight', 'tc_site' ).
			'<td class="blank">'.$form->get_hidden( 'tc_site' ).
			$this->p->options['tc_site'].'</td>';

			$rows[] = $this->p->util->th( '<em>Summary</em> Card Image Dimensions', null, 'tc_sum_dimensions' ).
			'<td class="blank">'.$this->get_img_dims( 'tc_sum', $form ).'</td>';

			$rows[] = $this->p->util->th( '<em>Large Image</em> Card Image Dimensions', null, 'tc_lrgimg_dimensions' ).
			'<td class="blank">'.$this->get_img_dims( 'tc_lrgimg', $form ).'</td>';

			$rows[] = $this->p->util->th( '<em>Photo</em> Card Image Dimensions', 'highlight', 'tc_photo_dimensions' ).
			'<td class="blank">'.$this->get_img_dims( 'tc_photo', $form ).'</td>';

			$rows[] = $this->p->util->th( '<em>Gallery</em> Card Minimum Images', null, 'tc_gal_minimum' ).
			'<td class="blank">'.$form->get_hidden( 'tc_gal_min' ).
			$this->p->options['tc_gal_min'].'</td>';

			$rows[] = $this->p->util->th( '<em>Gallery</em> Card Image Dimensions', null, 'tc_gal_dimensions' ).
			'<td class="blank">'.$this->get_img_dims( 'tc_gal', $form ).'</td>';

			$rows[] = $this->p->util->th( '<em>Product</em> Card Image Dimensions', null, 'tc_prod_dimensions' ).
			'<td class="blank">'.$this->get_img_dims( 'tc_prod', $form ).'</td>';

			$rows[] = $this->p->util->th( '<em>Product</em> Card Default 2nd Attribute', null, 'tc_prod_defaults' ).
			'<td class="blank">'.
			$form->get_hidden( 'tc_prod_def_l2' ).'Label: '.$this->p->options['tc_prod_def_l2'].' &nbsp; '.
			$form->get_hidden( 'tc_prod_def_d2' ).'Value: '.$this->p->options['tc_prod_def_d2'].
			'</td>';

			return $rows;
		}

		private function get_img_dims( $name, &$form ) {
			return $form->get_hidden( $name.'_width' ).
				$form->get_hidden( $name.'_height' ).
				$form->get_hidden( $name.'_crop' ).
				$this->p->options[$name.'_height'].' x '.
				$this->p->options[$name.'_height'].
				( $this->p->options[$name.'_crop'] ? ', cropped' : '' );
		}
	}
}

?>
