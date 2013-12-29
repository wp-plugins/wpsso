<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2013 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoNgg' ) ) {

	class WpssoNgg {

		private $p;

		public $img_src_preg = '([^\'"]+\/cache\/([0-9]+)_(crop)?_[0-9]+x[0-9]+_[^\/\'"]+|[^\'"]+-nggid0[1-f]([0-9]+)-[^\'"]+)';

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			// try and filter after other plugins that may clobber our changes
			add_filter( 'ngg_image_object', array( &$this, 'add_image_attributes' ), 20, 2 );
			add_filter( 'ngg_get_thumbcode', array( &$this, 'add_thumbcode' ), 20, 2 );
		}

		// apply_filters('ngg_get_thumbcode', $this->thumbcode, $this);
		public function add_thumbcode( $thumbcode, $image ) {
			if ( ! empty( $image->pid ) )
				$thumbcode .= ' data-ngg-pid="'.$image->pid.'"';
			return $thumbcode;
		}

		// apply_filters('ngg_image_object', $picturelist[$key], $picture->pid);
		public function add_image_attributes( $image, $pid ) {
			foreach ( array( 'href', 'imageHTML', 'thumbHTML' ) as $key )
				if ( ! empty( $image->$key ) )
					$image->$key = preg_replace( '/<img /i', '<img data-ngg-pid="'.$pid.'" ', $image->$key );
			$image->style .= ' data-ngg-pid="'.$pid.'"';
			return $image;
		}

		// called to get an image url from an ngg picture id and a media size name (the pid must be formatted as 'ngg-#')
		public function get_image_src( $pid, $size_name = 'thumbnail', $check_dupes = true ) {
			$this->p->debug->args( array( 'pid' => $pid, 'size_name' => $size_name, 'check_dupes' => $check_dupes ) );

			if ( $this->p->is_avail['ngg'] !== true || strpos( $pid, 'ngg-' ) !== 0 )
				return array( null, null, null, null );

			$size_info = $this->p->media->get_size_info( $size_name );
			$pid = substr( $pid, 4 );
			$img_url = '';
			$img_width = -1;
			$img_height = -1;
			$img_cropped = empty( $size_info['crop'] ) ? 0 : 1;
			$ret_empty = array( null, null, null, null );

			if ( version_compare( $this->p->ngg_version, '2.0.0', '<' ) ) {
				global $nggdb;
				$image = $nggdb->find_image( $pid );	// returns an nggImage object
				if ( ! empty( $image ) ) {
					$crop_arg = $size_info['crop'] == 1 ? 'crop' : '';
					$img_url = $image->cached_singlepic_file( $size_info['width'], $size_info['height'], $crop_arg ); 
					// if the image file doesn't exist, use the dynamic image url
					if ( empty( $img_url ) ) {
						$img_url = trailingslashit( site_url() ).
							'index.php?callback=image&amp;pid='.$pid.
							'&amp;width='.$size_info['width'].
							'&amp;height='.$size_info['height'].
							'&amp;mode='.$crop_arg;
						$img_width = $size_info['width'];
						$img_height = $size_info['height'];
					} else {
						// get the real image width and height (for ngg pre-v2.0)
						$cachename = $image->pid.'_'.$crop_arg.'_'. $size_info['width'].'x'.$size_info['height'].'_'.$image->filename;
						$cachefolder = WINABSPATH.$this->p->ngg_options['gallerypath'].'cache/';
						$cached_file = $cachefolder.$cachename;
						if ( file_exists( $cached_file ) ) {
							$file_info = getimagesize( $cached_file );
							if ( ! empty( $file_info[0] ) && ! empty( $file_info[1] ) ) {
								$img_width = $file_info[0];
								$img_height = $file_info[1];
							}
						} else $this->p->debug->log( $cached_file.' not found' );
					}
				}
			} else {
				$dynthumbs = C_Component_Registry::get_instance()->get_utility('I_Dynamic_Thumbnails_Manager');
				$mapper = C_Component_Registry::get_instance()->get_utility( 'I_Image_Mapper' );
				$image = new C_Image_Wrapper( $mapper->find( $pid ) );
				$storage = $image->get_storage();
				$img_meta = $image->_orig_image->meta_data;

				// protect against missing array and/or array elements
				$full_width = empty( $img_meta['full']['width'] ) ? 0 : $img_meta['full']['width'];
				$full_height = empty( $img_meta['full']['height'] ) ? 0 : $img_meta['full']['height'];

				// check to see that the full size image is large enough for our requirements
				$is_sufficient_width = $full_width >= $size_info['width'] ? true : false;
				$is_sufficient_height = $full_height >= $size_info['height'] ? true : false;

				if ( empty( $full_width ) || empty( $full_height ) ) {
					$this->p->debug->log( 'ngg image meta_data missing full width and/or height elements' );

				// if the full size is too small, get the full size image URL instead
				} elseif ( ( empty( $size_info['crop'] ) && ( ! $is_sufficient_width && ! $is_sufficient_height ) ) ||
					( ! empty( $size_info['crop'] ) && ( ! $is_sufficient_width || ! $is_sufficient_height ) ) ) {

					$this->p->debug->log( 'full meta sizes '.$full_width.'x'.$full_height.' smaller than '.
						$size_name.' ('.$size_info['width'].'x'.$size_info['height'].
						( empty( $size_info['crop'] ) ? '' : ' cropped' ).') - fetching "full" image url instead' );

					$img_url = $storage->get_image_url( $image, 'full' );
					$img_width = $full_width;
					$img_height = $full_height;

				} else {
					$params = array(
						'width' => $size_info['width'], 
						'height' => $size_info['height'], 
						'crop' => $size_info['crop'],
					);
					$img_url = $storage->get_image_url( $image, $dynthumbs->get_size_name( $params ) );

					// determine "accurate" sizes, as best we can
					if ( empty( $size_info['crop'] ) ) {
						$ratio = $full_width / $size_info['width'];
						$img_width = $size_info['width'];
						$img_height = (int) round( $full_height / $ratio );
					} else {
						$img_width = $size_info['width'];
						$img_height = $size_info['height'];
					}
				}
				if ( empty( $img_url ) )
					$this->p->debug->log( 'exiting early: returned img_url is empty' );
			}
			if ( empty( $img_url ) )
				return $ret_empty;

			if ( ! empty( $this->p->options['plugin_ignore_small_img'] ) ) {
				$is_sufficient_width = $img_width >= $size_info['width'] ? true : false;
				$is_sufficient_height = $img_height >= $size_info['height'] ? true : false;

				if ( ( empty( $size_info['crop'] ) && ( ! $is_sufficient_width && ! $is_sufficient_height ) ) ||
					( ! empty( $size_info['crop'] ) && ( ! $is_sufficient_width || ! $is_sufficient_height ) ) ) {

					if ( is_admin() )
						$this->p->notice->err( 'NGG image ID '.$pid.' rejected - '.$img_url.
							' ('.$img_width.'x'.$img_height.') too small for '.$size_name.
							' ('.$size_info['width'].'x'.$size_info['height'].
							( empty( $size_info['crop'] ) ? '' : ' cropped' ).').' );

					$this->p->debug->log( 'exiting early: returned image dimensions are smaller than'.
						' ('.$size_info['width'].'x'.$size_info['height'].
						( empty( $size_info['crop'] ) ? '' : ' cropped' ).')' );

					return $ret_empty;
				}
			}
			if ( $check_dupes == false || $this->p->util->is_uniq_url( $img_url ) )
				return array( $img_url, $img_width, $img_height, $img_cropped );

			return $ret_empty;
		}

		public function get_content_images( $num = 0, $size_name = 'thumbnail', $use_post = true, $check_dupes = true, $content = null ) {
			if ( $this->p->is_avail['ngg'] !== true ) return;
			$og_ret = array();
			// allow custom content to be passed
			if ( empty( $content ) )
				$content = $this->p->webpage->get_content( $use_post );
			if ( empty( $content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret; 
			}
			if ( preg_match_all( '/<((div|a|img)[^>]*? (data-ngg-pid|data-image-id)=[\'"]([0-9]+)[\'"]|(img)[^>]*? (src)=[\'"]'.$this->img_src_preg.'[\'"])[^>]*>/s', 
				$content, $match, PREG_SET_ORDER ) ) {
				$this->p->debug->log( count( $match ).' x matching <div|a|img/> html tag(s) found' );
				foreach ( $match as $img_num => $img_arr ) {
					$tag_value = $img_arr[0];
					if ( empty( $img_arr[5] ) ) {
						$tag_name = $img_arr[2];	// div|a|img
						$attr_name = $img_arr[3];	// data-ngg-pid|data-image-id
						$attr_value = $img_arr[4];	// pid
						$pid = 'ngg-'.$img_arr[4];	// pid
					} else {
						$tag_name = $img_arr[5];	// img
						$attr_name = $img_arr[6];	// src
						$attr_value = $img_arr[7];	// url
						$pid = empty( $img_arr[10] ) ? 	// pid
							'ngg-'.$img_arr[8] : 'ngg-'.$img_arr[10];
					}
					$this->p->debug->log( 'match '.$img_num.': '.$tag_name.' '.$attr_name.'="'.$attr_value.'"' );
					list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
						$og_image['og:image:cropped'] ) = $this->get_image_src( $pid, $size_name, $check_dupes );
					if ( ! empty( $og_image['og:image'] ) && 
						$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
							return $og_ret;
				}
				return $og_ret;	// return immediately and ignore any other type of image
			}
			$this->p->debug->log( 'no matching <div|a|img/> html tag found' );
			return $og_ret;
		}

		// want_this can be either 'gallery' or 'pid' (returns image when browsing the gallery)
		public function get_gallery_images( $num = 0, $size_name = 'large', $want_this = 'gallery', $check_dupes = false ) {
			if ( $this->p->is_avail['ngg'] !== true ) 
				return array();

			global $post, $wp_query, $nggdb;
			$og_ret = array();

			if ( empty( $post ) ) { 
				$this->p->debug->log( 'exiting early: empty post object' ); 
				return $og_ret;
			} elseif ( empty( $post->post_content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret;
			}

			// sanitize possible ngg pre-v2 query values
			$ngg_album = empty( $wp_query->query['album'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['album'] );
			$ngg_gallery = empty( $wp_query->query['gallery'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['gallery'] );
			$ngg_pageid = empty( $wp_query->query['pageid'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['pageid'] );
			$ngg_pid = empty( $wp_query->query['pid'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['pid'] );

			if ( ! empty( $ngg_album ) || ! empty( $ngg_gallery ) || ! empty( $ngg_pid ) ) {
				$this->p->debug->log( 'ngg query found = pageid:'.$ngg_pageid.' album:'.$ngg_album.
					' gallery:'.$ngg_gallery.' pid:'.$ngg_pid );
			}
			if ( $want_this == 'gallery' && $ngg_pid > 0 ) {
				$this->p->debug->log( 'exiting early: want gallery but have query for pid:'.$ngg_pid );
				return $og_ret;
			} elseif ( $want_this == 'pid' && empty( $ngg_pid ) ) {
				$this->p->debug->log( 'exiting early: want pid but don\'t have a query for pid' );
				return $og_ret;
			}

			if ( preg_match( '/\[(nggalbum|album|nggallery|nggtags)(| [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*| [^\]]*)\]/im', $post->post_content, $match ) ) {
				$shortcode_type = strtolower( $match[1] );
				$shortcode_id = ! empty( $match[3] ) ? $match[3] : 0;
				$this->p->debug->log( '['.$shortcode_type.'] shortcode found (id:'.$shortcode_id.')' );
				switch ( $shortcode_type ) {
					case 'nggtags' :
						$content = do_shortcode( $match[0] );
						$content = preg_replace( '/\['.$shortcode_type.'[^\]]*\]/', '', $content );	// prevent loops, just in case
						// provide the expanded content and extract images
						$og_ret = array_merge( $og_ret, 
							$this->p->media->get_content_images( $num, $size_name, $post->ID, $check_dupes, $content ) );
						break;
					default :
						// always trust hard-coded shortcode ID more than query arguments
						$ngg_album = $shortcode_type == 'nggalbum' || $shortcode_type == 'album' ? $shortcode_id : $ngg_album;
						$ngg_gallery = $shortcode_type == 'nggallery' ? $shortcode_id : $ngg_gallery;
						// security checks
						if ( $ngg_gallery > 0 && $ngg_album > 0 ) {
							$nggAlbum = $nggdb->find_album( $ngg_album );
							if ( in_array( $ngg_gallery, $nggAlbum->gallery_ids, true ) ) {
								$this->p->debug->log( 'security check passed = gallery:'.$ngg_gallery.' is in album:'.$ngg_album );
							} else {
								$this->p->debug->log( 'security check failed = gallery:'.$ngg_gallery.' is not in album:'.$ngg_album );
								return $og_ret;
							}
						}
						if ( $ngg_pid > 0 && $ngg_gallery > 0 ) {
							$pids = $nggdb->get_ids_from_gallery( $ngg_gallery );
							if ( in_array( $ngg_pid, $pids, true ) ) {
								$this->p->debug->log( 'security check passed = pid:'.$ngg_pid.' is in gallery:'.$ngg_gallery );
							} else {
								$this->p->debug->log( 'security check failed = pid:'.$ngg_pid.' is not in gallery:'.$ngg_gallery );
								return $og_ret;
							}
						}
						switch ( $want_this ) {
							case 'gallery' :
								if ( $ngg_gallery > 0 ) {
									// get_ids_from_gallery($id, $order_by = 'sortorder', $order_dir = 'ASC', $exclude = true)
									foreach ( array_slice( $nggdb->get_ids_from_gallery( $ngg_gallery, 'sortorder', 'ASC', true ), 0, $num ) as $pid ) {
										list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
											$og_image['og:image:cropped'] )= $this->get_image_src( 'ngg-'.$pid, $size_name, $check_dupes );
										if ( ! empty( $og_image['og:image'] ) && 
											$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
												return $og_ret;
									}
								}
								break;
							case 'pid' :
								if ( $ngg_pid > 0 ) {
									list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
										$og_image['og:image:cropped'] )= $this->get_image_src( 'ngg-'.$ngg_pid, $size_name, $check_dupes );
									if ( ! empty( $og_image['og:image'] ) && 
										$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
											return $og_ret;
								}
								break;
						}
						break;
				}	
			} else $this->p->debug->log( '[nggalbum|album|nggallery|nggtags] shortcode not found' );
			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		// parse ngg pre-v2 query arguments
		public function get_query_images( $num = 0, $size_name = 'thumbnail', $check_dupes = true ) {
			// exit if ngg is not active, or version is 2.0.0 or greater
			if ( $this->p->is_avail['ngg'] !== true || version_compare( $this->p->ngg_version, '2.0.0', '>=' ) )
				return array();

			global $post, $wp_query, $nggdb;
			$og_ret = array();

			if ( empty( $post ) ) { 
				$this->p->debug->log( 'exiting early: empty post object' ); 
				return $og_ret; 
			} elseif ( empty( $post->post_content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret;
			}

			// sanitize possible query values
			$ngg_album = empty( $wp_query->query['album'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['album'] );
			$ngg_gallery = empty( $wp_query->query['gallery'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['gallery'] );
			$ngg_pageid = empty( $wp_query->query['pageid'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['pageid'] );
			$ngg_pid = empty( $wp_query->query['pid'] ) ? '' : preg_replace( '/[^0-9]/', '', $wp_query->query['pid'] );

			if ( empty( $ngg_album ) && empty( $ngg_gallery ) && empty( $ngg_pid ) ) {
				$this->p->debug->log( 'exiting early: no ngg query values' ); 
				return $og_ret;
			} else {
				$this->p->debug->log( 'ngg query found = pageid:'.$ngg_pageid.' album:'.$ngg_album.
					' gallery:'.$ngg_gallery.' pid:'.$ngg_pid );
			}

			if ( preg_match( '/\[(nggalbum|album|nggallery)(| [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*| [^\]]*)\]/im', $post->post_content, $match ) ) {
				$shortcode_type = $match[1];
				$shortcode_id = ! empty( $match[3] ) ? $match[3] : 0;
				$this->p->debug->log( 'ngg query with ['.$shortcode_type.'] shortcode (id:'.$shortcode_id.')' );

				// always trust hard-coded shortcode ID more than query arguments
				$ngg_album = $shortcode_type == 'nggalbum' || $shortcode_type == 'album' ? $shortcode_id : $ngg_album;
				$ngg_gallery = $shortcode_type == 'nggallery' ? $shortcode_id : $ngg_gallery;

				// security checks
				if ( $ngg_gallery > 0 && $ngg_album > 0 ) {
					$nggAlbum = $nggdb->find_album( $ngg_album );
					if ( in_array( $ngg_gallery, $nggAlbum->gallery_ids, true ) ) {
						$this->p->debug->log( 'security check passed = gallery:'.$ngg_gallery.' is in album:'.$ngg_album );
					} else {
						$this->p->debug->log( 'security check failed = gallery:'.$ngg_gallery.' is not in album:'.$ngg_album );
						return $og_ret;
					}
				}
				if ( $ngg_pid > 0 && $ngg_gallery > 0 ) {
					$pids = $nggdb->get_ids_from_gallery( $ngg_gallery );
					if ( in_array( $ngg_pid, $pids, true ) ) {
						$this->p->debug->log( 'security check passed = pid:'.$ngg_pid.' is in gallery:'.$ngg_gallery );
					} else {
						$this->p->debug->log( 'security check failed = pid:'.$ngg_pid.' is not in gallery:'.$ngg_gallery );
						return $og_ret;
					}
				}
				if ( $ngg_pid > 0 ) {
					$this->p->debug->log( 'getting image for ngg query pid:'.$ngg_pid );
					list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
						$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$ngg_pid, $size_name, $check_dupes );
					if ( ! empty( $og_image['og:image'] ) && 
						$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
							return $og_ret;
				} elseif ( $ngg_gallery > 0 ) {
					$gallery = $nggdb->find_gallery( $ngg_gallery );
					if ( ! empty( $gallery ) ) {
						if ( ! empty( $gallery->previewpic ) ) {
							$this->p->debug->log( 'getting previewpic:'.$gallery->previewpic.' for gallery:'.$ngg_gallery );
							list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
								$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$gallery->previewpic, $size_name, $check_dupes );
							if ( ! empty( $og_image['og:image'] ) && 
								$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
									return $og_ret;
						} else $this->p->debug->log( 'no previewpic for gallery:'.$ngg_gallery );
					} else $this->p->debug->log( 'no gallery:'.$ngg_gallery.' found' );
				} elseif ( $ngg_album > 0 ) {
					$album = $nggfb->find_album( $ngg_album );
					if ( ! empty( $albums ) ) {
						if ( ! empty( $album->previewpic ) ) {
							$this->p->debug->log( 'getting previewpic:'.$album->previewpic.' for album:'.$ngg_album );
							list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
								$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$album->previewpic, $size_name, $check_dupes );
							if ( ! empty( $og_image['og:image'] ) && 
								$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
									return $og_ret;
						} else $this->p->debug->log( 'no previewpic for album:'.$ngg_album );
					} else $this->p->debug->log( 'no album:'.$ngg_album.' found' );
				}
			} else $this->p->debug->log( 'ngg query without [nggalbum|album|nggallery] shortcode' );

			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		public function get_shortcode_images( $num = 0, $size_name = 'thumbnail', $check_dupes = true ) {
			if ( $this->p->is_avail['ngg'] !== true ) 
				return array();

			global $post, $wpdb;
			$og_ret = array();

			if ( empty( $post ) ) { 
				$this->p->debug->log( 'exiting early: empty post object' ); 
				return $og_ret; 
			} elseif ( empty( $post->post_content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret; 
			}

			if ( preg_match_all( '/\[(nggalbum|album)(| [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*| [^\]]*)\]/im', $post->post_content, $match, PREG_SET_ORDER ) ) {
				foreach ( $match as $album ) {
					$og_image = array();
					if ( empty( $album[3] ) ) {
						$ngg_album = 0;
						$this->p->debug->log( 'album id zero or not found - setting album id to 0 (all)' );
					} else $ngg_album = $album[3];
					$this->p->debug->log( '['.$album[1].'] shortcode found (id:'.$ngg_album.')' );
					if ( $ngg_album > 0 ) 
						$albums = $wpdb->get_results( 'SELECT * FROM '.$wpdb->nggalbum.' WHERE id IN (\''.$ngg_album.'\')', OBJECT_K );
					else $albums = $wpdb->get_results( 'SELECT * FROM '.$wpdb->nggalbum, OBJECT_K );
					if ( is_array( $albums ) ) {
						foreach ( $albums as $row ) {
							if ( ! empty( $row->previewpic ) ) {
								$this->p->debug->log( 'getting previewpic:'.$row->previewpic.' for album:'.$row->id );
								list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
									$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$row->previewpic, $size_name, $check_dupes );
								if ( ! empty( $og_image['og:image'] ) && 
									$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
										return $og_ret;
							} else $this->p->debug->log( 'no previewpic for album:'.$row->id );
						}
					} else $this->p->debug->log( 'no album(s) found' );
				}
			} else $this->p->debug->log( 'no [nggalbum|album] shortcode found' );

			if ( preg_match_all( '/\[(nggallery) [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*\]/im', $post->post_content, $match, PREG_SET_ORDER ) ) {
				foreach ( $match as $gallery ) {
					$this->p->debug->log( '['.$gallery[1].'] shortcode found (id:'.$gallery[2].')' );
					$og_image = array();
					$ngg_gallery = $gallery[2];
					$galleries = $wpdb->get_results( 'SELECT * FROM '.$wpdb->nggallery.' WHERE gid IN (\''.$ngg_gallery.'\')', OBJECT_K );
					if ( is_array( $galleries ) ) {
						foreach ( $galleries as $row ) {
							if ( ! empty( $row->previewpic ) ) {
								$this->p->debug->log( 'getting previewpic:'.$row->previewpic.' for gallery:'.$row->gid );
								list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
									$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$row->previewpic, $size_name, $check_dupes );
								if ( ! empty( $og_image['og:image'] ) && 
									$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
										return $og_ret;
							} else $this->p->debug->log( 'no previewpic for gallery:'.$row->gid );
						}
					} else $this->p->debug->log( 'no gallery:'.$ngg_gallery.' found' );
				}
			} else $this->p->debug->log( 'no [nggallery] shortcode found' );

			if ( preg_match_all( '/\[(singlepic) [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*\]/im', $post->post_content, $match, PREG_SET_ORDER ) ) {
				foreach ( $match as $singlepic ) {
					$this->p->debug->log( '['.$singlepic[1].'] shortcode found (id:'.$singlepic[2].')' );
					$og_image = array();
					$pid = $singlepic[2];
					$this->p->debug->log( 'getting image for singlepic:'.$pid );
					list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
						$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$pid, $size_name, $check_dupes );
					if ( ! empty( $og_image['og:image'] ) && 
						$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
							return $og_ret;
				}
			} else $this->p->debug->log( 'no [singlepic] shortcode found' );

			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		public function get_singlepic_images( $num = 0, $size_name = 'thumbnail', $check_dupes = false ) {
			if ( $this->p->is_avail['ngg'] !== true ) 
				return array();

			global $post;
			$og_ret = array();

			if ( empty( $post ) ) { 
				$this->p->debug->log( 'exiting early: empty post object' ); 
				return $og_ret; 
			} elseif ( empty( $post->post_content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret; 
			}

			if ( preg_match_all( '/\[(singlepic) [^\]]*id=[\'"]*([0-9]+)[\'"]*[^\]]*\]/im', $post->post_content, $match, PREG_SET_ORDER ) ) {
				foreach ( $match as $singlepic ) {
					$this->p->debug->log( '['.$singlepic[1].'] shortcode found (id:'.$singlepic[2].')' );
					$og_image = array();
					$pid = $singlepic[2];
					$this->p->debug->log( 'getting image for singlepic:'.$pid );
					list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
						$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$pid, $size_name, $check_dupes );
					if ( ! empty( $og_image['og:image'] ) && 
						$this->p->util->push_max( $og_ret, $og_image, $num ) ) 
							return $og_ret;
				}
			} else $this->p->debug->log( 'no [singlepic] shortcode found' );

			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		// called from the view/gallery-meta.php template
		public function get_from_images( $num = 0, $size_name = 'thumbnail', $ngg_images = array() ) {
			if ( $this->p->is_avail['ngg'] !== true ) 
				return array();
			$og_ret = array();
			if ( is_array( $ngg_images ) ) {
				foreach ( $ngg_images as $image ) {
					if ( ! empty( $image->pid ) ) {
						$og_image = array();
						list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
							$og_image['og:image:cropped'] ) = $this->get_image_src( 'ngg-'.$image->pid, $size_name );
						if ( ! empty( $og_image['og:image'] ) && 
							$this->p->util->push_max( $og_ret, $og_image, $num ) )
								return $og_ret;
					}
				}
			}
			return $og_ret;
		}

		// called from the view/gallery-meta.php template
		public function get_tags( $pid ) {
			$tags = apply_filters( $this->p->cf['lca'].'_ngg_tags_seed', array(), $pid );
			if ( ! empty( $tags ) )
				$this->p->debug->log( 'ngg tags seed = "'.implode( ',', $tags ).'"' );
			else {
				if ( $this->p->is_avail['ngg'] == true && is_string( $pid ) && substr( $pid, 0, 4 ) == 'ngg-' )
					$tags = wp_get_object_terms( substr( $pid, 4 ), 'ngg_tag', 'fields=names' );
				$tags = array_map( 'strtolower', $tags );
			}
			return apply_filters( $this->p->cf['lca'].'_ngg_tags', $tags, $pid );
		}
	}
}

?>
