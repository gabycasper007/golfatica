<?php 
defined( 'ABSPATH' ) or exit;

/**
* Customize dashboard
* @since 1.0
*/
if ( ! class_exists('Golfatica_Custom_Dashboard') ) {
	class Golfatica_Custom_Dashboard {

		protected $args;
		
		public function __construct( $args = array() ) {			
			$this->_create_defaults( $args );
			$this->_create_filters();
		} // End __construct()

		/**
		 * Remove update notifications and buttons
		 * @since 1.0
		 */
		public function remove_updates() {
			remove_action( 'load-update-core.php', 'wp_update_themes' );
			add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
			add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
		} // End remove_updates()

		public function remove_updates_submenu() {
		    remove_submenu_page( 'index.php', 'update-core.php' );
		}

		/**
		 * REMOVE ADMIN MENUS
		 * @since 1.0
		 */
		public function remove_menus() {

			foreach ( $this->args->remove_menus->menus as $m ) {
				remove_menu_page( $m );
			}

			foreach ( $this->args->remove_menus->submenus as $key => $subs ) {
				foreach ($subs as $sub) {
					remove_submenu_page( $key, $sub );
				}
			}
			// global $menu, $submenu;
			// foreach ($menu as $key => $m) {
			// 	$val = explode(' ', $m[0] );
			// 	if ( in_array( $val[0] != NULL ? $val[0] : "" , $this->args->remove_menus->menus ) ) {
			// 		unset( $menu[ $key ]);
			// 	}
			// }

			// foreach ( $submenu as $key => $subs ) {
			// 	foreach ( $subs as $key2 => $sub ) {
			// 		if ( in_array( $sub[0] != NULL ? $sub[0] : "" , $this->args->remove_menus->submenus ) ) {
			// 			unset( $submenu[ $key ][ $key2 ]);
			// 		}
			// 	}
			// }
			// remove_action('admin_menu', '_add_themes_utility_last', 101); // Remove the theme editor submenu within admin
		} // End remove_menus()

		/**
		 * Hide welcome screen
		 * @since 1.0
		 */
		public function hide_welcome_screen() {
		    $user_id = get_current_user_id();
		    if ( ! $this->args->welcome ) {
		        update_user_meta( $user_id, 'show_welcome_panel', 0 );
		    }
		    else {
		        update_user_meta( $user_id, 'show_welcome_panel', 1 );
		    }
		} // End hide_welcome_screen()

		/**
		 * Remove Dashboard Widgets
		 * @since 1.0
		 */
		public function remove_dashboard_widgets() {
			foreach ( $this->args->widgets->remove as $widget ) {
	        	remove_meta_box( $widget, 'dashboard', 'normal' );
			}
		}

		/**
		 * Change Addmin Bar Menu
		 * @since 1.0
		 */
		public function change_admin_bar_menu() {
			global $wp_admin_bar;
			
			if ( !is_super_admin() || !is_admin_bar_showing() ) 
				return;

			foreach ( $this->args->admin_bar->add as $menu ) {
				$wp_admin_bar->add_menu( $menu );
			}

			foreach ( $this->args->admin_bar->remove as $menu ) {
				$wp_admin_bar->remove_menu( $menu );
			}
		} // End change_admin_bar_menu()
		
		/**
		 * Change text in admin left footer
		 * @since 1.0
		 */
		public function set_footer_left_text( $text ) {
		    return $this->args->footer_left_text;
		}

		/**
		 * Change text in admin right footer
		 * @since 1.0
		 */
		public function set_footer_right_text( $text ) {
		    return $this->args->footer_right_text;
		}

		/**
		 * Change Logo Link
		 * @since 1.0
		 */
		public function set_login_link() {
		    return $this->args->login->link;
		}

		/**
		 * Change Logo Title
		 * @since 1.0
		 */
		public function set_login_title() {
		    return $this->args->login->title;
		}

		/**
		 * Set The Logo
		 * @since 1.0
		 */
		public function set_login_logo() { 
			if ( isset( $this->args->login->url ) && $this->args->login->url ) {
				$content = '<style type="text/css">';
				$content .=  '.login #login h1 a {';
				$content .=  	'background-image: url(' . $this->args->login->url . ');'; 

				if ( $this->args->login->width ) {
					$content .= '-webkit-background-size: ' . $this->args->login->width . ' auto;';
					$content .= 'background-size: ' . $this->args->login->width . ' auto;';
					$content .= 'width: ' . $this->args->login->width . ';';
				}
				if ( $this->args->login->height ) {
					$content .= 'height: ' . $this->args->login->height . ';';
				}
				$content .= ' }';
				$content .='</style>';
				echo $content;

			}
			if ( isset( $this->args->login->css ) && $this->args->login->css ) {
				wp_register_style( 'login-styles', $this->args->login->css , false, 1.0);
				wp_enqueue_style( 'login-styles' ); 

				
			}
		} // End set_login_logo()

		/**
		 * Create default values
		 * @param  array $args [Arguments]
		 * @since 1.0
		 */
		protected function _create_defaults( $args ) {
			$defaults = array(
				'login' => array(
					'css' => '',
					'url' => '',
					'link' => '',
					'title' => '',
					'width' => 0,
					'height' => 0,
				),
				'admin_bar' => array(
					'add' => array(),
					'remove' => array(),
					'front' => true,
				),
				'footer_left_text' => '',
				'footer_right_text' => '',
				'widgets' => array(
					'add' => array(),
					'remove' => array(),
				),
				'welcome' => true,
				'remove_menus' => array(
					'menus' => array(),
					'submenus' => array(),
				),
				'updates' => true
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = json_decode( json_encode( $args ) );
		} // End _create_defaults()

		/**
		 * Create Wordpress Filters and Actions
		 * @since 1.0
		 */
		protected function _create_filters() {

			// Login Logo
			if ( ( isset( $this->args->login->url ) && $this->args->login->url ) || 
				 ( isset( $this->args->login->css ) && $this->args->login->css ) ) { 
				add_action( 'login_enqueue_scripts', array( $this, 'set_login_logo' ) );
			}

			// Login Link
			if ( isset( $this->args->login->link ) && $this->args->login->link ) { 
				add_action( 'login_headerurl', array( $this, 'set_login_link' ) );
			}

			// Login Title
			if ( isset( $this->args->login->title  ) && $this->args->login->title ) { 
				add_action( 'login_headertitle', array( $this, 'set_login_title' ) );
			}

			// Footer Left Text
			if ( isset( $this->args->footer_left_text ) && $this->args->footer_left_text ) {
				add_filter( 'admin_footer_text', array( $this, 'set_footer_left_text' ) );
			}

			// Footer Right Text
			if ( isset( $this->args->footer_right_text ) && $this->args->footer_right_text ) {
				add_filter( 'update_footer', array( $this, 'set_footer_right_text' ), 11 );
			}

			// Change Admin Bar Menu
			if ( isset( $this->args->admin_bar ) && $this->args->admin_bar ) {
				add_action( 'admin_bar_menu', array( $this, 'change_admin_bar_menu' ), 25 );
			}

			// Remove Dashboard Widgets
			if ( isset( $this->args->widgets->remove ) && $this->args->widgets->remove ) {
				add_action( 'admin_init', array( $this, 'remove_dashboard_widgets' ) );
			}

			// Hide Welcome screen
			add_action( 'load-index.php', array( $this, 'hide_welcome_screen' ) );
			
			// Remove Menus and Submenus
			if ( ( isset( $this->args->remove_menus->menus ) && $this->args->remove_menus->menus ) || 
				 ( isset( $this->args->remove_menus->submenus ) && $this->args->remove_menus->submenus ) ){
				add_action( 'admin_init', array( $this, 'remove_menus' ) );
			}

			// Remove Updates
			if ( ! $this->args->updates ) {
				$this->remove_updates();
				add_action( 'admin_init', array( $this, 'remove_updates_submenu' ) );
			}

			// Hide Admin Bar on Front End
			if ( isset ( $this->args->admin_bar->front ) && ! $this->args->admin_bar->front ) {
				add_filter('show_admin_bar', '__return_false' );
			}
		} // End _create_filters()

	} // End class Golfatica_Custom_Dashboard
} // End if ( ! class_exists('Golfatica_Custom_Dashboard') )