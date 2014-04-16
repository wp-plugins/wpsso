<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSubmenuGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoSubmenuGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_opengraph', 'Open Graph Settings', array( &$this, 'show_metabox_opengraph' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_publishers', 'Publisher Settings', array( &$this, 'show_metabox_publishers' ), $this->pagehook, 'normal' );

			// issues a warning notice if the default image size is too small
			$this->p->media->get_default_image( 1, $this->p->cf['lca'].'-opengraph', false );
		}

		public function show_metabox_opengraph() {
			$metabox = 'og';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'webpage' => 'Title and Description',
				'images' => 'Images',
				'videos' => 'Videos',
				'author' => 'Authorship' ) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		public function show_metabox_publishers() {
			$metabox = 'pub';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'facebook' => 'Facebook',
				'google' => 'Google / SEO',
				'pinterest' => 'Pinterest',
				'twitter' => 'Twitter',
			) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			$user_ids = array();
			foreach ( get_users() as $user ) 
				$user_ids[$user->ID] = $user->display_name;
			$user_ids[0] = 'none';
			switch ( $metabox.'-'.$key ) {
				case 'og-webpage':
					$rows[] = $this->p->util->th( 'Website Topic', 'highlight', 'og_art_section' ).
					'<td>'.$this->form->get_select( 'og_art_section', $this->p->util->get_topics() ).'</td>';

					$rows[] = $this->p->util->th( 'Site Name', null, 'og_site_name', array( 'is_locale' => true ) ).
					'<td>'.$this->form->get_input( SucomUtil::get_locale_key( 'og_site_name' ), 
						null, null, null, get_bloginfo( 'name', 'display' ) ).'</td>';

					$rows[] = $this->p->util->th( 'Site Description', 'highlight', 'og_site_description', array( 'is_locale' => true ) ).
					'<td>'.$this->form->get_textarea( SucomUtil::get_locale_key( 'og_site_description' ), 
						null, null, null, get_bloginfo( 'description', 'display' ) ).'</td>';

					$rows[] = $this->p->util->th( 'Title Separator', null, 'og_title_sep' ).
					'<td>'.$this->form->get_input( 'og_title_sep', 'short' ).'</td>';

					$rows[] = $this->p->util->th( 'Title Length', null, 'og_title_len' ).
					'<td>'.$this->form->get_input( 'og_title_len', 'short' ).' characters or less</td>';

					$rows[] = $this->p->util->th( 'Description Length', null, 'og_desc_len' ).
					'<td>'.$this->form->get_input( 'og_desc_len', 'short' ).' characters or less</td>';

					$rows[] = $this->p->util->th( 'Add Page Title in Tags', null, 'og_page_title_tag' ).
					'<td>'.$this->form->get_checkbox( 'og_page_title_tag' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Add Page Ancestor Tags', null, 'og_page_parent_tags' ).
					'<td>'.$this->form->get_checkbox( 'og_page_parent_tags' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Number of Hashtags to Include', null, 'og_desc_hashtags' ).
					'<td>'.$this->form->get_select( 'og_desc_hashtags', 
						range( 0, $this->p->cf['form']['max_desc_hashtags'] ), 'short', null, true ).' tag names</td>';
	
					$rows[] = $this->p->util->th( 'Content Begins at First Paragraph', null, 'og_desc_strip' ).
					'<td>'.$this->form->get_checkbox( 'og_desc_strip' ).'</td>';
					break;

				case 'og-images':
					$img_id_pre = array( 'wp' => 'Media Library' );
					if ( $this->p->is_avail['media']['ngg'] == true ) 
						$img_id_pre['ngg'] = 'NextGEN Gallery';

					$rows[] = $this->p->util->th( 'Image Dimensions', 'highlight', 'og_img_dimensions' ).
					'<td>Width '.$this->form->get_input( 'og_img_width', 'short' ).' x '.
					'Height '.$this->form->get_input( 'og_img_height', 'short' ).' &nbsp; '.
					'Crop '.$this->form->get_checkbox( 'og_img_crop' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Default Image ID', 'highlight', 'og_def_img_id' ).
					'<td>'.$this->form->get_input( 'og_def_img_id', 'short' ).' in the '.
					$this->form->get_select( 'og_def_img_id_pre', $img_id_pre ).'</td>';
	
					$rows[] = $this->p->util->th( 'Default Image URL', null, 'og_def_img_url' ).
					'<td>'.( empty( $this->p->options['og_def_img_id'] ) ? 
						$this->form->get_input( 'og_def_img_url', 'wide' ) :
						$this->form->get_fake_input( 'og_def_img_url', 'wide' ) ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use Default Image on Indexes', null, 'og_def_img_on_index' ).
					'<td>'.$this->form->get_checkbox( 'og_def_img_on_index' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use Default Image on Search Results', null, 'og_def_img_on_search' ).
					'<td>'.$this->form->get_checkbox( 'og_def_img_on_search' ).'</td>';
	
					if ( $this->p->is_avail['media']['ngg'] === true ) {
						$rows[] = $this->p->util->th( 'Add Tags from NGG Featured Image', null, 'og_ngg_tags' ).
						( isset( $this->p->addons['media']['ngg'] ) ?
							'<td>'.$this->form->get_checkbox( 'og_ngg_tags' ).'</td>' :
							'<td class="blank">'.$this->form->get_fake_checkbox( 'og_ngg_tags' ).'</td>' );
					} else $rows[] = $this->form->get_hidden( 'og_ngg_tags' );
	
					$rows[] = $this->p->util->th( 'Maximum Images', null, 'og_img_max' ).
					'<td>'.$this->form->get_select( 'og_img_max', 
						range( 0, $this->p->cf['form']['max_media_items'] ), 'short', null, true ).'</td>';
					break;

				case 'og-videos':
					$rows[] = $this->p->util->th( 'Default Video URL', null, 'og_def_vid_url' ).
					'<td>'.$this->form->get_input( 'og_def_vid_url', 'wide' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use Default Video on Indexes', null, 'og_def_vid_on_index' ).
					'<td>'.$this->form->get_checkbox( 'og_def_vid_on_index' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use Default Video on Search Results', null, 'og_def_vid_on_search' ).
					'<td>'.$this->form->get_checkbox( 'og_def_vid_on_search' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Maximum Videos', null, 'og_vid_max' ).
					'<td>'.$this->form->get_select( 'og_vid_max', 
						range( 0, $this->p->cf['form']['max_media_items'] ), 'short', null, true ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use HTTPS for Video APIs', null, 'og_vid_https' ).
					'<td>'.$this->form->get_checkbox( 'og_vid_https' ).'</td>';
					break;

				case 'og-author':
					$rows[] = $this->p->util->th( 'Author Profile URL', null, 'og_author_field' ).
					'<td>'.$this->form->get_select( 'og_author_field', $this->author_contact_fields() ).'</td>';

					$rows[] = $this->p->util->th( 'Fallback to Author Index URL', null, 'og_author_fallback' ).
					'<td>'.$this->form->get_checkbox( 'og_author_fallback' ).'</td>';
	
					$rows[] = $this->p->util->th( 'Default Author when Missing', null, 'og_def_author_id' ).
					'<td>'.$this->form->get_select( 'og_def_author_id', $user_ids, null, null, true ).'</td>';
	
					$rows[] = $this->p->util->th( 'Use Default Author on Indexes', null, 'og_def_author_on_index' ).
					'<td>'.$this->form->get_checkbox( 'og_def_author_on_index' ).' defines index webpages as articles</td>';
	
					$rows[] = $this->p->util->th( 'Default Author on Search Results', null, 'og_def_author_on_search' ).
					'<td>'.$this->form->get_checkbox( 'og_def_author_on_search' ).' defines search webpages as articles</td>';

					$rows[] = $this->p->util->th( 'Article Publisher Page URL', 'highlight', 'og_publisher_url' ).
					'<td>'.$this->form->get_input( 'og_publisher_url', 'wide' ).'</td>';
					break;

				case 'pub-facebook':
					$rows[] = $this->p->util->th( 'Facebook Admin(s)', 'highlight', 'fb_admins' ).
					'<td>'.$this->form->get_input( 'fb_admins' ).'</td>';

					$rows[] = $this->p->util->th( 'Facebook Application ID', null, 'fb_app_id' ).
					'<td>'.$this->form->get_input( 'fb_app_id' ).'</td>';

					$rows[] = $this->p->util->th( 'Default Language', null, 'fb_lang' ).
					'<td>'.$this->form->get_select( 'fb_lang', SucomUtil::get_pub_lang( 'facebook' ) ).'</td>';
					break;

				case 'pub-google':
					$rows[] = $this->p->util->th( 'Description Length', null, 'google_desc_len' ).
					'<td>'.$this->form->get_input( 'seo_desc_len', 'short' ).' characters or less</td>';

					$rows[] = $this->p->util->th( 'Author Name Format', null, 'google_author_name' ).
					'<td>'.$this->form->get_select( 'seo_author_name', $this->author_name_fields() ).'</td>';
	
					$rows[] = $this->p->util->th( 'Author Link URL', null, 'google_author_field' ).
					'<td>'.$this->form->get_select( 'link_author_field', $this->author_contact_fields() ).'</td>';

					$rows[] = $this->p->util->th( 'Default Author when Missing', null, 'google_def_author_id' ).
					'<td>'.$this->form->get_select( 'seo_def_author_id', $user_ids, null, null, true ).'</td>';

					$rows[] = $this->p->util->th( 'Use Default Author on Indexes', null, 'google_def_author_on_index' ).
					'<td>'.$this->form->get_checkbox( 'seo_def_author_on_index' ).'</td>';

					$rows[] = $this->p->util->th( 'Default Author on Search Results', null, 'google_def_author_on_search' ).
					'<td>'.$this->form->get_checkbox( 'seo_def_author_on_search' ).'</td>';
			
					$rows[] = $this->p->util->th( 'Publisher Link URL', 'highlight', 'google_publisher_url' ).
					'<td>'.$this->form->get_input( 'link_publisher_url', 'wide' ).'</td>';
					break;

				case 'pub-pinterest':
					$rows[] = '<td colspan="2" style="padding-bottom:10px;">'.$this->p->msgs->get( 'pub-pinterest-info' ).'</td>';
					$rows[] = $this->p->util->th( 'Author Name Format', null, 'rp_author_name' ).
					'<td>'.$this->form->get_select( 'rp_author_name', $this->author_name_fields() ).'</td>';
	
					break;
			}
			return $rows;
		}

		private function author_contact_fields() {
			return array_merge( array( 'none' => '' ), 	// make sure [none] is first
				$this->p->user->add_contact_methods( array( 
					'author' => 'Author Index', 
					'url' => 'Website'
				) )
			);
		}

		private function author_name_fields() {
			return array( 
				'none' => '',
				'fullname' => 'First and Last Names',
				'display_name' => 'Display Name',
				'nickname' => 'Nickname',
			);
		}
	}
}

?>
