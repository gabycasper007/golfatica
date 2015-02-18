<?php 

defined( 'ABSPATH' ) or exit;

/**
 * Require the class for customizing custom post types ( labels, args, help tabs, help sidebar )
 */
// require get_template_directory() . '/admin/classess/class-golfatica-customize-custom-post-types.php';

// Uncomment this if you want to customize a post type
// $post_type = 'test';
// $args = array(
// 	'new_labels' => array('menu_name' => 'New ' . $post_type . ' Menu Name'),
// 	'new_args' =>  array(
// 		'supports' => 'title'
// 	),
// 	'new_help_tabs' => array(
// 		array(
// 			'title'   => __( 'Help for the ' .  $post_type . ' Tabs', TEXT_DOMAIN ),
// 			'id'      => $post_type . '_help_tabs', //UNIQUE id for the tab
// 			'content' => __( '<h3>Help tab for the ' .  $post_type . ' </h3><p>Help content for the ' .  $post_type . ' </p>', TEXT_DOMAIN ),
// 		),
// 		array(
// 			'title'   => __( 'Help for the ' .  $post_type . ' Tabs 2', TEXT_DOMAIN ),
// 			'id'      => $post_type . '_help_tabs2', //UNIQUE id for the tab
// 			'content' => __( '<h3>Title Tabs for the ' .  $post_type . ' 2</h3><p>Help2 content for the ' .  $post_type . ' </p>', TEXT_DOMAIN )
// 		),
// 	),
// 	'new_help_sidebar' => __( $post_type . ' Sidebar INIT', TEXT_DOMAIN )
// );
// new Golfatica_Customize_Custom_Post_Types( $post_type, $args );

if (!class_exists('Golfatica_Customize_Custom_Post_Types')) {

	/**
	 * Customize Custom Post Types
	* @since 1.0
	*/
	class Golfatica_Customize_Custom_Post_Types {

		public $post_type;
		public $new_labels;
		public $new_args;
		public $new_help_tabs;
		public $new_help_sidebar;

		/**
		 * Customize custom post type
		 * @param string $post_type The post type
		 * @param array $args       			[Array of arguments for registering a post type.]
		 *       @type array $new_labels 	    [see http://codex.wordpress.org/register_post_type#Arguments]
		 *       @type array $new_args 		    [see http://codex.wordpress.org/register_post_type#Arguments]
		 *       @type array $new_help_tabs     [each array containts an array having the next arguments http://codex.wordpress.org/add_help_tab#Arguments]
		 *       @type string $new_help_sidebar [see http://codex.wordpress.org/Class_Reference/WP_Screen/set_help_sidebar]
		 * @since 1.0
		 */
		public function __construct( $post_type = '', $args = array()) {

			if ( ! $args  || ! is_admin()) return;

			$defaults = array(
		        'new_labels' => array(), 
		        'new_args' => array(), 
		        'new_help_tabs' => array(array()), 
		        'new_help_sidebar' => ''
			);
			$args = wp_parse_args( $args, $defaults );
			$args = (object) $args;

			$this->post_type = sanitize_key( $post_type );
			$this->new_labels = $args->new_labels;
			$this->new_args = $args->new_args;
			$this->new_help_tabs = $args->new_help_tabs;
			$this->new_help_sidebar = $args->new_help_sidebar;

			// Change labels for "{$this->post_type}" post type
			add_filter( "{$this->post_type}_labels", array( $this, 'change_custom_post_labels' ) );

			// Change arguments for "{$this->post_type}" post type
			add_filter( "{$this->post_type}_register_args", array( $this, 'change_custom_post_register_args' ) );

			// Change help tabs for "{$this->post_type}" post type
			add_filter( "{$this->post_type}_help_tabs", array( $this, 'change_custom_help_tabs' ) );

			// Change sidebar help for "{$this->post_type}" post type
			add_filter( "{$this->post_type}_help_sidebar", array( $this, 'change_help_sidebar' ) );
		} // End __construct()

		/**
		 * @since 1.0
		 * @return array $labels [Modified labels]
		 */
		public function change_custom_post_labels() {
			return $this->new_labels;
		} // End change_custom_post_labels()

		/**
		 * @since 1.0
		 * @return array $args [Modified arguments]
		 */
		public function change_custom_post_register_args() {
			return $this->new_args;
		} // End change_custom_post_register_args()

		/**
		 * @since 1.0
		 * @return array $args [Modified tabs]
		 */
		public function change_custom_help_tabs() {
			return $this->new_help_tabs;
		} // End change_custom_help_tabs()

		/**
		 * @since 1.0
		 * @return string [Modified sidebar help text]
		 */
		public function change_help_sidebar() {
			return $this->new_help_sidebar;
		}
	} // End class Golfatica_Customize_Custom_Post_Types

} // End if (!class_exists('Golfatica_Customize_Custom_Post_Types'))
