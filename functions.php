<?php
/**
 * Golfatica functions and definitions
 *
 * @package Golfatica
 * @since  1.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Language text domain for POT files and translations
 * @since 1.0
 */
define('TEXT_DOMAIN', 'golfatica');

if (!class_exists('Golfatica_Functions')) {
	/**
	* Golfatica functions
	* @since  1.0
	*/
	class Golfatica_Functions {

		/**
		 * $text_domain used for translation
		 * @since 1.0
		 */
		public $text_domain;
		
		public function __construct($text_domain = '') {

			// Include files BEFORE theme initialization
			$this->golfatica_before_includes();

			// Set up theme's text domain
			$this->text_domain = $text_domain;

			// Set up theme
			$this->theme_setup();

			// Add actions
			$this->add_actions();

			// Include files AFTER theme initialization
			$this->golfatica_after_includes();
		} // End __construct()

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 * @since  1.0
		 */
		public function theme_setup() {
			/**
			 * Set the content width based on the theme's design and stylesheet.
			 */
			if ( ! isset( $content_width ) ) {
				$content_width = 640; /* pixels */
			}

			/**
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Golfatica, use a find and replace
			 * to change $this->text_domain to the name of your theme in all the template files
			 * @since 1.0
			 */
			load_theme_textdomain( $this->text_domain, get_template_directory() . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/**
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 * @since 1.0
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 * @since 1.0
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * This theme uses wp_nav_menu() in one location.
			 * @since 1.0
			 */
			register_nav_menus( array(
				'primary' => __( 'Primary Menu', $this->text_domain ),
			) );

			/**
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 * @since 1.0
			 */
			add_theme_support( 'html5', array(
				'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
			) );

			/**
			 * Enable support for Post Formats.
			 * @link http://codex.wordpress.org/Post_Formats
			 * @since 1.0
			 */
			add_theme_support( 'post-formats', array(
				'aside', 'image', 'video', 'quote', 'link', 'gallery'
			) );

			/**
			 * Set up the WordPress core custom background feature.
			 * @since 1.0
			 */
			add_theme_support( 'custom-background', apply_filters( 'golfatica_custom_background_args', array(
				'default-color' => 'ffffff',
				'default-image' => '',
			) ) );
		} // End theme_setup()

		/**
		 * Add actions
		 * @since 1.0
		 */
		public function add_actions() {
			add_action( 'after_setup_theme',  array($this, 'theme_setup' ));
			add_action( 'widgets_init', array($this, 'golfatica_widgets_init' ));
			add_action( 'wp_enqueue_scripts', array($this, 'golfatica_scripts' ));
		} // End add_actions()

		/**
		 * Register widget area.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
		 * @since 1.0
		 */
		public function golfatica_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Sidebar', $this->text_domain ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title">',
				'after_title'   => '</h1>',
			) );
		} // End golfatica_widgets_init()

		/**
		 * Enqueue scripts and styles.
		 * @since 1.0
		 */
		public function golfatica_scripts() {
			// Bootstrap Css
			wp_enqueue_style('bootstrapwp', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css', false ,'3.3.2', 'all' );

			// Main Theme CSS
			wp_enqueue_style( 'golfatica-style', get_stylesheet_uri() );

			// Deregister Wordpress jQuery
			wp_deregister_script('jquery');

			// Load CDN jQuery
			wp_enqueue_script('jquery', '//code.jquery.com/jquery-1.11.2.min.js', '1.11.2', true );

			// Load CDN jQuery Migrate
			wp_enqueue_script('jquery-migrate', '//code.jquery.com/jquery-migrate-1.2.1.min.js', array('jquery'), '1.2.1', true );

			// Load Bootstrap Js
			wp_enqueue_script('bootstrapjs', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js', array('jquery'),'3.3.2', true );

			wp_enqueue_script( 'golfatica-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
			wp_enqueue_script( 'golfatica-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		} // End golfatica_scripts()

		/**
		 * Include files BEFORE theme initiolization
		 * @since 1.0
		 */
		public function golfatica_before_includes() {
			$golfatica_before_includes = array(
				'admin/admin-init.php', 	      // Get Redux Framework
			);

			foreach ($golfatica_before_includes as $file) {
				if (!$filepath = locate_template($file)) {
				trigger_error(sprintf(__('Error locating %s for inclusion', 'roots'), $file), E_USER_ERROR);
				}

				require_once $filepath;
			}
			unset($file, $filepath);
		} // End golfatica_before_includes()

		/**
		 * Include files AFTER theme initialization
		 * @since 1.0
		 */
		public function golfatica_after_includes() {
			$golfatica_after_includes = array(
				'inc/template-tags.php', 	      	     // Custom template tags for this theme.
				'inc/extras.php',            	  	     // Custom functions that act independently of the theme templates.
				'inc/customizer.php',         	  	     // Customizer additions.
				'inc/jetpack.php',         		  	     // Load Jetpack compatibility file.
				'admin/config-metaboxes.php',            // Load Metaboxes
				'admin/classess/class-golfatica-custom-help-tab.php',    // Load Custom Help Tabs
				'admin/config-custom-taxonomies.php',    // Load Custom Taxonomies
				'admin/config-custom-post-types.php',    // Load Custom Post Types
				'admin/config-custom-post-columns.php',  // Load Custom Post Columns
				'admin/config-custom-post-statuses.php', // Load Custom Post Statuses
				'admin/config-custom-dashboard.php', 	 // Load Custom Dashboard
			);
			// TODO: Combine two classes in one (custom post types classes/custom taxonomies classes)
			// TODO: Capabilities for custom post types and taxonomies
			// TODO: Customize tables class fara hard coding

			foreach ($golfatica_after_includes as $file) {
				if (!$filepath = locate_template($file)) {
					trigger_error(sprintf(__('Error locating %s for inclusion', 'roots'), $file), E_USER_ERROR);
				}

				require_once $filepath;
			}
			unset($file, $filepath);
		} // End golfatica_after_includes()

	} // End class Golfatica_Functions

	$Golfatica_Functions = new Golfatica_Functions(TEXT_DOMAIN);

} // End if (!class_exists('Golfatica_Functions'))

/* TODOs:
- Taxonomies Table
- Taxonomies Metaboxes
- Dashboard
	- Login screen - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-the-login-screen--wp-33035
	- Remove/Move/Add New metaboxes - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-the-dashboard--wp-33110
	- Rename/Remove/Reorder Admin Menu Items - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-custom-admin-menus--wp-33200
	- Add Help Boxes in a post (add_meta_box + edit_form_after_title) - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-help-text--wp-33281
	- Remove/Resize columns - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-listings-screens--wp-33296
	- Extra - https://code.tutsplus.com/articles/customizing-the-wordpress-admin-adding-styling--wp-33530
- Help Posts - https://wordpress.org/plugins/wp-help/
- Widgets
- Shortcodes
- Media
- Comments
- Users
	- Roles & Capabilities
- Woocommerce
- Documentation
- Ajax

- LESS/SCSS/Wordless WP - https://wordpress.org/plugins/wp-scss/
- Grunt/Bower
*/

/*

wp_is_mobile()

Best practices 
- https://code.tutsplus.com/series/design-patterns-in-wordpress--wp-33841
- https://code.tutsplus.com/articles/more-tips-for-best-practices-in-wordpress-development--cms-21013
- https://code.tutsplus.com/articles/tips-for-best-practices-in-wordpress-development--cms-20649

 */