<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoAdminGeneralGpl' ) && class_exists( 'WpssoAdminGeneral' ) ) {

	class WpssoAdminGeneralGpl extends WpssoAdminGeneral {

		protected function get_rows_twitter() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Enable Twitter Cards', 'highlight', 'tc_enable' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'tc_enable' ).'</td>',

				$this->p->util->th( 'Maximum Description Length', null, 'tc_desc_len' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_desc_len' ).
				$this->p->options['tc_desc_len'].' characters or less</td>',

				$this->p->util->th( 'Website @username to Follow', 'highlight', 'tc_site' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_site' ).
				$this->p->options['tc_site'].'</td>',

				$this->p->util->th( '<em>Summary</em> Card Image Dimensions', null, 'tc_sum_dimensions' ).
				'<td class="blank">'.$this->get_img_dims( 'tc_sum' ).'</td>',

				$this->p->util->th( '<em>Large Image</em> Card Image Dimensions', null, 'tc_lrgimg_dimensions' ).
				'<td class="blank">'.$this->get_img_dims( 'tc_lrgimg' ).'</td>',

				$this->p->util->th( '<em>Photo</em> Card Image Dimensions', 'highlight', 'tc_photo_dimensions' ).
				'<td class="blank">'.$this->get_img_dims( 'tc_photo' ).'</td>',

				$this->p->util->th( '<em>Gallery</em> Card Minimum Images', null, 'tc_gal_minimum' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_gal_min' ).
				$this->p->options['tc_gal_min'].'</td>',

				$this->p->util->th( '<em>Gallery</em> Card Image Dimensions', null, 'tc_gal_dimensions' ).
				'<td class="blank">'.$this->get_img_dims( 'tc_gal' ).'</td>',

				$this->p->util->th( '<em>Product</em> Card Image Dimensions', null, 'tc_prod_dimensions' ).
				'<td class="blank">'.$this->get_img_dims( 'tc_prod' ).'</td>',

				$this->p->util->th( '<em>Product</em> Card Default 2nd Attribute', null, 'tc_prod_defaults' ).
				'<td class="blank">'.
				$this->form->get_hidden( 'tc_prod_def_l2' ).'Label: '.$this->p->options['tc_prod_def_l2'].' &nbsp; '.
				$this->form->get_hidden( 'tc_prod_def_d2' ).'Value: '.$this->p->options['tc_prod_def_d2'].
				'</td>',

			);
		}

		protected function get_img_dims( $name ) {
			return $this->form->get_hidden( $name.'_width' ).
				$this->form->get_hidden( $name.'_height' ).
				$this->form->get_hidden( $name.'_crop' ).
				$this->p->options[$name.'_height'].' x '.
				$this->p->options[$name.'_height'].
				( $this->p->options[$name.'_crop'] ? ', cropped' : '' );
		}
	}
}

if ( ! class_exists( 'WpssoAdminAdvancedGpl' ) && class_exists( 'WpssoAdminAdvanced' ) ) {

	class WpssoAdminAdvancedGpl extends WpssoAdminAdvanced {

		protected function get_more_content() {
			$add_to_checkboxes = '';
			foreach ( $this->p->util->get_post_types( 'plugin' ) as $post_type )
				$add_to_checkboxes .= '<p>'.$this->form->get_fake_checkbox( 'plugin_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ? '' : '('.$post_type->description.')' ).'</p>';

			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Check for Wistia Videos', null, 'plugin_wistia_api' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_wistia_api' ).'</td>',

				$this->p->util->th( 'Show Custom Settings on', null, 'plugin_add_to' ).
				'<td class="blank">'.$add_to_checkboxes.'</td>',
			);
		}

		protected function get_more_taglist() {
			$og_cols = 4;
			$cells = array();
			$rows = array();
			foreach ( $this->p->opt->get_defaults() as $opt => $val ) {
				if ( preg_match( '/^inc_(.*)$/', $opt, $match ) ) {
					$cells[] = '<td class="taglist blank checkbox">'.
					$this->form->get_fake_checkbox( $opt ).'</td>'.
					'<th class="taglist">'.$match[1].'</th>'."\n";
				}
			}
			$per_col = ceil( count( $cells ) / $og_cols );
			foreach ( $cells as $num => $cell ) {
				if ( empty( $rows[ $num % $per_col ] ) )
					$rows[ $num % $per_col ] = '';	// initialize the array
				$rows[ $num % $per_col ] .= $cell;	// create the html for each row
			}
			return array_merge( array( '<td colspan="'.($og_cols * 2).'" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>' ), $rows );
		}

		protected function get_more_cache() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Social File Cache Expiry', 'highlight', 'plugin_file_cache_hrs' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_file_cache_hrs' ). 
				$this->p->options['plugin_file_cache_hrs'].' hours</td>',

				$this->p->util->th( 'Verify SSL Certificates', null, 'plugin_verify_certs' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_verify_certs' ).'</td>',
			);
		}

		protected function get_more_apikeys() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Bit.ly Username', null, 'plugin_bitly_login' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_bitly_login' ).
				$this->p->options['plugin_bitly_login'].'</td>',

				$this->p->util->th( 'Bit.ly API Key', null, 'plugin_bitly_api_key' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_bitly_api_key' ).
				$this->p->options['plugin_bitly_api_key'].'</td>',

				$this->p->util->th( 'Google Project Application BrowserKey', null, 'plugin_google_api_key' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_google_api_key' ).
				$this->p->options['plugin_google_api_key'].'</td>',

				$this->p->util->th( 'Google URL Shortener API is ON', null, 'plugin_google_shorten' ).
				'<td class="blank">'.$this->form->get_fake_radio( 'plugin_google_shorten', 
					array( '1' => 'Yes', '0' => 'No' ), null, null, true ).'</td>',
			);
		}

		protected function get_more_rewrite() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'URL Length to Shorten', null, 'plugin_min_shorten' ). 
				'<td class="blank">'.$this->form->get_hidden( 'plugin_min_shorten' ).
					$this->p->options['plugin_min_shorten'].' characters</td>',

				$this->p->util->th( 'Static Content URL(s)', 'highlight', 'plugin_cdn_urls' ). 
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_urls' ). 
					$this->p->options['plugin_cdn_urls'].'</td>',

				$this->p->util->th( 'Include Folders', null, null, 'plugin_cdn_folders' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_folders' ). 
					$this->p->options['plugin_cdn_folders'].'</td>',

				$this->p->util->th( 'Exclude Patterns', null, 'plugin_cdn_excl' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_excl' ).
					$this->p->options['plugin_cdn_excl'].'</td>',

				$this->p->util->th( 'Not when Using HTTPS', null, 'plugin_cdn_not_https' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_cdn_not_https' ).'</td>',

				$this->p->util->th( 'www is Optional', null, 'plugin_cdn_www_opt' ). 
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_cdn_www_opt' ).'</td>',
			);
		}
	}
}

if ( ! class_exists( 'WpssoAdminSocialGpl' ) && class_exists( 'WpssoAdminSocial' ) ) {

	class WpssoAdminSocialGpl extends WpssoAdminSocial {

		protected function get_more_social() {
			$add_to_checkboxes = '';
			foreach ( $this->p->util->get_post_types( 'buttons' ) as $post_type )
				$add_to_checkboxes .= '<p>'.$this->form->get_fake_checkbox( 'buttons_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ? '' : '('.$post_type->description.')' ).'</p>';

			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Include on Post Types', null, 'buttons_add_to' ).
				'<td class="blank">'.$add_to_checkboxes.'</td>',
			);
		}
	}
}

?>
