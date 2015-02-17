<?php 
defined( 'ABSPATH' ) or exit;

/**
 * Require the class for creating custom post statuses
 */
require get_template_directory() . '/admin/classess/class-golfatica-custom-post-statuses.php';

if ( class_exists('Golfatica_Custom_Post_Statuses') ) {

	if ( ! is_admin() )
		return;

	// @see http://codex.wordpress.org/register_post_status#Arguments
	$args = array(
		'post_type' => 'page',
		'statuses' => array(
			'unread' => array(
				'label'                     => _x( 'Unread', 'post' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Unread' . ' <span class="count">(%s)</span>', 'Unreads' . ' <span class="count">(%s)</span>' ),
			),
			'customceva' => array(
			),
		)
	);
	new Golfatica_Custom_Post_Statuses($args);

} // End if ( class_exists('Golfatica_Custom_Post_Statuses') )
