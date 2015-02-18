<?php 

defined( 'ABSPATH' ) or exit;

if ( !class_exists('Golfatica_Custom_Help_Tab') ) {
	/**
	* Create Custom Help Tabs
	* @since 1.0
	*/
	class Golfatica_Custom_Help_Tab {
		
		public $object_type;
		public $args;

		function __construct( $object_type, $object, $args ) {

			if ( ! isset( $object_type ) || ! isset( $object ) || ! isset( $args ) || ! is_admin() ) return;

			// Create default values
			$this->_create_defaults( $args );

			// Sanitize Post type
			$this->object_type = sanitize_key( $object_type );
			$this->object = sanitize_key( $object );

			// Display help tabs
			add_action( 'admin_head', array( $this, 'custom_help_tab' ) );
		} // End __construct()

		/**
		 * Create custom help tab
		 * @since 1.0
		 * @return void
		 */
		public function custom_help_tab() {

			$screen = get_current_screen();

			// Return early if we're not on the needed post type.
			if ( ( $this->object_type == 'post' && $this->object != $screen->post_type ) || 
				 ( $this->object_type == 'taxonomy' && $this->object != $screen->taxonomy ) )  {
				return;
			}

			// Add the help tabs.
			foreach ( $this->args->help_tabs as $tab ) {
				$screen->add_help_tab( $tab );
			}

			// Add the help sidebar.
			$screen->set_help_sidebar( $this->args->help_sidebar );
		} // End custom_help_tab()

		/**
		 * Create default values
		 * @param  array $args [Arguments]
		 * @return void
		 */
		protected function _create_defaults( $args ) {
			$defaults = array(
				'help_tabs' => array(),
				'help_sidebar' => '',
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = json_decode( json_encode( $args ) );
		} // End _create_defaults()

	} // End class Golfatica_Custom_Help_Tab
} // End if ( !class_exists('Golfatica_Custom_Help_Tab') )