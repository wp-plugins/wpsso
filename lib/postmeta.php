<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPostMeta' ) ) {

	class WpssoPostMeta {

		protected $p;
		protected $form;
		protected $header_tags = array();
		protected $post_info = array();

		// executed by wpssoPostMetaPro() as well
		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_actions();
		}

		protected function add_actions() {
			if ( ! is_admin() ) return;

			if ( $this->p->is_avail['opengraph'] )
				add_action( 'admin_head', array( &$this, 'set_header_tags' ) );

			add_action( 'save_post', array( &$this, 'flush_cache' ), 20 );
			add_action( 'edit_attachment', array( &$this, 'flush_cache' ), 20 );
		}

		public function add_metaboxes() {
			// include the custom settings metabox on the editing page for all post types
			foreach ( $this->p->util->get_post_types( 'plugin' ) as $post_type ) {
				if ( ! empty( $this->p->options[ 'plugin_add_to_'.$post_type->name ] ) ) {
					// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
					add_meta_box( WPSSO_META_NAME, $this->p->cf['menu'].' Custom Settings', 
						array( &$this->p->meta, 'show_metabox' ), $post_type->name, 'advanced', 'high' );
				}
			}
		}

		public function set_header_tags() {
			if ( $this->p->is_avail['opengraph'] && empty( $this->p->meta->header_tags ) ) {
				if ( ( $obj = $this->p->util->get_the_object() ) === false ) {
					$this->p->debug->log( 'exiting early: invalid object type' );
					return;
				}
				if ( isset( $obj->ID ) && $obj->post_status === 'publish' && $obj->filter === 'edit' ) {
					$html = $this->p->head->get_header_html( $this->p->og->get_array( $obj->ID ), $obj->ID );
					$this->p->debug->show_html( null, 'debug log' );
					$html = preg_replace( '/<!--.*-->/Us', '', $html );
					preg_match_all( '/<(\w+) (\w+)="([^"]*)" (\w+)="([^"]*)"[ \/]*>/', $html, $this->p->meta->header_tags, PREG_SET_ORDER );
				}
			}
		}

		public function show_metabox( $post ) {
			$opts = $this->get_options( $post->ID );	// sanitize when saving, not reading
			$def_opts = $this->get_defaults();
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$this->post_info = array( 'ptn' => ucfirst( $post_type->name ) );
			$this->form = new SucomForm( $this->p, WPSSO_META_NAME, $opts, $def_opts );
			wp_nonce_field( $this->get_nonce(), WPSSO_NONCE );

			$show_tabs = array( 
				'header' => 'Webpage Header', 
				'social' => 'Social Sharing', 
				'tools' => 'Validation Tools',
				'metatags' => 'Meta Tags Preview',
			);

			// only show if the social sharing button features are enabled
			if ( empty( $this->p->is_avail['ssb'] ) )
				unset( $show_tabs['social'] );

			if ( empty( $this->p->is_avail['opengraph'] ) )
				unset( $show_tabs['metatags'] );

			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key, $post->ID );
			$this->p->util->do_tabs( 'meta', $show_tabs, $tab_rows );
		}

		protected function get_rows( $key, $post_id ) {
			$ret = array();
			switch ( $key ) {
				case 'header' :
					$ret = $this->get_rows_header( $post_id );
					break;
				case 'social' :
					$ret = $this->get_rows_social( $post_id );
					break;
				case 'tools' :	
					$ret = $this->get_rows_tools( $post_id );
					break; 
				case 'metatags' :	
					$ret = $this->get_rows_metatags( $post_id );
					break; 
			}
			return $ret;
		}

		// returns an array of $pid and $video_url
		protected function get_social_vars( $post_id ) {
			$pid = $this->p->meta->get_options( $post_id, 'og_img_id' );
			$pre = $this->p->meta->get_options( $post_id, 'og_img_id_pre' );
			$img_url = $this->p->meta->get_options( $post_id, 'og_img_url' );
			$video_url = $this->p->meta->get_options( $post_id, 'og_vid_url' );

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

		protected function get_rows_tools( $post_id ) {
			$ret = array();

			if ( get_post_status( $post_id ) == 'publish' ) {

				$ret[] = $this->p->util->th( 'Facebook Debugger' ).'
				<td class="validate"><p>Verify the Open Graph and Rich Pin meta tags, and refresh the Facebook cache for this '.$this->post_info['ptn'].'.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Open Graph', 'button-secondary', null, 
					'https://developers.facebook.com/tools/debug/og/object?q='.urlencode( get_permalink( $post_id ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Google Structured Data Testing Tool' ).'
				<td class="validate"><p>Check that Google can correctly parse your structured data markup and display it in search results.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Data Markup', 'button-secondary', null, 
					'http://www.google.com/webmasters/tools/richsnippets?q='.urlencode( get_permalink( $post_id ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Pinterest Rich Pin Validator' ).'
				<td class="validate"><p>Validate the Open Graph / Rich Pin meta tags, and apply to display them on Pinterest.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Rich Pins', 'button-secondary', null, 
					'http://developers.pinterest.com/rich_pins/validator/?link='.urlencode( get_permalink( $post_id ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Twitter Card Validator' ).'
				<td class="validate"><p>The Twitter Card Validator does not accept query arguments -- copy-paste the following URL into the validation input field.
				To enable the display of Twitter Card information in tweets you must submit a URL for each type of card for approval.</p>'.
				$this->form->get_text( get_permalink( $post_id ), 'wide' ).'</td>
				<td class="validate">'.$this->form->get_button( 'Validate Twitter Card', 'button-secondary', null, 
					'https://dev.twitter.com/docs/cards/validation/validator', true ).'</td>';

			} else $ret[] = '<td><p class="centered">The Validation Tools will be available when the '.$this->post_info['ptn'].' is published with public visibility.</p></td>';

			return $ret;
		}

		protected function get_rows_metatags( $post_id ) {
			$ret = array();

			if ( get_post_status( $post_id ) == 'publish' ) {
				foreach ( $this->p->meta->header_tags as $m ) {
					$ret[] = '<th class="xshort">'.$m[1].'</th>'.
						'<th class="xshort">'.$m[2].'</th>'.
						'<td class="short">'.$m[3].'</td>'.
						'<th class="xshort">'.$m[4].'</th>'.
						'<td class="wide">'.( strpos( $m[5], 'http' ) === 0 ? '<a href="'.$m[5].'">'.$m[5].'</a>' : $m[5] ).'</td>';
				}
				sort( $ret );
			} else $ret[] = '<td><p class="centered">The Meta Tags Preview will be available when the '.$this->post_info['ptn'].' is published with public visibility.</p></td>';

			return $ret;
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

		protected function get_rows_header( $post_id ) { return array(); }
		protected function get_rows_social( $post_id ) { return array(); }
	}
}

?>
