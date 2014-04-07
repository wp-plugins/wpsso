<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomCache' ) ) {

	class SucomCache {

		private $p;

		public $base_dir = '';
		public $base_url = '/cache/';
		public $verify_certs = false;
		public $file_expire = 0;
		public $object_expire = 3600;
		public $timeout = 10;		// wait 10 seconds for a completed transaction
		public $connect_timeout = 5;	// wait 5 seconds for a connection
		public $ignore_time = 300;
		public $ignore_urls = array();	// offline some URLs for a period of time

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->verify_certs = empty( $this->p->options['plugin_verify_certs'] ) ? 0 : 1;
			$this->base_dir = trailingslashit( constant( $this->p->cf['uca'].'_CACHEDIR' ) );
			$this->base_url = trailingslashit( constant( $this->p->cf['uca'].'_CACHEURL' ) );

			$cache_salt = __CLASS__.'(ignore_urls)';
			$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
			$this->ignore_urls = get_transient( $cache_id );
			if ( $this->ignore_urls === false ) 
				$this->ignore_urls = array();
		}

		public function get( $url, $return = 'url', $cache_name = 'file', $expire_secs = false, $curl_userpwd = '' ) {

			$file_expire = $expire_secs === false ? $this->file_expire : $expire_secs;

			if ( $this->p->is_avail['curl'] !== true ) {
				$this->p->debug->log( 'exiting early: curl is not available' );
				return $return == 'url' ? $url : false;

			} elseif ( defined( $this->p->cf['uca'].'_CURL_DISABLE' ) && 
				constant( $this->p->cf['uca'].'_CURL_DISABLE' ) ) {
				$this->p->debug->log( 'exiting early: curl has been disabled' );
				return $return == 'url' ? $url : false;

			} elseif ( empty( $file_expire ) && $cache_name === 'file' )
				return $return === 'url' ? $url : false;

			$get_url = preg_replace( '/#.*$/', '', $url );	// remove the fragment
			$url_path = parse_url( $get_url, PHP_URL_PATH );

			$url_ext = pathinfo( $url_path, PATHINFO_EXTENSION );
			if ( ! empty( $url_ext ) ) 
				$url_ext = '.'.$url_ext;

			$url_frag = parse_url( $url, PHP_URL_FRAGMENT );
			if ( ! empty( $url_frag ) ) 
				$url_frag = '#'.$url_frag;

			$cache_salt = __CLASS__.'(get:'.$get_url.')';
			$cache_id = md5( $cache_salt );		// no lca prefix on filenames
			$cache_file = $this->base_dir.$cache_id.$url_ext;
			$cache_url = $this->base_url.$cache_id.$url_ext.$url_frag;
			$cache_data = false;

			// return immediately if the cache contains what we need
			switch ( $return ) {
				case 'raw':
					$cache_data = $this->get_cache_data( $cache_salt, $cache_name, $url_ext, $expire_secs );
					if ( ! empty( $cache_data ) ) {
						$this->p->debug->log( 'cache_data is present - returning '.strlen( $cache_data ).' chars' );
						return $cache_data;
					}
					break;
				case 'url':
					if ( file_exists( $cache_file ) && filemtime( $cache_file ) > time() - $file_expire ) {
						$this->p->debug->log( 'cache_file is current - returning cache url '.$cache_url );
						return $cache_url;
					}
					break;
				case 'filepath':
					if ( file_exists( $cache_file ) && filemtime( $cache_file ) > time() - $file_expire ) {
						$this->p->debug->log( 'cache_file is current - returning cache filepath '.$cache_file );
						return $cache_file;
					}
					break;
				default:
					return false;
					break;
			}

			// broken urls are ignored for $ignore_time seconds
			if ( ! empty( $this->ignore_urls ) && array_key_exists( $get_url, $this->ignore_urls ) ) {
				$time_remaining = $this->ignore_time - ( time() - $this->ignore_urls[$get_url] );
				if ( $time_remaining > 0 ) {
					$this->p->debug->log( 'ignoring url '.$get_url.' for another '.$time_remaining.' second(s). ' );
					return $return == 'url' ? $url : false;
				} else {
					unset( $this->ignore_urls[$get_url] );
					$cache_salt = __CLASS__.'(ignore_urls)';
					$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
					set_transient( $cache_id, $this->ignore_urls, $this->ignore_time );
				}
			}

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $get_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, $this->timeout );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout );

			if( ini_get('safe_mode') || ini_get('open_basedir') )
				$this->p->debug->log( 'PHP safe_mode or open_basedir defined, cannot use CURLOPT_FOLLOWLOCATION' );
			else {
				curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			}

			if ( defined( $this->p->cf['uca'].'_CURL_USERAGENT' ) ) 
				curl_setopt( $ch, CURLOPT_USERAGENT, 
					constant( $this->p->cf['uca'].'_CURL_USERAGENT' ) );
			
			if ( defined( $this->p->cf['uca'].'_CURL_PROXY' ) ) 
				curl_setopt( $ch, CURLOPT_PROXY, 
					constant( $this->p->cf['uca'].'_CURL_PROXY' ) );

			if ( defined( $this->p->cf['uca'].'_CURL_PROXYUSERPWD' ) ) 
				curl_setopt( $ch, CURLOPT_PROXYUSERPWD, 
					constant( $this->p->cf['uca'].'_CURL_PROXYUSERPWD' ) );

			if ( empty( $this->verify_certs) ) {
				curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
			} else {
				curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );

				if ( defined( $this->p->cf['uca'].'_CURL_CAINFO' ) ) 
					curl_setopt( $ch, CURLOPT_CAINFO, 
						constant( $this->p->cf['uca'].'_CURL_CAINFO' ) );
			}

			if ( ! empty( $curl_userpwd ) )
				curl_setopt( $ch, CURLOPT_USERPWD, $curl_userpwd );

			$this->p->debug->log( 'curl: fetching cache_data from '.$get_url );
			$cache_data = curl_exec( $ch );
			$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			curl_close( $ch );

			$this->p->debug->log( 'curl: http return code = '.$http_code );
			if ( $http_code == 200 ) {
				if ( empty( $cache_data ) )
					$this->p->debug->log( 'cache_data returned from "'.$get_url.'" is empty' );
				elseif ( $this->save_cache_data( $cache_salt, $cache_data, $cache_name, $url_ext, $expire_secs ) == true )
					$this->p->debug->log( 'cache_data sucessfully saved' );
				switch ( $return ) {
					case 'raw': return $cache_data; break;
					case 'url': return $cache_url; break;
					case 'filepath': return $cache_file; break;
					default: return false; break;
				}
			} else {
				if ( is_admin() )
					$this->p->notice->err( 'Error connecting to <a href="'.$get_url.'" target="_blank">'.$get_url.'</a> for caching. 
						Ignoring requests to cache this URL for '.$this->ignore_time.' second(s)', true );
				$this->p->debug->log( 'error connecting to URL '.$get_url.' for caching. ' );
				$this->p->debug->log( 'ignoring requests to cache this URL for '.$this->ignore_time.' second(s)' );
				$this->ignore_urls[$get_url] = time();
				$cache_salt = __CLASS__.'(ignore_urls)';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				set_transient( $cache_id, $this->ignore_urls, $this->ignore_time );
			}

			// return original url or empty data on failure
			return $return == 'url' ? $url : false;
		}

		private function get_cache_data( $cache_salt, $cache_name = 'file', $url_ext = '', $expire_secs = false ) {
			$cache_data = false;
			switch ( $cache_name ) {
				case 'wp_cache' :
					if ( $this->p->is_avail['cache']['object'] ) {
						$cache_type = 'object cache';
						$cache_id = $this->p->cf['lca']. '_'.md5( $cache_salt );	// add a prefix to the object cache id
						$this->p->debug->log( $cache_type.': cache_data '.$cache_name.' salt '.$cache_salt );
						$cache_data = wp_cache_get( $cache_id, __CLASS__ );
						if ( $cache_data !== false )
							$this->p->debug->log( $cache_type.': cache_data retrieved from '.$cache_name.' '.$cache_id );
					}
					break;
				case 'transient' :
					if ( $this->p->is_avail['cache']['transient'] ) {
						$cache_type = 'object cache';
						$cache_id = $this->p->cf['lca']. '_'.md5( $cache_salt );	// add a prefix to the object cache id
						$this->p->debug->log( $cache_type.': cache_data '.$cache_name.' salt '.$cache_salt );
						$cache_data = get_transient( $cache_id );
						if ( $cache_data !== false )
							$this->p->debug->log( $cache_type.': cache_data retrieved from '.$cache_name.' '.$cache_id );
					}
					break;
				case 'file' :
					$cache_type = 'file cache';
					$cache_id = md5( $cache_salt );		// no lca prefix on filenames
					$cache_file = $this->base_dir.$cache_id.$url_ext;
					$this->p->debug->log( $cache_type.': filename salt '.$cache_salt );
					$file_expire = $expire_secs === false ? $this->file_expire : $expire_secs;

					if ( ! file_exists( $cache_file ) )
						$this->p->debug->log( $cache_file.' does not exist yet.' );
					elseif ( ! is_readable( $cache_file ) )
						$this->p->notice->err( $cache_file.' is not readable.', true );
					elseif ( filemtime( $cache_file ) < time() - $file_expire )
						$this->p->debug->log( $cache_file.' is expired (file expiration = '.$file_expire.').' );
					elseif ( ! $fh = @fopen( $cache_file, 'rb' ) )
						$this->p->notice->err( 'Failed to open file '.$cache_file.' for reading.', true );
					else {
						$cache_data = fread( $fh, filesize( $cache_file ) );
						fclose( $fh );
						if ( ! empty( $cache_data ) )
							$this->p->debug->log( $cache_type.': cache_data retrieved from "'.$cache_file.'"' );
					}
					break;
				default :
					$this->p->debug->log( 'unknown cache name "'.$cache_name.'"' );
					break;
			}
			return $cache_data;	// return data or empty string
		}

		private function save_cache_data( $cache_salt, &$cache_data = '', $cache_name = 'file', $url_ext = '', $expire_secs = false ) {
			$ret_status = false;
			if ( empty( $cache_data ) ) 
				return $ret_status;

			switch ( $cache_name ) {
				case 'wp_cache' :
					if ( $this->p->is_avail['cache']['object'] ) {
						$cache_type = 'object cache';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );	// add a prefix to the object cache id
						$this->p->debug->log( $cache_type.': cache_data '.$cache_name.' salt '.$cache_salt );
						$object_expire = $expire_secs === false ? $this->object_expire : $expire_secs;
						wp_cache_set( $cache_id, $cache_data, __CLASS__, $object_expire );
						$this->p->debug->log( $cache_type.': cache_data saved to '.$cache_name.' '.$cache_id.' ('.$object_expire.' seconds)' );
						$ret_status = true;	// success
					}
					break;
				case 'transient' :
					if ( $this->p->is_avail['cache']['transient'] ) {
						$cache_type = 'object cache';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );	// add a prefix to the object cache id
						$this->p->debug->log( $cache_type.': cache_data '.$cache_name.' salt '.$cache_salt );
						$object_expire = $expire_secs === false ? $this->object_expire : $expire_secs;
						set_transient( $cache_id, $cache_data, $object_expire );
						$this->p->debug->log( $cache_type.': cache_data saved to '.$cache_name.' '.$cache_id.' ('.$object_expire.' seconds)' );
						$ret_status = true;	// success
					}
					break;
				case 'file' :
					$cache_type = 'file cache';
					$cache_id = md5( $cache_salt );
					$cache_file = $this->base_dir.$cache_id.$url_ext;
					$this->p->debug->log( $cache_type.': filename salt '.$cache_salt );
					if ( ! is_dir( $this->base_dir ) ) 
						mkdir( $this->base_dir );
					if ( ! is_writable( $this->base_dir ) )
						$this->p->notice->err( $this->base_dir.' is not writable.', true );
					else {
						if ( ! $fh = @fopen( $cache_file, 'wb' ) )
							$this->p->notice->err( 'Failed to open file '.$cache_file.' for writing.', true );
						else {
							if ( fwrite( $fh, $cache_data ) ) {
								$this->p->debug->log( $cache_type.': cache_data saved to "'.$cache_file.'"' );
								$ret_status = true;	// success
							}
							fclose( $fh );
						}
					}
					break;
				default :
					$this->p->debug->log( 'unknown cache name "'.$cache_name.'"' );
					break;
			}
			return $ret_status;	// return true or false
		}
	}
}

?>
