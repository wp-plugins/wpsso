<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoPostMetaGpl' ) && class_exists( 'WpssoPostMeta' ) ) {

	class WpssoPostMetaGpl extends WpssoPostMeta {

		protected function add_actions() {
			if ( ! is_admin() ) return;

			add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );
		}

		protected function get_rows_header( $post_id ) {
			$ret = array();

			$ret[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$ret[] = $this->p->util->th( 'Topic', 'medium', 'postmeta-og_art_section', $this->post_info ).
			'<td class="blank">'.$this->p->options['og_art_section'].'</td>';

			$ret[] = $this->p->util->th( 'Default Title', 'medium', 'postmeta-og_title', $this->post_info ). 
			'<td class="blank">'.$this->p->webpage->get_title( $this->p->options['og_title_len'], '...', true ).'</td>';
		
			$ret[] = $this->p->util->th( 'Default Description', 'medium', 'postmeta-og_desc', $this->post_info ).
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['og_desc_len'], '...', true ).'</td>';
	
			$ret[] = $this->p->util->th( 'Google Description', 'medium', 'postmeta-meta_desc', $this->post_info ).
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['meta_desc_len'], '...', true, true, false ).	// no hashtags
			'</td>';

			$ret[] = $this->p->util->th( 'Twitter Card Description', 'medium', 'postmeta-tc_desc', $this->post_info ).
			'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['tc_desc_len'], '...', true ).'</td>';

			$ret[] = $this->p->util->th( 'Image ID', 'medium', 'postmeta-og_img_id', $this->post_info ).
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Image URL', 'medium', 'postmeta-og_img_url', $this->post_info ).
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Video URL', 'medium', 'postmeta-og_vid_url', $this->post_info ).
			'<td class="blank">&nbsp;</td>';

			$ret[] = $this->p->util->th( 'Maximum Images', 'medium', 'postmeta-og_img_max', $this->post_info ).
			'<td class="blank">'.$this->p->options['og_img_max'].'</td>';

			$ret[] = $this->p->util->th( 'Maximum Videos', 'medium', 'postmeta-og_vid_max', $this->post_info ).
			'<td class="blank">'.$this->p->options['og_vid_max'].'</td>';

			$ret[] = $this->p->util->th( 'Sharing URL', 'medium', 'postmeta-sharing_url', $this->post_info ).
			'<td class="blank">'.( get_post_status( $post_id ) == 'publish' ? 
				$this->p->util->get_sharing_url( true ) :
				'<p>The Sharing URL will be available when the '.$this->post_info['ptn'].' is published.</p>' ).'</td>';

			return $ret;
		}

		protected function get_rows_social( $post_id ) {
			$ret = array();
			$twitter_cap_len = $this->p->util->tweet_max_len( get_permalink( $post_id ) );
			list( $pid, $video_url ) = $this->get_social_vars( $post_id );

			$ret[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$th = $this->p->util->th( 'Pinterest Image Caption', 'medium', 'postmeta-pin_desc' );
			if ( ! empty( $pid ) ) {
				$img = $this->p->media->get_attachment_image_src( $pid, $this->p->cf['lca'].'-pinterest', false );
				if ( empty( $img[0] ) )
					$ret[] = $th.'<td class="blank"><em>Caption disabled - image ID '.$pid.' is too small for \''.
					$this->p->cf['lca'].'-pinterest\' image dimensions.</em></td>';
				else $ret[] = $th.'<td class="blank">'.
					$this->p->webpage->get_caption( $this->p->options['pin_caption'], $this->p->options['pin_cap_len'] ).'</td>';
			} else $ret[] = $th.'<td class="blank"><em>Caption disabled - no custom Image ID, featured or attached image found.</em></td>';

			$th = $this->p->util->th( 'Tumblr Image Caption', 'medium', 'postmeta-tumblr_img_desc' );
			if ( empty( $this->p->options['tumblr_photo'] ) ) {
				$ret[] = $th.'<td class="blank"><em>\'Use Featured Image\' option is disabled.</em></td>';
			} elseif ( ! empty( $pid ) ) {
				$img = $this->p->media->get_attachment_image_src( $pid, $this->p->cf['lca'].'-tumblr', false );
				if ( empty( $img[0] ) )
					$ret[] = $th.'<td class="blank"><em>Caption disabled - image ID '.$pid.' is too small for \''.
					$this->p->cf['lca'].'-tumblr\' image dimensions.</em></td>';
				else $ret[] = $th.'<td class="blank">'.
					$this->p->webpage->get_caption( $this->p->options['tumblr_caption'], $this->p->options['tumblr_cap_len'] ).'</td>';
			} else $ret[] = $th.'<td class="blank"><em>Caption disabled - no custom Image ID, featured or attached image found.</em></td>';

			$th = $this->p->util->th( 'Tumblr Video Caption', 'medium', 'postmeta-tumblr_vid_desc' );
			if ( ! empty( $vid_url ) )
				$ret[] = $th.'<td class="blank">'.
				$this->p->webpage->get_caption( $this->p->options['tumblr_caption'], $this->p->options['tumblr_cap_len'] ).'</td>';
			else $ret[] = $th.'<td class="blank"><em>Caption disabled - no custom Video URL or embedded video found.</em></td>';

			$ret[] = $this->p->util->th( 'Tweet Text', 'medium', 'postmeta-twitter_desc' ). 
			'<td class="blank">'.$this->p->webpage->get_caption( $this->p->options['twitter_caption'], $twitter_cap_len,
				true, true, true ).'</td>';	// use_post = true, use_cache = true, add_hashtags = true

			$ret[] = $this->p->util->th( 'Disable Social Buttons', 'medium', 'postmeta-buttons_disabled', $this->post_info ).
			'<td class="blank">&nbsp;</td>';

			return $ret;
		}
		
	}
}

?>
