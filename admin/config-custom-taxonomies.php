<?php 

defined( 'ABSPATH' ) or exit;

/**
 * Require the class for creating custom taxonomies
 */
require get_template_directory() . '/admin/classess/class-golfatica-custom-taxonomies.php';

if (class_exists('Golfatica_Custom_Taxonomies')) {

	if ( ! is_admin() )
		return;

	/**
	 * Create the taxonomies
	 * @since 1.0
	 */
	$golfatica_custom_taxonomies = array(
		'testtax' => array( 'post_type' => 'test' ), 
		'glftaxon' => array( 'post_type' => 'test' ), 
		'rabbittaxon' => array( 'post_type' => 'rabbit' ),
		'combinetax' => array(
			'post_type' => 'test',
			'args' => array(
		       	'labels' => array(
		       		'name' => _x('Plu Cmb', TEXT_DOMAIN),
		       		'singular_name' => _x('Sng Comb', TEXT_DOMAIN),
		       		'menu_name' => 'Plu Cmb',
		       		'all_items' => sprintf( __( 'All %s' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'edit_item' => sprintf( __( 'Edit %s' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'view_item' => sprintf( __( 'View %s' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'update_item' => sprintf( __( 'Update %s' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'add_new_item' => sprintf( __( 'Add New %s' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'new_item_name' => sprintf( __( 'New %s Name' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'parent_item' => sprintf( __( 'Parent %s' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'parent_item_colon' => sprintf( __( 'Parent %s:' , TEXT_DOMAIN ), 'Sng Comb' ),
		       		'search_items' =>  sprintf( __( 'Search %s' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'popular_items' =>  sprintf( __( 'Popular %s' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'separate_items_with_commas' =>  sprintf( __( 'Separate %s with commas' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'add_or_remove_items' =>  sprintf( __( 'Add or remove %s' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'choose_from_most_used' =>  sprintf( __( 'Choose from the most used %s' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       		'not_found' =>  sprintf( __( 'No %s found' , TEXT_DOMAIN ), 'Plu Cmb' ),
		       	),
		       	'label' => 'Plural Combs',
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud' => true,
				'meta_box_cb' => null,
				'show_admin_column' => true,
		       	'hierarchical' => true, 
				'update_count_callback' => '',
				'query_var' => 'combinetax',
				'rewrite' => true,
				'sort' => '',
			),
			'help_tabs' => array(
				array(
					'title'   => __( 'Help for the combinetax Tabs', TEXT_DOMAIN ),
					'id'      => 'newpostt_help_tabs', // UNIQUE id for the tab
					'content' => __( '<h3>Help tab for the combinetax </h3><p>Help content for the combinetax </p>', TEXT_DOMAIN ),
				),
				array(
					'title'   => __( 'Help for the combinetax Tabs 2', TEXT_DOMAIN ),
					'id'      => 'newpostt_help_tabs2', // UNIQUE id for the tab
					'content' => __( '<h3>Title Tabs for the combinetax 2</h3><p>Help2 content for the combinetax </p>', TEXT_DOMAIN )
				),
			),
			'help_sidebar' => __( 'combinetax Sidebar INIT', TEXT_DOMAIN )
		),
	);

	/**
	 * Loop through the taxonomies
	 * @since 1.0
	 */
	foreach ($golfatica_custom_taxonomies as $taxonomy => $custom_taxonomy) {
		new Golfatica_Custom_Taxonomies( $taxonomy, $custom_taxonomy );
	}

} // End if (class_exists('Golfatica_Custom_Taxonomies'))