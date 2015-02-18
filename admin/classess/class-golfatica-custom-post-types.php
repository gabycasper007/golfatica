<?php
defined( 'ABSPATH' ) or exit;

if ( !class_exists('Golfatica_Custom_Post_Types') ) {
	
	/**
	* Create custom post types
	* @since 1.0
	*/
	class Golfatica_Custom_Post_Types {
		
		public $args;
		public $plural;
		public $single;
		public $post_type;
		
		public function __construct( $post_type = 'post', $args = array() ) {

			if ( ! isset( $args ) || ! isset( $post_type ) || ! is_admin() ) return;

			// Sanitize Post type
			$this->post_type = sanitize_key( $post_type );

			$this->_create_defaults( $args );

			// Register post type
			add_action( 'init' , array( $this, 'register_post_type' ) );

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
			add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_updated_messages' ), 10, 2 );

			$this->set_help_tab( $this->args );
			$this->set_taxonomies();


		} // End __construct ()

		/**
		 * Register new post type
		 * @return void
		 */
		public function register_post_type() {
			register_post_type( $this->post_type, $this->args->args );
		} // End register_post_type()

		/**
		 * Set up admin messages for post type
		 * @param  array $messages Default message
		 * @return array           Modified messages
		 */
		public function updated_messages( $messages = array() ) {
			global $post, $post_ID;
		    $messages[ $this->post_type ] = array(
			    0 => '',
			    1 => sprintf( __( '%1$s updated. %2$sView %3$s%4$s.' , TEXT_DOMAIN ), $this->single, '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', $this->single, '</a>' ),
			    2 => __( 'Custom field updated.' , TEXT_DOMAIN ),
			    3 => __( 'Custom field deleted.' , TEXT_DOMAIN ),
			    4 => sprintf( __( '%1$s updated.' , TEXT_DOMAIN ), $this->single ),
			    5 => isset( $_GET['revision'] ) ? sprintf( __( '%1$s restored to revision from %2$s.' , TEXT_DOMAIN ), $this->single, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			    6 => sprintf( __( '%1$s published. %2$sView %3$s%4$s.' , TEXT_DOMAIN ), $this->single, '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', $this->single, '</a>' ),
			    7 => sprintf( __( '%1$s saved.' , TEXT_DOMAIN ), $this->single ),
			    8 => sprintf( __( '%1$s submitted. %2$sPreview post%3$s%4$s.' , TEXT_DOMAIN ), $this->single, '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', $this->single, '</a>' ),
			    9 => sprintf( __( '%1$s scheduled for: %2$s. %3$sPreview %4$s%5$s.' , TEXT_DOMAIN ), $this->single, '<strong>' . date_i18n( __( 'M j, Y @ G:i' , TEXT_DOMAIN ), strtotime( $post->post_date ) ) . '</strong>', '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">', $this->single, '</a>' ),
			    10 => sprintf( __( '%1$s draft updated. %2$sPreview %3$s%4$s.' , TEXT_DOMAIN ), $this->single, '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', $this->single, '</a>' ),
			  );
		    return $messages;
		} // End updated_messages()

		/**
		 * Set up bulk admin messages for post type
		 * @param  array  $bulk_messages Default bulk messages
		 * @param  array  $bulk_counts   Counts of selected posts in each status
		 * @return array                 Modified messages
		 */
		public function bulk_updated_messages ( $bulk_messages = array(), $bulk_counts = array() ) {
			$bulk_messages[ $this->post_type ] = array(
		        'updated'   => sprintf( _n( '%1$s %2$s updated.', '%1$s %3$s updated.', $bulk_counts['updated'], TEXT_DOMAIN ), $bulk_counts['updated'], $this->single, $this->plural ),
		        'locked'    => sprintf( _n( '%1$s %2$s not updated, somebody is editing it.', '%1$s %3$s not updated, somebody is editing them.', $bulk_counts['locked'], TEXT_DOMAIN ), $bulk_counts['locked'], $this->single, $this->plural ),
		        'deleted'   => sprintf( _n( '%1$s %2$s permanently deleted.', '%1$s %3$s permanently deleted.', $bulk_counts['deleted'], TEXT_DOMAIN ), $bulk_counts['deleted'], $this->single, $this->plural ),
		        'trashed'   => sprintf( _n( '%1$s %2$s moved to the Trash.', '%1$s %3$s moved to the Trash.', $bulk_counts['trashed'], TEXT_DOMAIN ), $bulk_counts['trashed'], $this->single, $this->plural ),
		        'untrashed' => sprintf( _n( '%1$s %2$s restored from the Trash.', '%1$s %3$s restored from the Trash.', $bulk_counts['untrashed'], TEXT_DOMAIN ), $bulk_counts['untrashed'], $this->single, $this->plural ),
		    );
		    return $bulk_messages;
		} // End bulk_updated_messages()

		/**
		 * Create default values
		 * @param  array $args [Arguments]
		 * @return void
		 */
		protected function _create_defaults( $args ) {
			$single = ucfirst($this->post_type);
			$plural = $single . 's';

			$defaults = array(
				'args' => array(
					'labels' => array(
						'name' => $plural,
						'singular_name' => $single,
						'name_admin_bar' => $single,
						'add_new' => _x( 'Add New', $single , TEXT_DOMAIN ),
						'add_new_item' => sprintf( __( 'Add New %s' , TEXT_DOMAIN ), $single ),
						'edit_item' => sprintf( __( 'Edit %s' , TEXT_DOMAIN ), $single ),
						'new_item' => sprintf( __( 'New %s' , TEXT_DOMAIN ), $single ),
						'all_items' => sprintf( __( 'All %s' , TEXT_DOMAIN ), $plural ),
						'view_item' => sprintf( __( 'View %s' , TEXT_DOMAIN ), $single ),
						'search_items' => sprintf( __( 'Search %s' , TEXT_DOMAIN ), $plural ),
						'not_found' =>  sprintf( __( 'No %s Found' , TEXT_DOMAIN ), $plural ),
						'not_found_in_trash' => sprintf( __( 'No %s Found In Trash' , TEXT_DOMAIN ), $plural ),
						'parent_item_colon' => sprintf( __( 'Parent %s' ), $single ),
						'menu_name' => $plural,
					),
					'description' => sprintf( __('About %s', TEXT_DOMAIN), $plural ),
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
				'help_tabs' => array(),
				'help_sidebar' => '',
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = json_decode( json_encode( $args ) );

			$this->single = $this->args->args->labels->singular_name;
			$this->plural = $this->args->args->labels->name;
		}

		/**
		 * Set The Help Tab
		 * @param [type] $args [description]
		 */
		public function set_help_tab( $args ) {
			if ( class_exists('Golfatica_Custom_Help_Tab') ) {
				new Golfatica_Custom_Help_Tab( 'post', $this->post_type , $args );
			}
		}

		/**
		 * Get Requested Taxonomies
		 * @return void
		 * @since 1.0
		 */
		protected function set_taxonomies() {
			if ( class_exists('Golfatica_Custom_Taxonomies') && isset( $this->args->taxonomies )) {
				foreach ($this->args->taxonomies as $taxonomy => $custom_taxonomy) {

					// Get Default Post Type if none Assigned
					if ( ! isset( $custom_taxonomy->post_type ) ) {
						if ( empty( $custom_taxonomy ) ) {
							$custom_taxonomy['post_type'] = $this->post_type;
						}
						else {
							$custom_taxonomy->post_type = $this->post_type;
						}
					}
					new Golfatica_Custom_Taxonomies( $taxonomy, $custom_taxonomy );
				}
			}
		} // End set_taxonomies

	} // End class Golfatica_Custom_Post_Types

} // End if (!class_exists('Golfatica_Custom_Post_Types')) 