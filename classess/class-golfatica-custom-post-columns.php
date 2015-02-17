<?php 

defined( 'ABSPATH' ) or exit;

if ( !class_exists('Golfatica_Custom_Post_Columns') ) {
	/**
	* Customize Post Columns
	* @since 1.0
	// TODO: Function for re-arranging the columns
	// TODO: Function for removing columns
	// TODO: Order by post__in, meta_value_num, rand
	*/
	class Golfatica_Custom_Post_Columns {

		public $taxonomies;
		public $args;
		
		/**
		 * @param integer $posts_per_page [How many posts per Page]
		 * @param string  $post_type      [Post Type]
		 * @since 1.0
		 */
		public function __construct($args = array(), $taxonomies = array()) {

			if ( ! is_admin() )
				return;

			$defaults = array(
				'post_type' => 'post',
				'posts_per_page' => 20,
				'columns' => array()
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = (object) $args;

			// Post Types
			add_filter( "edit_{$this->args->post_type}_per_page", array( $this, 'edit_posts_per_post_type_page' ) ); // Change Number of Posts per Page
			add_filter( "manage_{$this->args->post_type}_posts_columns", array( $this, 'manage_post_type_posts_columns' ), 10 );
			add_action( "manage_{$this->args->post_type}_posts_custom_column", array( $this, 'manage_post_type_posts_custom_column' ), 10, 2);
			add_action( "manage_edit-{$this->args->post_type}_sortable_columns", array( $this, 'manage_post_type_sortable_columns' ) );
			
			// Run customization only in the edit.php page of the admin
			// @see http://justintadlock.com/archives/2011/06/27/custom-columns-for-custom-post-types
			add_action( 'load-edit.php', array( $this, 'add_filter_request' ) );

			// Add Post status_links 
			add_filter( "views_edit-{$this->args->post_type}", array( $this, 'add_column_custom_link' ) );
			
			// Taxonomies
			if (!is_array($taxonomies)) {
				$taxonomies = array($taxonomies);
			}
			$this->taxonomies = $taxonomies;

			foreach ($this->taxonomies as $taxonomy) {
				add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'manage_edit_taxonomy_columns' ), 10, 1 );
				add_filter( "manage_{$taxonomy}_custom_column", array( $this, 'manage_taxonomies_for_post_type_columns' ), 10, 3);
				add_filter( "manage_edit-{$taxonomy}_sortable_columns", array( $this, 'manage_edit_sortable_columns_for_post_type_columns' ) );
			}

		} // End __construct()
		 
		function add_column_custom_link( $status_links ) {

			if ( isset( $this->args->status_links ) && $this->args->status_links ) {
				foreach ($this->args->status_links as $key => $link) {
		    		$status_links[ $key ] = $link;
				}
			}
		    return $status_links;
		}

		public function add_filter_request() {
			add_filter( 'request',  array( $this, 'handle_post_type_column_sorting') ); 
		}

		/**
		 * How Many Posts do you want per page?
		 * @return integer [number of posts]
		 * @since 1.0
		 */
		public function edit_posts_per_post_type_page() {
			return $this->args->posts_per_page;
		} // End edit_posts_per_post_type_page()

		/**
		 * Used to insert a column at a specific location 
		 * @param  array $array  [description]
		 * @param  array $values [description]
		 * @param  integer $offset [description]
		 * @return array         [description]
		 */
		protected function array_insert( $array, $values, $offset ) {
		    return array_slice( $array, 0, $offset, true ) + $values + array_slice( $array, $offset, NULL, true );  
		}

		/**
		 * Add Post Type Costom Columns Titles
		 * @param  array $posts_columns [Get default post columns and append to them]
		 * @return array [New columns]
		 * @since 1.0
		 */
		public function manage_post_type_posts_columns( $posts_columns ) {
		    foreach ( $this->args->columns as $key => $column ) {
		    	if ( isset( $column['title'] ) ) {

		    		if ( isset( $column['location'] ) ) {
		    			$posts_columns = $this->array_insert( $posts_columns, array( $key => $column['title'] ), $column['location'] );
		    		}
		    		else {
		    			$posts_columns[ $key ] = $column['title'];
		    		}
		    	}
		    }

		    return $posts_columns;
		} // End manage_post_type_posts_columns()

		public function remove_post_type_posts_columns( $old_columns = array() ) {
			foreach ( $old_columns as $column ) {
				unset( $posts_columns[ $column ] );
			}
		}

		/**
		 * Add Post Type Costom Column Contents
		 * @param  [type] $column_name [description]
		 * @param  [type] $post_ID     [description]
		 * @return void
		 * @since 1.0
		 */
		public function manage_post_type_posts_custom_column( $column_name, $post_ID ) {
		    foreach ( $this->args->columns as $key => $column ) {
		    	if ( isset( $column['content'] ) && $column_name == $key ) {
		    		if (is_callable( $column['content'] )) {
		    			$column['content']();
		    		}
			    	else {
			    		echo $column['content'];
			    	}
		    	}
		    }
		} // End manage_post_type_posts_custom_column()

		/**
		 * Make Post Type Costom Columns Sortable
		 * @param  [type] $sortable_columns [description]
		 * @return array [Sortable Columns]
		 * @since 1.0
		 */
		public function manage_post_type_sortable_columns( $sortable_columns ) {
			foreach ( $this->args->columns as $key => $column ) {
				if ( isset( $column['orderby'] ) ) {
					$sortable_columns[$key] = $key;
				}
			}
		    return $sortable_columns;
		} // End manage_post_type_sortable_columns

		public function handle_post_type_column_sorting( $vars ) {
			if ( 
				isset( $vars['orderby'] ) && 
				isset( $vars['post_type'] ) && $vars['post_type'] == $this->args->post_type // if we're viewing the right post_type
			) {
				foreach ( $this->args->columns as $key => $column ) {

					// if we are on the right orderby
					if ( isset( $column['orderby'] ) && $key == $vars['orderby'] ) {

						// Meta Ordering
						if ( isset( $column['meta_key'] ) ) {
							$vars = array_merge( $vars, array(
							  'orderby'  => $column['orderby'],
							  'meta_key'  => $column['meta_key']
							));
						}
						// Basic Ordering: none/ID/author/title/name/date/modified/parent/rand/comment_count/menu_order
						else {
							$vars = array_merge( $vars, array(
							  'orderby'  => $column['orderby']
							));
						}
					}
				}
			}
			return $vars;
		}

		/**
		 * Add Taxonomy Custom Column Title
		 * @param  array $posts_columns [Get default post columns and append to them]
		 * @return array [New columns] 
		 * @since 1.0
		 */
		public function manage_edit_taxonomy_columns($columns) {

		    /* $columns = array(
		        'cb' => '<input type="checkbox" />',
		        'name' => __('Name'),
		 		'description' => __('Description'),
		        'slug' => __('Slug'),
		        'posts' => __('Posts')
		        'new_label' => __('New label'),
		    );*/

			$columns['new_label'] = __('New2 label');
		    return $columns;
		} // End manage_edit_taxonomy_columns()
		
		public function manage_taxonomies_for_post_type_columns($out, $column_name, $term_id) {
			return 'works';
		} // End manage_taxonomies_for_post_type_columns()


		public function manage_edit_sortable_columns_for_post_type_columns($sortable_columns) {
			$sortable_columns['new_label'] = 'new_label';
		    return $sortable_columns;
		} // End manage_edit_sortable_columns_for_post_type_columns

	} // End class Golfatica_Custom_Post_Columns
} // End if ( !class_exists('Golfatica_Custom_Post_Columns') )