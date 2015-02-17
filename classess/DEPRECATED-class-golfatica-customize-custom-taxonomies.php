<?php 

defined( 'ABSPATH' ) or exit;

/**
 * Require the class for customizing custom post types ( labels, args, help tabs, help sidebar )
 */
require get_template_directory() . '/admin/classess/class-golfatica-customize-custom-taxonomies.php';

$taxonomy = 'testtax';
$post_type = 'test';

// Uncomment this if you want to customize a taxonomy
// $args = array(
// 	'new_labels' => array('menu_name' => 'TEst ' . $taxonomy . ' Menu Name'),
// 	'new_args' =>  array(
// 		'hierarchical' => false
// 	),
// 	'new_help_tabs' => array(
// 		array(
// 			'title'   => __( 'Help for the ' .  $taxonomy . ' Tabs', TEXT_DOMAIN ),
// 			'id'      => $taxonomy . '_help_tabs', //UNIQUE id for the tab
// 			'content' => __( '<h3>Help tab for the ' .  $taxonomy . ' </h3><p>Help content for the ' .  $taxonomy . ' </p>', TEXT_DOMAIN ),
// 		),
// 		array(
// 			'title'   => __( 'Help for the ' .  $taxonomy . ' Tabs 2', TEXT_DOMAIN ),
// 			'id'      => $taxonomy . '_help_tabs2', //UNIQUE id for the tab
// 			'content' => __( '<h3>Title Tabs for the ' .  $taxonomy . ' 2</h3><p>Help2 content for the ' .  $taxonomy . ' </p>', TEXT_DOMAIN )
// 		),
// 	),
// 	'new_help_sidebar' => __( $taxonomy . ' Sidebar INIT', TEXT_DOMAIN )
// );
// new Golfatica_Customize_Custom_Taxonomies( $taxonomy, $args );

if (!class_exists('Golfatica_Customize_Custom_Taxonomies')) {

	/**
	 * Customize Custom Taxonomies
	 * @since 1.0
	 */
	class Golfatica_Customize_Custom_Taxonomies {

		public $taxonomy;
		public $new_labels;
		public $new_args;
		public $new_help_tabs;
		public $new_help_sidebar;

		/**
		 * Customize custom taxonomy
		 * @param string $taxonomy The taxonomy
		 * @param array $args       			[Array of arguments for registering a taxonomy.]
		 *       @type array $new_labels 	    [see http://codex.wordpress.org/register_taxonomy#Arguments]
		 *       @type array $new_args 		    [see http://codex.wordpress.org/register_taxonomy#Arguments]
		 *       @type array $new_help_tabs     [each array containts an array having the next arguments http://codex.wordpress.org/add_help_tab#Arguments]
		 *       @type string $new_help_sidebar [see http://codex.wordpress.org/Class_Reference/WP_Screen/set_help_sidebar]
		 * @since 1.0
		 */
		public function __construct( $taxonomy = '', $args = array()) {

			if ( ! $args || ! is_admin() ) return;

			$defaults = array(
		        'new_labels' => array(), 
		        'new_args' => array(), 
		        'new_help_tabs' => array(array()), 
		        'new_help_sidebar' => ''
			);
			$args = wp_parse_args( $args, $defaults );
			$args = (object) $args;

			$this->taxonomy = sanitize_key( $taxonomy );
			$this->new_labels = $args->new_labels;
			$this->new_args = $args->new_args;
			$this->new_help_tabs = $args->new_help_tabs;
			$this->new_help_sidebar = $args->new_help_sidebar;

			// Change labels for "{$this->taxonomy}" taxonomy
			add_filter( "{$this->taxonomy}_labels", array( $this, 'golfatica_change_custom_taxonomy_labels' ) );

			// Change arguments for "{$this->taxonomy}" taxonomy
			add_filter( "{$this->taxonomy}_register_args", array( $this, 'golfatica_change_custom_taxonomy_register_args' ) );

			// Change help tabs for "{$this->taxonomy}" taxonomy
			add_filter( "{$this->taxonomy}_help_tabs", array( $this, 'golfatica_change_custom_taxonomy_help_tabs' ) );

			// Change sidebar help for "{$this->taxonomy}" taxonomy
			add_filter( "{$this->taxonomy}_help_sidebar", array( $this, 'golfatica_change_custom_taxonomy_help_sidebar' ) );
		} // End __construct()

		/**
		 * @since 1.0
		 * @return array $labels [Modified labels]
		 */
		public function golfatica_change_custom_taxonomy_labels() {
			return $this->new_labels;
		} // End golfatica_change_custom_taxonomy_labels()

		/**
		 * @since 1.0
		 * @return array $args [Modified arguments]
		 */
		public function golfatica_change_custom_taxonomy_register_args() {
			return $this->new_args;
		} // End golfatica_change_custom_taxonomy_register_args()

		/**
		 * @since 1.0
		 * @return array $args [Modified tabs]
		 */
		public function golfatica_change_custom_taxonomy_help_tabs() {
			return $this->new_help_tabs;
		} // End golfatica_change_custom_taxonomy_help_tabs()

		/**
		 * @since 1.0
		 * @return string [Modified sidebar help text]
		 */
		public function golfatica_change_custom_taxonomy_help_sidebar() {
			return $this->new_help_sidebar; // __( 'Sidebar Text', TEXT_DOMAIN ); // Just for Demo, Visual Change
		}
	} // End class Golfatica_Customize_Custom_Taxonomies

} // End if (!class_exists('Golfatica_Customize_Custom_Taxonomies'))
