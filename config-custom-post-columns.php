<?php 
defined( 'ABSPATH' ) or exit;

/**
 * Require the class for creating custom post columns
 */
require get_template_directory() . '/admin/classess/class-golfatica-custom-post-columns.php';

if ( class_exists('Golfatica_Custom_Post_Columns') ) {

	function glf_get_featured_image_into_column() {
		global $post;
		echo get_the_post_thumbnail($post->ID, array(70,70));
	}

	function glf_get_page_id_into_column() {
		global $post;
		echo $post->ID;
	}

	function glf_get_page_order_into_column() {
		global $post;
		echo $post->menu_order;
	}

	$args = array(
		'post_type' => 'page',
		'posts_per_page' => 20,
		'columns' => array(
			'glf_thumbnail_column' => array(
				'title' => __('Featured image', 'golfatica' ),
				'content' => 'glf_get_featured_image_into_column', // Callable function or a string
				'orderby' => 'meta_value',
				'meta_key' => '_thumbnail_id',
				'location' => 1,
			),
			'glf_id_column' => array(
				'title' => __('ID', 'golfatica'),
				'content' => 'glf_get_page_id_into_column', // Callable function or a string
				'orderby' => 'ID',
				'location' => 3
			),
			'glf_order_column' => array(
				'title' => __('Page Order', 'golfatica'),
				'content' => 'glf_get_page_order_into_column', // Callable function or a string
				'orderby' => 'menu_order',
				'location' => 4
			),
		),
		'status_links' => array(
			'<a href="http://www.quart.ro" target="_blank">See documentation</a>'
		)
	);
	new Golfatica_Custom_Post_Columns($args); 

} // End if ( class_exists('Golfatica_Custom_Post_Columns') )