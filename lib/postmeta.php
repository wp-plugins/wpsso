<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPostMeta' ) ) {

	class WpssoPostMeta {

		protected $p;
		protected $header_tags = array();

		// executed by wpssoPostMetaPro() as well
		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_actions();
		}

		protected function add_actions() {
			if ( is_admin() ) {
				if ( ! $this->p->check->is_aop() )
					add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );

				if ( $this->p->is_avail['opengraph'] )
					add_action( 'admin_head', array( &$this, 'set_header_tags' ) );

				add_action( 'save_post', array( &$this, 'flush_cache' ), 20 );
				add_action( 'edit_attachment', array( &$this, 'flush_cache' ), 20 );
			}
		}

		public function add_metaboxes() {
			// include the custom settings metabox on the editing page for that post type
			foreach ( $this->p->util->get_post_types( 'plugin' ) as $post_type )
				if ( ! empty( $this->p->options[ 'plugin_add_to_'.$post_type->name ] ) )
					add_meta_box( WPSSO_META_NAME, $this->p->cf['menu'].' Custom Settings', 
						array( &$this->p->meta, 'show_metabox' ), $post_type->name, 'advanced', 'high' );
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
			$this->form = new SucomForm( $this->p, WPSSO_META_NAME, $opts, $def_opts );
			wp_nonce_field( $this->get_nonce(), WPSSO_NONCE );

			$show_tabs = array( 
				'header' => 'Webpage Header', 
				'tools' => 'Validation Tools',
				'metatags' => 'Meta Tags Preview',
			);

			if ( ! $this->p->is_avail['opengraph'] )
				unset( $show_tabs['metatags'] );

			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key, $post );
			$this->p->util->do_tabs( 'meta', $show_tabs, $tab_rows );
		}

		protected function get_rows( $id, $post ) {
			$ret = array();
			switch ( $id ) {
				case 'header' :
					$ret = $this->get_rows_header( $post );
					break;
				case 'tools' :	
					$ret = $this->get_rows_tools( $post );
					break; 
				case 'metatags' :	
					$ret = $this->get_rows_metatags( $post );
					break; 
			}
			return $ret;
		}

		protected function get_rows_header( $post ) {
			$ret = array();
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$post_type_name = ucfirst( $post_type->name );

			$ret[] = '<td colspan="2" align="center">'.$this->p->msg->get( 'pro-feature-msg' ).'</td>';

			$ret[] = $this->p->util->th( 'Topic', 'medium', null, 
			'A custom topic for this '.$post_type_name.', different from the default Website Topic chosen in the General Settings.' ) .
			'<td class="blank">'.$this->p->options['og_art_section'].'</td>';

			$ret[] = $this->p->util->th( 'Default Title', 'medium', null, 
			'A custom title for the Open Graph, Rich Pin, Twitter Card meta tags (all Twitter Card formats), 
			and possibly the Pinterest, Tumblr, and Twitter sharing caption / text, depending on some option 
			settings. The default title value is refreshed when the (draft or published) '.$post_type_name.' is saved.' ) .
			'<td class="blank">'.$this->p->webpage->get_title( $this->p->options['og_title_len'], '...', true ).'</td>';
		
			$ret[] = $this->p->util->th( 'Default Description', 'medium', null, 
			'A custom description for the Open Graph, Rich Pin meta tags, and the fallback description for all other meta tags.
			The default description value is based on the content, or excerpt if one is available, 
			and is refreshed when the (draft or published) '.$post_type_name.' is saved.
			Update and save this description to change the default value of all other meta tags.' ) .
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['og_desc_len'], '...', true ).'</td>';
	
			$ret[] = $this->p->util->th( 'Google Description', 'medium', null, 
			'A custom description for the Google Search description meta tag.
			The default description value is refreshed when the '.$post_type_name.' is saved.' ) .
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['meta_desc_len'], '...', true, true, false ).	// no hashtags
			'</td>';

			$ret[] = $this->p->util->th( 'Twitter Card Description', 'medium', null, 
			'A custom description for the Twitter Card description meta tag (all Twitter Card formats).
			The default description value is refreshed when the '.$post_type_name.' is saved.' ) .
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['tc_desc_len'], '...', true ).'</td>';

			$ret[] = $this->p->util->th( 'Image ID', 'medium', null, 
			'A custom Image ID to include (first) in the Open Graph, Rich Pin, and \'Large Image Summary\' Twitter Card meta tags.' ) .
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Image URL', 'medium', null, 
			'A custom image URL, instead of an Image ID, to include (first) in the Open Graph, Rich Pin, 
			and \'Large Image Summary\' Twitter Card meta tags. Please make sure your custom image
			is large enough, or it may be ignored by the social website(s). <strong>Facebook recommends 
			an image size of 1200x630, 600x315 as a minimum, and will ignore any images less than 200x200</strong>.' ) .
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Video URL', 'medium', null, 
			'A custom video URL to include (first) in the Open Graph, Rich Pin, and \'Player\' Twitter Card meta tags.
			If the URL is from Youtube, Vimeo, or Wistia, an API connection will be made to retrieve the preferred video URL, dimensions, and preview image.' ).
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Maximum Images', 'medium', null, 
			'The maximum number of images to include in the Open Graph meta tags for this '.$post_type_name.'.' ) .
			'<td class="blank">'.$this->p->options['og_img_max'].'</td>';

			$ret[] = $this->p->util->th( 'Maximum Videos', 'medium', null, 
			'The maximum number of embedded videos to include in the Open Graph meta tags for this '.$post_type_name.'.' ) .
			'<td class="blank">'.$this->p->options['og_vid_max'].'</td>';

			$ret[] = $this->p->util->th( 'Sharing URL', 'medium', null, 
			'A custom sharing URL used in the Open Graph, Rich Pin meta tags.
			The default sharing URL may be influenced by settings from supported SEO plugins.
			Please make sure any custom URL you enter here is functional and redirects correctly.' ).
			'<td class="blank">'.( get_post_status( $post->ID ) == 'publish' ? 
				$this->p->util->get_sharing_url( true ) :
				'<p>The Sharing URL will be available when the '.$post_type_name.' is published.</p>' ).'</td>';

			return $ret;
		}

		protected function get_rows_tools( $post ) {
			$ret = array();
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$post_type_name = ucfirst( $post_type->name );

			if ( get_post_status( $post->ID ) == 'publish' ) {

				$ret[] = $this->p->util->th( 'Facebook Debugger' ).'
				<td class="validate"><p>Verify the Open Graph and Rich Pin meta tags, and refresh the Facebook cache for this '.$post_type_name.'.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Open Graph', 'button-secondary', null, 
					'https://developers.facebook.com/tools/debug/og/object?q='.urlencode( get_permalink( $post->ID ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Google Structured Data Testing Tool' ).'
				<td class="validate"><p>Check that Google can correctly parse your structured data markup and display it in search results.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Data Markup', 'button-secondary', null, 
					'http://www.google.com/webmasters/tools/richsnippets?q='.urlencode( get_permalink( $post->ID ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Pinterest Rich Pin Validator' ).'
				<td class="validate"><p>Validate the Open Graph / Rich Pin meta tags, and apply to display them on Pinterest.</p></td>
				<td class="validate">'.$this->form->get_button( 'Validate Rich Pins', 'button-secondary', null, 
					'http://developers.pinterest.com/rich_pins/validator/?link='.urlencode( get_permalink( $post->ID ) ), true ).'</td>';
	
				$ret[] = $this->p->util->th( 'Twitter Card Validator' ).'
				<td class="validate"><p>The Twitter Card Validator does not accept query arguments -- copy-paste the following URL into the validation input field.
				To enable the display of Twitter Card information in tweets you must submit a URL for each type of card for approval.</p>'.
				$this->form->get_text( get_permalink( $post->ID ), 'wide' ).'</td>
				<td class="validate">'.$this->form->get_button( 'Validate Twitter Card', 'button-secondary', null, 
					'https://dev.twitter.com/docs/cards/validation/validator', true ).'</td>';

			} else $ret[] = '<td><p class="centered">The Validation Tools will be available when the '.$post_type_name.' is published with public visibility.</p></td>';

			return $ret;
		}

		protected function get_rows_metatags( $post ) {
			$ret = array();
			$post_type = get_post_type_object( $post->post_type );	// since 3.0
			$post_type_name = ucfirst( $post_type->name );

			if ( get_post_status( $post->ID ) == 'publish' ) {
				foreach ( $this->p->meta->header_tags as $m ) {
					$ret[] = '<th class="xshort">'.$m[1].'</th>'.
						'<th class="xshort">'.$m[2].'</th>'.
						'<td class="short">'.$m[3].'</td>'.
						'<th class="xshort">'.$m[4].'</th>'.
						'<td class="wide">'.$m[5].'</td>';
				}
				sort( $ret );
			} else $ret[] = '<td><p class="centered">The Meta Tags Preview will be available when the '.$post_type_name.' is published with public visibility.</p></td>';

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
	}
}

?>
