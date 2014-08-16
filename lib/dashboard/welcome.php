<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoDashboardWelcome' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoDashboardWelcome extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;

			add_action( 'admin_init', array( &$this, 'welcome_redirect' ), 100 );
		}

		protected function add_meta_boxes() {
			$lca = $this->p->cf['lca'];
			$short_aop = $this->p->cf['plugin'][$lca]['short'].
				( $this->p->is_avail['aop'] ? ' Pro' : '' );

			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( 
				$this->pagehook.'_welcome', 
				$short_aop.' version '.$this->p->cf['plugin'][$lca]['version'], 
				array( &$this, 'show_metabox_welcome' ), 
				$this->pagehook, 
				'normal'
			);
		}

		public function show_metabox_welcome() {
			$metabox = 'welcome';
			$tabs = apply_filters( $this->p->cf['lca'].'_'.$metabox.'_tabs', array( 
				'whatsnew' => 'What\'s New',
				'setup' => 'Setup Guide' ) );
			$rows = array();
			foreach ( $tabs as $key => $title )
				$rows[$key] = array_merge( $this->get_rows( $metabox, $key ), 
					apply_filters( $this->p->cf['lca'].'_'.$metabox.'_'.$key.'_rows', array(), $this->form ) );
			$this->p->util->do_tabs( $metabox, $tabs, $rows );
		}

		protected function get_rows( $metabox, $key ) {
			$rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'welcome-whatsnew':
					$content = $this->p->msgs->get( 'info-review' ).
						$this->p->util->get_remote_content( 
							'',
							constant( $this->p->cf['uca'].'_PLUGINDIR' ).'whatsnew.html',
							$this->p->cf['*']['version']
						);
					$rows[] = '<td>'.$content.'</td>';
					break;
				case 'welcome-setup':
					$content = $this->p->msgs->get( 'info-review' ).
						$this->p->util->get_remote_content( 
							$this->p->cf['plugin'][$this->p->cf['lca']]['url']['setup'],
							constant( $this->p->cf['uca'].'_PLUGINDIR' ).'setup.html'
						);
					$rows[] = '<td>'.$content.'</td>';
					break;
			}
			return $rows;
		}

		public function welcome_redirect() {
			if ( ! get_transient( $this->p->cf['lca'].'_activation_redirect' ) &&
				! get_transient( $this->p->cf['lca'].'_update_redirect' ) )
					return;

			delete_transient( $this->p->cf['lca'].'_activation_redirect' );
			delete_transient( $this->p->cf['lca'].'_update_redirect' );

			if ( is_network_admin() || 
				isset( $_GET['activate-multi'] ) || 
				defined( 'IFRAME_REQUEST' ) )
					return;

			if ( ( isset( $_GET['action'] ) && $_GET['action'] === 'upgrade-plugin' ) && 
				( isset( $_GET['plugin'] ) && $_GET['plugin'] === WPSSO_PLUGINBASE ) )
					return;

			$url = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';
			$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

			$redir_page = '?page='.$this->p->cf['lca'].'-welcome';
			$redir_url = admin_url( $redir_page );

			if ( strpos( $url, $redir_page ) === false ) {
				wp_redirect( $redir_url );
				exit;
			}
		}
	}
}

?>
