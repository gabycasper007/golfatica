<?php 
defined( 'ABSPATH' ) or exit;

/**
 * Require the class for creating custom post types
 */
require get_template_directory() . '/admin/classess/class-golfatica-custom-post-types.php';

if (class_exists('Golfatica_Custom_Post_Types')) {

	if ( ! is_admin() )
		return;

	/**
	 * Create the post types.
	 * @since 1.0
	 */
	$golfatica_custom_posts = array(
		'test' => array(), 
		'rabbit' => array(),
		'newpostt' => array(
			'args' => array(
				'labels' => array(
					'name' => 'Pl newp',
					'singular_name' => 'Sng newp',
					'name_admin_bar' => 'Sng newp',
					'add_new' => _x( 'Add New Sng newp', TEXT_DOMAIN ),
					'add_new_item' => sprintf( __( 'Add New %s' , TEXT_DOMAIN ), 'Sng newp' ),
					'edit_item' => sprintf( __( 'Edit %s' , TEXT_DOMAIN ), 'Sng newp' ),
					'new_item' => sprintf( __( 'New %s' , TEXT_DOMAIN ), 'Sng newp' ),
					'all_items' => sprintf( __( 'All %s' , TEXT_DOMAIN ), 'Pl newp' ),
					'view_item' => sprintf( __( 'View %s' , TEXT_DOMAIN ), 'Sng newp' ),
					'search_items' => sprintf( __( 'Search %s' , TEXT_DOMAIN ), 'Pl newp' ),
					'not_found' =>  sprintf( __( 'No %s Found' , TEXT_DOMAIN ), 'Pl newp' ),
					'not_found_in_trash' => sprintf( __( 'No %s Found In Trash' , TEXT_DOMAIN ), 'Pl newp' ),
					'parent_item_colon' => sprintf( __( 'Parent %s' ), 'Sng newp' ),
				),
				'description' => 'Description newppoossss',
				'public' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
				'query_var' => true,
				'can_export' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => true,
				'supports' => array( 'title', 'editor', 'excerpt', 'comments', 'thumbnail' ),
				'menu_position' => 5,
				'menu_icon' => 'dashicons-admin-post',
			),
			'taxonomies' => array(
				'generaltax' => array('post_type' => 'rabbit'),
				'posttypetax' => array(
					'post_type' => 'post',
					'args' => array(
				       	'labels' => array(
				       		'name' => _x('PLptx', TEXT_DOMAIN),
				       		'singular_name' => _x('SNptx', TEXT_DOMAIN),
				       		'menu_name' => 'PLptx',
				       		'all_items' => sprintf( __( 'All %s' , TEXT_DOMAIN ), 'PLptx' ),
				       		'edit_item' => sprintf( __( 'Edit %s' , TEXT_DOMAIN ), 'SNptx' ),
				       		'view_item' => sprintf( __( 'View %s' , TEXT_DOMAIN ), 'SNptx' ),
				       		'update_item' => sprintf( __( 'Update %s' , TEXT_DOMAIN ), 'SNptx' ),
				       		'add_new_item' => sprintf( __( 'Add New %s' , TEXT_DOMAIN ), 'SNptx' ),
				       		'new_item_name' => sprintf( __( 'New %s Name' , TEXT_DOMAIN ), 'SNptx' ),
				       		'parent_item' => sprintf( __( 'Parent %s' , TEXT_DOMAIN ), 'SNptx' ),
				       		'parent_item_colon' => sprintf( __( 'Parent %s:' , TEXT_DOMAIN ), 'SNptx' ),
				       		'search_items' =>  sprintf( __( 'Search %s' , TEXT_DOMAIN ), 'PLptx' ),
				       		'popular_items' =>  sprintf( __( 'Popular %s' , TEXT_DOMAIN ), 'PLptx' ),
				       		'separate_items_with_commas' =>  sprintf( __( 'Separate %s with commas' , TEXT_DOMAIN ), 'PLptx' ),
				       		'add_or_remove_items' =>  sprintf( __( 'Add or remove %s' , TEXT_DOMAIN ), 'PLptx' ),
				       		'choose_from_most_used' =>  sprintf( __( 'Choose from the most used %s' , TEXT_DOMAIN ), 'PLptx' ),
				       		'not_found' =>  sprintf( __( 'No %s found' , TEXT_DOMAIN ), 'PLptx' ),
				       	),
				       	'label' => 'PL Yppy',
						'public' => true,
						'show_ui' => true,
						'show_in_nav_menus' => true,
						'show_tagcloud' => true,
						'meta_box_cb' => null,
						'show_admin_column' => true,
				       	'hierarchical' => true, 
						'update_count_callback' => '',
						'query_var' => 'posttypetax',
						'rewrite' => true,
						'sort' => '',
					),
					'help_tabs' => array(
						array(
							'title'   => __( 'Help for the posttypetax Tabs', TEXT_DOMAIN ),
							'id'      => 'newpostt_help_tabs', // UNIQUE id for the tab
							'content' => __( '<h3>Help tab for the posttypetax </h3><p>Help content for the posttypetax </p>', TEXT_DOMAIN ),
						),
						array(
							'title'   => __( 'Help for the posttypetax Tabs 2', TEXT_DOMAIN ),
							'id'      => 'newpostt_help_tabs2', // UNIQUE id for the tab
							'content' => __( '<h3>Title Tabs for the posttypetax 2</h3><p>Help2 content for the posttypetax </p>', TEXT_DOMAIN )
						),
					),
					'help_sidebar' => __( 'posttypetax Sidebar INIT', TEXT_DOMAIN )
				),
			),
			'help_tabs' => array(
				array(
					'title'   => __( 'Help for the newposttt Tabs', TEXT_DOMAIN ),
					'id'      => 'newpostt_help_tabs', // UNIQUE id for the tab
					'content' => __( '<h3>Help tab for the newposttt </h3><p>Help content for the newposttt </p>', TEXT_DOMAIN ),
				),
				array(
					'title'   => __( 'Help for the newposttt Tabs 2', TEXT_DOMAIN ),
					'id'      => 'newpostt_help_tabs2', // UNIQUE id for the tab
					'content' => __( '<h3>Title Tabs for the newposttt 2</h3><p>Help2 content for the newposttt </p>', TEXT_DOMAIN )
				),
			),
			'help_sidebar' => __( 'newpostt Sidebar INIT', TEXT_DOMAIN ),
		),
	);

	/**
	 * Loop through the custom post types
	 * @since 1.0
	 */
	foreach ($golfatica_custom_posts as $post_type => $golfatica_custom_post) {
		new Golfatica_Custom_Post_Types( $post_type, $golfatica_custom_post );
	}

	/**
	 * TODO: flush_rewrite_rules(); because now you need to manually flush
	 */
	
} // End if (class_exists('Golfatica_Custom_Post_Types'))