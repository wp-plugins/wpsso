<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomForm' ) ) {

	class SucomForm {
	
		private $p;
		public $options = array();
		public $defaults = array();
		public $options_name;

		public function __construct( &$plugin, $opts_name, &$opts, &$def_opts ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->options_name =& $opts_name;
			$this->options =& $opts;
			$this->defaults =& $def_opts;
		}

		public function get_image_upload_input( $name_prefix ) {
			$media_libs = array( 'wp' => 'Media Library' );
			if ( $this->p->is_avail['media']['ngg'] === true ) 
				$media_libs['ngg'] = 'NextGEN Gallery';

			return '<div class="img_upload">'.$this->get_input( $name_prefix.'_id', 'short' ).'&nbsp;in&nbsp;'.
				$this->get_select( $name_prefix.'_id_pre', $media_libs ).'&nbsp;'.
				( function_exists( 'wp_enqueue_media' ) ? $this->get_button( 'Select or Upload Image', 
					'sucom_image_upload_button button', $name_prefix ) : '' ).'</div>';

		}

		public function get_image_url_input( $name_prefix ) {
			return empty( $this->options[$name_prefix.'_id'] ) ? 
				$this->get_input( $name_prefix.'_url', 'wide' ) :
				$this->get_no_input( $name_prefix.'_url', 'wide' );
		}

		public function get_hidden( $name, $value = '' ) {
			if ( empty( $name ) ) return;	// just in case
			// hide the current options value, unless one is given as an argument to the method
			$value = empty( $value ) && $this->in_options( $name ) ? $this->options[$name] : $value;
			return '<input type="hidden" name="'.$this->options_name.'['.$name.']" value="'.esc_attr( $value ).'" />';
		}

		public function get_checkbox( $name, $check = array( 1, 0 ), $class = '', $id = '', $disabled = false ) {
			if ( empty( $name ) ) return;	// just in case
			if ( ! is_array( $check ) ) $check = array( 1, 0 );
			if ( $this->in_options( $name.':is' ) && 
				$this->options[$name.':is'] === 'disabled' )
					$disabled = true;
			$html = $disabled === true ? $this->get_hidden( $name ) : $this->get_hidden( 'is_checkbox_'.$name, 1 );
			$html .= '<input type="checkbox"'.
				( $disabled === true ? ' disabled="disabled"' : ' name="'.$this->options_name.'['.$name.']" value="'.esc_attr( $check[0] ).'"' ).
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? '' : ' id="checkbox_'.$id.'"' ).
				( $this->in_options( $name ) ? checked( $this->options[$name], $check[0], false ) : '' ).
				' title="default is '.( $this->in_defaults( $name ) && $this->defaults[$name] == $check[0] ? 'checked' : 'unchecked' ).
				( $disabled === true ? ' (option disabled)' : '' ).'" />';
			return $html;
		}

		public function get_no_checkbox( $name, $check = array( 1, 0 ), $class = '', $id = '' ) {
			return $this->get_checkbox( $name, $check, $class, $id, true );
		}

		public function get_radio( $name, $values = array(), $class = '', $id = '', $is_assoc = false, $disabled = false ) {
			if ( empty( $name ) || ! is_array( $values ) ) return;
			if ( $is_assoc == false ) 
				$is_assoc = SucomUtil::is_assoc( $values );
			if ( $this->in_options( $name.':is' ) && 
				$this->options[$name.':is'] === 'disabled' )
					$disabled = true;
			$html = $disabled === true ? $this->get_hidden( $name ) : '';
			foreach ( $values as $val => $desc ) {
				// if the array is NOT associative (so regular numered array), 
				// then the description is used as the saved value as well
				if ( $is_assoc == false ) $val = $desc;
				$html .= '<input type="radio"'.
					( $disabled === true ? ' disabled="disabled"' : ' name="'.$this->options_name.'['.$name.']" value="'.esc_attr( $val ).'"' ).
					( empty( $class ) ? '' : ' class="'.$class.'"' ).
					( empty( $id ) ? '' : ' id="radio_'.$id.'"' ).
					( $this->in_options( $name ) ? checked( $this->options[$name], $val, false ) : '' ).
					( $this->in_defaults( $name ) ? ' title="default is '.$values[$this->defaults[$name]].'"' : '' ).
					'/> '.$desc.'&nbsp;&nbsp;';
			}
			return $html;
		}

		public function get_no_radio( $name, $values = array(), $class = '', $id = '', $is_assoc = false ) {
			return $this->get_radio( $name, $values, $class, $id, $is_assoc, true );
		}

		public function get_no_select( $name, $values = array(), $class = '', $id = '', $is_assoc = false ) {
			return $this->get_select( $name, $values, $class, $id, $is_assoc, true );
		}

		public function get_select( $name, $values = array(), $class = '', $id = '', 
			$is_assoc = false, $disabled = false, $selected = false, $reload = false ) {

			if ( empty( $name ) || ! is_array( $values ) ) 
				return;

			if ( $is_assoc === false ) 
				$is_assoc = SucomUtil::is_assoc( $values );

			$html = '';
			$select_id = empty( $id ) ? 'select_'.$name : 'select_'.$id;

			if ( $reload === true ) {
				$url = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';
				$url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				$url = add_query_arg( array( $name => '%%'.$name.'%%' ), $url );
				$html .= '
<script type="text/javascript">
	jQuery(function(){
		jQuery("#'.$select_id.'").change(function(){
			url="'.$url.'"+jQuery(location).attr("hash");
			window.location=url.replace("%%'.$name.'%%", this.value);
		});
	});
</script>';
			}
			$html .= '<select name="'.$this->options_name.'['.$name.']"'.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).' id="'.$select_id.'"'.
				( $disabled === true ? ' disabled="disabled"' : '' ).'>';

			foreach ( $values as $val => $desc ) {
				// if the array is NOT associative (so regular numered array), 
				// then the description is used as the saved value as well
				if ( $is_assoc == false ) 
					$val = $desc;
				if ( $val == -1 ) 
					$desc = '(settings value)';
				else {
					switch ( $name ) {
						case 'og_img_max': 
							if ( $desc === 0 ) 
								$desc .= ' (no images)'; 
							break;
						case 'og_vid_max': 
							if ( $desc === 0 ) 
								$desc .= ' (no videos)'; 
							break;
						default: 
							if ( $desc === '' || $desc === 'none' ) 
								$desc = '[none]'; 
							break;
					}
					if ( $this->in_defaults( $name ) && 
						$val === $this->defaults[$name] )
							$desc .= ' (default)';	// mark default value
				}

				$html .= '<option value="'.esc_attr( $val ).'"';
				if ( $selected !== false )
					$html .= selected( $selected, $val, false );
				elseif ( $this->in_options( $name ) )
					$html .= selected( $this->options[$name], $val, false );
				$html .= '>'.$desc.'</option>';
			}
			$html .= '</select>';
			return $html;
		}

		// $use_opt_defs = true when used for post / user meta forms (to show default values)
		public function get_image_dimensions_input( $name, $use_opt_defs = false, $narrow = false ) {
			$def_width = '';
			$def_height = '';
			$crop_select = '';

			if ( $use_opt_defs === true ) {
				$def_width = empty( $this->p->options[$name.'_width'] ) ? '' : $this->p->options[$name.'_width'];
				$def_height = empty( $this->p->options[$name.'_height'] ) ? '' : $this->p->options[$name.'_height'];
				foreach ( array( 'crop', 'crop_x', 'crop_y' ) as $key )
					if ( ! $this->in_options( $name.'_'.$key ) && $this->in_defaults( $name.'_'.$key ) )
						$this->options[$name.'_'.$key] = $this->defaults[$name.'_'.$key];
			}

			global $wp_version;
			if ( ! version_compare( $wp_version, 3.9, '<' ) ) {
				$crop_select .= $narrow === true ? 
					' <div class="img_crop_from is_narrow">' :
					' <div class="img_crop_from">From';
				foreach ( array( 'crop_x', 'crop_y' ) as $key ) {
					$pos_vals = $this->options[$name.'_'.$key] == -1 ? 
						array_merge( array( '-1' => '(settings value)' ), $this->p->cf['form']['position_'.$key] ) : 
						$this->p->cf['form']['position_'.$key];
					$crop_select .= ' '.$this->get_select( $name.'_'.$key, $pos_vals, 'medium' );
				}
				$crop_select .= '</div>';
			}

			return 'Width '.$this->get_input( $name.'_width', 'short', null, null, $def_width ).' x '.
				'Height '.$this->get_input( $name.'_height', 'short', null, null, $def_height ).
				' &nbsp; Crop '.$this->get_checkbox( $name.'_crop' ).$crop_select;
		}

		public function get_image_dimensions_text( $name, $use_opt_defs = false ) {
			if ( ! empty( $this->options[$name.'_width'] ) && 
				! empty( $this->options[$name.'_height'] ) ) {
				return $this->options[$name.'_width'].' x '.
					$this->options[$name.'_height'].
					( $this->options[$name.'_crop'] ? ', cropped' : '' );
			} elseif ( $use_opt_defs === true ) {
				if ( ! empty( $this->p->options[$name.'_width'] ) &&
					! empty( $this->p->options[$name.'_height'] ) ) {
					return $this->p->options[$name.'_width'].' x '.
						$this->p->options[$name.'_height'].
						( $this->p->options[$name.'_crop'] ? ', cropped' : '' );
				}
			}
			return;
		}

		public function get_select_img_size( $name, $name_preg = '//', $invert = false ) {
			if ( empty( $name ) ) 
				return;	// just in case
			$invert = $invert == false ? 
				null : PREG_GREP_INVERT;
			$size_names = preg_grep( $name_preg, get_intermediate_image_sizes(), $invert );
			natsort( $size_names );
			$html = '<select name="'.$this->options_name.'['.$name.']">';
			foreach ( $size_names as $size_name ) {
				if ( ! is_string( $size_name ) ) 
					continue;
				$size = $this->p->media->get_size_info( $size_name );
				$html .= '<option value="'.esc_attr( $size_name ).'" ';
				if ( $this->in_options( $name ) )
					$html .= selected( $this->options[$name], $size_name, false );
				$html .= '>'.$size_name.' [ '.$size['width'].'x'.$size['height'].( $size['crop'] ? ' cropped' : '' ).' ]';
				if ( $this->in_defaults( $name ) && $size_name == $this->defaults[$name] ) 
					$html .= ' (default)';	// mark default value
				$html .= '</option>';
			}
			$html .= '</select>';
			return $html;
		}

		private function get_text_len_js( $id ) {
			return ( empty( $id ) ? '' : '<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(\'#'.$id.'\').focus(function(){ sucomTextLen(\''.$id.'\'); });
					jQuery(\'#'.$id.'\').keyup(function(){ sucomTextLen(\''.$id.'\'); });
				});</script>' );
		}

		public function get_input( $name, $class = '', $id = '', $len = 0, $placeholder = '' ) {
			if ( empty( $name ) ) return;	// just in case
			if ( $this->in_options( $name.':is' ) && 
				$this->options[$name.':is'] === 'disabled' )
					return $this->get_no_input( $name, $class, $id );
			$html = '';
			$value = $this->in_options( $name ) ? $this->options[$name] : '';
			if ( ! empty( $len ) && ! empty( $id ) )
				$html .= $this->get_text_len_js( 'text_'.$id );
			
			$html .= '<input type="text" name="'.$this->options_name.'['.$name.']"'.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? ' id="text_'.$name.'"' : ' id="text_'.$id.'"' ).
				( empty( $len ) ? '' : ' maxLength="'.$len.'"' ).
				( empty( $placeholder ) ? '' : ' placeholder="'.$placeholder.'"'.
					' onFocus="if ( this.value == \'\' ) this.value = \''.esc_js( $placeholder ).'\';"'.
					' onBlur="if ( this.value == \''.esc_js( $placeholder ).'\' ) this.value = \'\';"' ).
				' value="'.esc_attr( $value ).'" />'.
				( empty( $len ) ? '' : ' <div id="text_'.$id.'-lenMsg"></div>' );
			return $html;
		}

		public function get_no_input( $name, $class = '', $id = '' ) {
			$value = $this->in_options( $name ) ? $this->options[$name] : '';
			$html = $this->get_hidden( $name ).
				'<input type="text" disabled="disabled"'.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? ' id="text_'.$name.'"' : ' id="text_'.$id.'"' ).
				' value="'.esc_attr( $value ).'" />';
			return $html;
		}

		public function get_textarea( $name, $class = '', $id = '', $len = 0, $placeholder = '' ) {
			if ( empty( $name ) ) return;	// just in case
			$html = '';
			$value = $this->in_options( $name ) ? $this->options[$name] : '';
			if ( ! empty( $len ) && ! empty( $id ) )
				$html .= $this->get_text_len_js( 'textarea_'.$id );
			$html .= '<textarea name="'.$this->options_name.'['.$name.']"'.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? ' id="textarea_'.$name.'"' : ' id="textarea_'.$id.'"' ).
				( empty( $len ) ? '' : ' maxLength="'.$len.'"' ).
				( empty( $len ) && empty( $class ) ? '' : ' rows="'.( round( $len / 100 ) + 1 ).'"' ).
				( empty( $placeholder ) ? '' : ' placeholder="'.$placeholder.'"'.
					' onFocus="if ( this.value == \'\' ) this.value = \''.esc_js( $placeholder ).'\';"'.
					' onBlur="if ( this.value == \''.esc_js( $placeholder ).'\' ) this.value = \'\';"' ).
				'>'.stripslashes( esc_attr( $value ) ).'</textarea>'.
				( empty( $len ) ? '' : ' <div id="textarea_'.$id.'-lenMsg"></div>' );
			return $html;
		}

		public function get_button( $value, $class = '', $id = '', $url = '', $newtab = false ) {
			$js = $newtab === true ? 
				'window.open(\''.$url.'\', \'_blank\');' :
				'location.href=\''.$url.'\';';
			$html = '<input type="button" '.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? '' : ' id="button_'.$id.'"' ).
				( empty( $url ) ? '' : ' onClick="'.$js.'"' ).
				' value="'.esc_attr( $value ).'" />';
			return $html;
		}

		public function get_input_for_copy( $value, $class = '', $id = '' ) {
			$html = '<input type="text" '.
				( empty( $class ) ? '' : ' class="'.$class.'"' ).
				( empty( $id ) ? '' : ' id="text_'.$id.'"' ).
				' value="'.esc_attr( $value ).'" 
				onFocus="this.select();" 
				onMouseUp="return false;" />';
			return $html;
		}

		public function get_options( $idx = false, $def_val = false ) {
			if ( $idx !== false ) {
				if ( isset( $this->options[$idx] ) )
					return $this->options[$idx];
				else return $def_val;
			} else return $this->options;
		}

		private function in_options( $name ) {
			return is_array( $this->options ) && 
				array_key_exists( $name, $this->options ) ? true : false;
		}

		private function in_defaults( $name ) {
			return is_array( $this->defaults ) && 
				array_key_exists( $name, $this->defaults ) ? true : false;
		}
	}
}

?>
