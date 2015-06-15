<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2015 - Jean-Sebastien Morisset - http://surniaulula.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoSchema' ) ) {

	class WpssoSchema {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			add_filter( 'language_attributes', array( &$this, 'add_doctype' ), WPSSO_DOCTYPE_PRIORITY, 1 );
		}

		public function add_doctype( $doctype ) {
			$obj = $this->p->util->get_post_object( false );
			$post_id = empty( $obj->ID ) || empty( $obj->post_type ) ? 0 : $obj->ID;
			$post_type = '';
			$item_type = 'Blog';	// default value for non-singular webpages

			if ( is_singular() ) {
				if ( ! empty( $obj->post_type ) )
					$post_type = $obj->post_type;
				switch ( $post_type ) {
					case 'article':
					case 'book':
					case 'blog':
					case 'event':
					case 'organization':
					case 'person':
					case 'place':
					case 'product':
					case 'review':
					case 'other':
						$item_type = ucfirst( $post_type );
						break;
					case 'local.business':
						$item_type = 'LocalBusiness';
						break;
					default:
						$item_type = 'Article';
						break;
				}
			} elseif ( $this->p->util->force_default_author() )
				$item_type = 'Article';

			$item_type = apply_filters( $this->p->cf['lca'].'_doctype_schema_type', $item_type, $post_id, $obj );

			if ( ! empty( $item_type ) ) {
				if ( strpos( $doctype, ' itemscope="itemscope" ' ) !== false )
					$doctype = preg_replace( '/ itemscope="itemscope" /', 
						' itemscope ', $doctype );
				elseif ( strpos( $doctype, ' itemscope ' ) === false )
					$doctype .= ' itemscope ';

				if ( strpos( $doctype, ' itemtype="http://schema.org/' ) !== false )
					$doctype = preg_replace( '/ itemtype="http:\/\/schema.org\/[^"]+"/',
						' itemtype="http://schema.org/'.$item_type.'"', $doctype );
				else $doctype .= ' itemtype="http://schema.org/'.$item_type.'"';
			}

			return $doctype;
		}

		public function get_meta_array( $use_post, &$obj, &$meta_og = array() ) {
			$meta_schema = array();

			if ( ! empty( $this->p->options['add_meta_itemprop_name'] ) ) {
				if ( ! empty( $meta_og['og:title'] ) )
					$meta_schema['name'] = $meta_og['og:title'];
			}

			if ( ! empty( $this->p->options['add_meta_itemprop_description'] ) ) {
				$meta_schema['description'] = $this->p->webpage->get_description( $this->p->options['og_desc_len'], 
					'...', $use_post, true, true, true, 'schema_desc' );	// custom meta = schema_desc
			}

			if ( ! empty( $this->p->options['add_meta_itemprop_url'] ) ) {
				if ( ! empty( $meta_og['og:url'] ) )
					$meta_schema['url'] = $meta_og['og:url'];
			}

			if ( ! empty( $this->p->options['add_meta_itemprop_image'] ) ) {
				if ( ! empty( $meta_og['og:image'] ) ) {
					if ( is_array( $meta_og['og:image'] ) )
						foreach ( $meta_og['og:image'] as $image )
							$meta_schema['image'][] = $image['og:image'];
					else $meta_schema['image'] = $meta_og['og:image'];
				}
			}

			return apply_filters( $this->p->cf['lca'].'_meta_schema', $meta_schema, $use_post, $obj );
		}

		public function get_json_array( $post_id = false, $author_id = false, $size_name = 'thumbnail' ) {
			$json_array = array();

			if ( ! empty( $this->p->options['schema_website_json'] ) &&
				( $json_script = $this->get_website_json_script( $post_id ) ) !== false )
					$json_array[] = $json_script;

			if ( ! empty( $this->p->options['schema_author_json'] ) && ! empty( $author_id ) &&
				( $json_script = $this->p->mods['util']['user']->get_person_json_script( $author_id, $size_name ) ) !== false )
					$json_array[] = $json_script;

			if ( ! empty( $this->p->options['schema_publisher_json'] ) &&
				( $json_script = $this->get_organization_json_script( $size_name ) ) !== false )
					$json_array[] = $json_script;

			return $json_array;	// must be an array
		}

		public function get_website_json_script( $post_id = false ) {
			$home_url = get_bloginfo( 'url' );	// equivalent to get_home_url()
			// pass options array to allow fallback if locale option does not exist
			$site_name = $this->p->og->get_site_name( $post_id );
			$json_script = '<script type="application/ld+json">{
	"@context":"http://schema.org",
	"@type":"WebSite",
	"url":"'.$home_url.'",
	"name":"'.$site_name.'",
	"potentialAction":{
		"@type":"SearchAction",
		"target":"'.$home_url.'?s={search_term}",
		"query-input":"required name=search_term"
	}
}</script>';
			return $json_script;
		}

		public function get_organization_json_script( $size_name = 'thumbnail') {
			$home_url = get_bloginfo( 'url' );	// equivalent to get_home_url()
			$logo_url = $this->p->options['schema_logo_url'];
			$og_image = $this->p->media->get_default_image( 1, $this->p->cf['lca'].'-opengraph', false );
			if ( count( $og_image ) > 0 ) {
				$image = reset( $og_image );
				$image_url = $image['og:image'];
			} else $image_url = '';

			$json_script = '<script type="application/ld+json">{
	"@context":"http://schema.org",
	"@type":"Organization",
	"url":"'.$home_url.'",
	"logo":"'.$logo_url.'",
	"image":"'.$image_url.'",
	"sameAs":['."\n";
			foreach ( array(
				'seo_publisher_url',
				'fb_publisher_url',
				'linkedin_publisher_url',
				'tc_site',
			) as $key ) {
				$sameAs = isset( $this->p->options[$key] ) ?
					trim( $this->p->options[$key] ) : '';
				if ( empty( $sameAs ) )
					continue;

				if ( $key === 'tc_site' )
					$sameAs = 'https://twitter.com/'.preg_replace( '/^@/', '', $sameAs );

				if ( strpos( $sameAs, '://' ) !== false )
					$json_script .= "\t\t\"".$sameAs."\",\n";
			}
			return rtrim( $json_script, ",\n" )."\n\t]\n}</script>\n";
		}
	}
}

?>
