<?php 

defined( 'ABSPATH' ) or exit;

if (!class_exists('Golfatica_Custom_Taxonomies')) {
	
	/**
	* Create custom taxonomies
	* @since 1.0
	*/
	class Golfatica_Custom_Taxonomies {

		public $taxonomy;
		public $plural;
		public $single;
		public $post_type;

		public function __construct( $taxonomy = '', $args = array() ) {

			if ( ! $taxonomy || ! isset( $args ) || ! is_admin()) return;

			// Sanitize Taxonomy Name
			$this->taxonomy = sanitize_key( $taxonomy );

			$this->_create_defaults( $args );

			// Register Taxonomy
			add_action( 'init', array( $this, 'register_taxonomy' ) );

			if ( class_exists('Golfatica_Custom_Help_Tab') ) {
				new Golfatica_Custom_Help_Tab( 'taxonomy', $this->taxonomy, $this->args );
			}
			// if ( $this->taxonomy != $screen->taxonomy )
			
		} // End __construct()

		public function register_taxonomy() {
	        register_taxonomy( $this->taxonomy, $this->post_type, $this->args->args );
 
	        // Add registered Taxonomy to a registered Post Types. - see http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
	        foreach ($this->post_type as $post_type) {
				register_taxonomy_for_object_type( $this->taxonomy, $post_type ); 
	        }

		} // End register_taxonomy()

		/**
		 * Create default values
		 * @param  array $args [Arguments]
		 * @return void
		 */
		protected function _create_defaults( $args ) {
			$single = ucfirst($this->taxonomy);
			$plural = $single . 's';

			$defaults = array(
				'post_type' => 'post',
				'args' => array(
					'labels' => array(
						'name' => _x($plural, TEXT_DOMAIN),
						'singular_name' => _x($single, TEXT_DOMAIN),
						'menu_name' => $plural,
						'all_items' => sprintf( __( 'All %s' , TEXT_DOMAIN ), $plural ),
						'edit_item' => sprintf( __( 'Edit %s' , TEXT_DOMAIN ), $single ),
						'view_item' => sprintf( __( 'View %s' , TEXT_DOMAIN ), $single ),
						'update_item' => sprintf( __( 'Update %s' , TEXT_DOMAIN ), $single ),
						'add_new_item' => sprintf( __( 'Add New %s' , TEXT_DOMAIN ), $single ),
						'new_item_name' => sprintf( __( 'New %s Name' , TEXT_DOMAIN ), $single ),
						'parent_item' => sprintf( __( 'Parent %s' , TEXT_DOMAIN ), $single ),
						'parent_item_colon' => sprintf( __( 'Parent %s:' , TEXT_DOMAIN ), $single ),
						'search_items' =>  sprintf( __( 'Search %s' , TEXT_DOMAIN ), $plural ),
						'popular_items' =>  sprintf( __( 'Popular %s' , TEXT_DOMAIN ), $plural ),
						'separate_items_with_commas' =>  sprintf( __( 'Separate %s with commas' , TEXT_DOMAIN ), $plural ),
						'add_or_remove_items' =>  sprintf( __( 'Add or remove %s' , TEXT_DOMAIN ), $plural ),
						'choose_from_most_used' =>  sprintf( __( 'Choose from the most used %s' , TEXT_DOMAIN ), $plural ),
						'not_found' =>  sprintf( __( 'No %s found' , TEXT_DOMAIN ), $plural ),
					),
					'label' => $plural,
					'public' => true,
					'show_ui' => true,
					'show_in_nav_menus' => true,
					'show_tagcloud' => true,
					'meta_box_cb' => null,
					'show_admin_column' => true,
			       	'hierarchical' => true, 
					'update_count_callback' => '',
					'query_var' => $single,
					'rewrite' => true,
					'sort' => '',
				),
				'help_tabs' => array(),
				'help_sidebar' => '',
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = json_decode( json_encode( $args ) );

			$this->single = $this->args->args->labels->singular_name;
			$this->plural = $this->args->args->labels->name;

			$post_type = $this->args->post_type;

			if ( ! is_array( $post_type ) ) {
				$post_type = array( $post_type );
			}
			$this->post_type = $post_type;
		} // End _create_defaults()

	} // End class Golfatica_Custom_Taxonomies

} // End if (!class_exists('Golfatica_Custom_Taxonomies'))
