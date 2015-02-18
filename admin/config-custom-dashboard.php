<?php 
defined( 'ABSPATH' ) or exit;

// If we're not in admin or login page return
if ( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {
	return;
}

/**
 * Require the class for creating custom post types
 */
require get_template_directory() . '/admin/classess/class-golfatica-custom-dashboard.php';

if (class_exists('Golfatica_Custom_Dashboard')) {
	$args = array(
		'login' => array(
			'css' => get_template_directory_uri() . '/css/login.css',
			// 'url' => get_template_directory_uri() . '/img/logo.jpg',
			'link' => 'http://www.quart.ro',
			'title' => 'Quart',
			// 'width' => '200px',
			// 'height' => '77px',
		),
		'admin_bar' => array(
			'front' => false, // Disable on front end
			'add' => array(
				array(
					'id' => 'quart_link',
					'title' => __( 'Quart'),
					'href' => __('http://www.quart.ro/'),
				),
				// array(
				// 	'id' => 'cwd_link',
				// 	'title' => __( 'CWD'),
				// 	'href' => __('http://www.creativewebdesign.ro/'),
				// ),
			),
			'remove' => array(
				'my-blogs',
				'about',
				'my-account-with-avatar',
				'appearance',
				'wp-logo',
			),
		),
		'footer_left_text' => '<em>Iti multumim ca folosesti platforma Quart! Pentru intrebari, contacteaza <a href="http://www.quart.ro/" target="_blank">Quart</a></em>.',
		'footer_right_text' => 'Golfatica 1.0',
		'widgets' => array(
			'remove' => array(
				'dashboard_incoming_links',
				'dashboard_plugins',
				'dashboard_primary',
				'dashboard_secondary',
				'dashboard_quick_press',
				'dashboard_recent_drafts',
				'dashboard_recent_comments',
				'dashboard_right_now',
				'dashboard_activity', //since 3.8
			),
		),
		'welcome' => false,
		'remove_menus' => array(
			'menus' => array(
				// 'index.php',				 //Dashboard
				// 'edit.php',					 //Posts
				// 'upload.php',				 //Media
				// 'edit.php?post_type=page',   //Pages
				'edit-comments.php',         //Comments
				// 'themes.php',                //Appearance
				// 'plugins.php',               //Plugins
				'users.php',                 //Users
				'tools.php',                 //Tools
				'options-general.php',       //Settings
			),
			'submenus' => array(
				'themes.php' => array(
					'themes.php',
					'theme-editor.php',
					'customize.php',
					'themes.php?page=install-required-plugins',
				),
			),
		),
		// 'updates' => false // Slows down admin loading!
	);
	$dash = new Golfatica_Custom_Dashboard( $args );
	// http://premium.wpmudev.org/blog/create-a-custom-wordpress-login-page/
	// 
	// 
} // End if (class_exists('Golfatica_Custom_Dashboard'))