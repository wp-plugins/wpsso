<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPostmeta' ) ) {

	/*
	 * This class is extended by gpl/util/postmeta.php or pro/util/postmeta.php
	 * and the class object is created as $this->p->addons['util']['postmeta'] by
	 * gpl/addons.php or pro/addons.php.
	 *
	 */
	class WpssoPostmeta {

		protected $p;
		protected $form;
		protected $header_tags = array();
		protected $post_info = array();

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_actions();
		}

		protected function add_actions() {
		}

		public function add_metaboxes() {
			if ( ! is_admin() )
				return;

			// get the current object / post type
			if ( ( $obj = $this->p->util->get_the_object() ) === false ) {
				$this->p->debug->log( 'exiting early: invalid object type' );
				return;
			}
			$post_type = get_post_type_object( $obj->post_type );

			if ( ! empty( $this->p->options[ 'plugin_add_to_'.$post_type->name ] ) ) {
				// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
				add_meta_box( WPSSO_META_NAME, $this->p->cf['menu'].' Custom Settings', 
					array( &$this, 'show_metabox_postmeta' ), $post_type->name, 'advanced', 'high' );
			}
		}

		public function set_header_tags() {
			if ( $this->p->is_avail['opengraph'] && empty( $this->header_tags ) ) {
				if ( ( $obj = $this->p->util->get_the_object() ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return;
				}
				if ( isset( $obj->ID ) && $obj->post_status === 'publish' && $obj->filter === 'edit' ) {
					$html = $this->p->head->get_header_html( $obj->ID );
					$this->p->debug->show_html( null, 'debug log' );
					$html = preg_replace( '/<!--.*-->/Us', '', $html );
					preg_match_all( '/<(\w+) (\w+)="([^"]*)" (\w+)="([^"]*)"[ \/]*>/', $html, $this->header_tags, PREG_SET_ORDER );
				}
			}
		}

		public function show_metabox_postmeta( $post ) {
			$opts = $this->get_options( $post->ID );	// sanitize when saving, not reading
			$def_opts = $this->get_defaults();
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$post_info = array( 'ptn' => ucfirst( $post_type->name ), 'id' => $post->ID );
			$this->form = new SucomForm( $this->p, WPSSO_META_NAME, $opts, $def_opts );
			wp_nonce_field( $this->get_nonce(), WPSSO_NONCE );

			$metabox = 'meta';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'header' => 'Header Meta Tags', 
				'tools' => 'Validation Tools',
				'tags' => 'Meta Tags Preview' ) );

			if ( empty( $this->p->is_avail['opengraph'] ) )
				unset( $tabs['tags'] );

			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key, $post_info ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form, $post_info ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		protected function get_rows( $metabox, $key, $post_info ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'meta-tools':
					if ( get_post_status( $post_info['id'] ) == 'publish' ) {

						$rows[] = $this->p->util->th( 'Facebook Debugger' ).'<td class="validate"><p>Refresh the Facebook cache and 
						validate the Open Graph / Rich Pin meta tags for this '.$post_info['ptn'].'. Facebook, Pinterest, LinkedIn, Google+,
						and most social websites use these Open Graph meta tags. The Facebook Debugger remains the most stable and reliable 
						method to verify Open Graph meta tags.</p>
						<p><strong>Please note that you may have to click the "Debug" button once or twice to refresh Facebook\'s cache</strong>.</p></td>

						<td class="validate">'.$this->form->get_button( 'Validate Open Graph', 'button-secondary', null, 
						'https://developers.facebook.com/tools/debug/og/object?q='.urlencode( get_permalink( $post_info['id'] ) ), true ).'</td>';
			
						$rows[] = $this->p->util->th( 'Google Structured Data Testing Tool' ).'<td class="validate"><p>Verify that Google can 
						correctly parse your structured data markup (meta tags, Schema, and Microdata markup) and display it in search results.
						Most of the information extracted from the meta tags can be found in the rdfa-node / property section of the results.</p></td>

						<td class="validate">'.$this->form->get_button( 'Validate Data Markup', 'button-secondary', null, 
						'http://www.google.com/webmasters/tools/richsnippets?q='.urlencode( get_permalink( $post_info['id'] ) ), true ).'</td>';
			
						$rows[] = $this->p->util->th( 'Pinterest Rich Pin Validator' ).'<td class="validate"><p>Validate the Open Graph / Rich Pin 
						meta tags, and apply to have them displayed on Pinterest.</p></td>

						<td class="validate">'.$this->form->get_button( 'Validate Rich Pins', 'button-secondary', null, 
						'http://developers.pinterest.com/rich_pins/validator/?link='.urlencode( get_permalink( $post_info['id'] ) ), true ).'</td>';
			
						$rows[] = $this->p->util->th( 'Twitter Card Validator' ).'<td class="validate"><p>The Twitter Card Validator does not 
						accept query arguments &ndash; copy-paste the following sharing URL into the validation input field. 
						To enable the display of Twitter Card information in tweets, you must submit a URL for each type of card you provide
						(Summary, Summary with Large Image, Photo, Gallery, Player, and/or Product card).</p>'.
						$this->form->get_text( get_permalink( $post_info['id'] ), 'wide' ).'</td>

						<td class="validate">'.$this->form->get_button( 'Validate Twitter Card', 'button-secondary', null, 
						'https://dev.twitter.com/docs/cards/validation/validator', true ).'</td>';
		
					} else $rows[] = '<td><p class="centered">The Validation Tools will be available when the '.$post_info['ptn'].
						' is published with public visibility.</p></td>';
					break; 

				case 'meta-tags':	
					if ( get_post_status( $post_info['id'] ) == 'publish' ) {
						foreach ( $this->header_tags as $m ) {
							$rows[] = '<th class="xshort">'.$m[1].'</th>'.
								'<th class="xshort">'.$m[2].'</th>'.
								'<td class="short">'.$m[3].'</td>'.
								'<th class="xshort">'.$m[4].'</th>'.
								'<td class="wide">'.( strpos( $m[5], 'http' ) === 0 ? '<a href="'.$m[5].'">'.$m[5].'</a>' : $m[5] ).'</td>';
						}
						sort( $rows );
					} else $rows[] = '<td><p class="centered">The Meta Tags Preview will be available when the '.$post_info['ptn'].
						' is published with public visibility.</p></td>';
					break; 
			}
			return $rows;
		}

		// returns an array of $pid and $video_url
		public function get_media( $post_id ) {
			// use get_options() from the extended meta object
			$pid = $this->get_options( $post_id, 'og_img_id' );
			$pre = $this->get_options( $post_id, 'og_img_id_pre' );
			$img_url = $this->get_options( $post_id, 'og_img_url' );
			$video_url = $this->get_options( $post_id, 'og_vid_url' );

			if ( empty( $pid ) ) {
				if ( $this->p->is_avail['postthumb'] == true && has_post_thumbnail( $post_id ) )
					$pid = get_post_thumbnail_id( $post_id );
				else $pid = $this->p->media->get_first_attached_image_id( $post_id );
			} elseif ( $pre === 'ngg' )
				$pid = $pre.'-'.$pid;

			if ( empty( $video_url ) ) {
				$videos = array();
				// get the first video, if any - don't check for duplicates
				$videos = $this->p->media->get_content_videos( 1, $post_id, false );
				if ( ! empty( $videos[0]['og:video'] ) ) 
					$video_url = $videos[0]['og:video'];
			}
			return array( $pid, $video_url );
		}

                public function get_options( $post_id, $idx = '' ) {
			if ( ! empty( $idx ) ) return false;
			else return array();
		}

		public function get_defaults( $idx = '' ) {
			if ( ! empty( $idx ) ) return false;
			else return array();
		}

		public function flush_cache( $post_id ) {
			$this->p->util->flush_post_cache( $post_id );
		}

		protected function get_nonce() {
			return plugin_basename( __FILE__ );
		}
	}
}

?>
