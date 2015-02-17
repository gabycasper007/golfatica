<?php

defined( 'ABSPATH' ) or exit;

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Golfatica_Redux_Framework_Config')) {

    class Golfatica_Redux_Framework_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public $text_domain;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            $this->text_domain = TEXT_DOMAIN;

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', $this->text_domain),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', $this->text_domain),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', $this->text_domain), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', $this->text_domain), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', $this->text_domain), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', $this->text_domain) . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', $this->text_domain), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Test Settings', $this->text_domain),
                'heading'   => __('Special title', $this->text_domain),
                'desc'      => __('Sample description', $this->text_domain),
                'fields'    => array(
                    array(
                        'id'        => 'opt-homelayout',
                        'type'      => 'image_select',
                        'compiler'  => true,
                        'title'     => __('Home Layout', $this->text_domain),
                        'subtitle'  => __('Select home content and sidebar alignment. Choose between 1 or 2 column layout.', $this->text_domain),
                        'hint'     => array(
                            //'title'     => '',
                            'content' => 'What kind of <b>homepage sidebar</b> do you want if any',
                        ),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            // '4' => array('alt' => '3 Column Middle','img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                            // '5' => array('alt' => '3 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                            // '6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'       => 'opt-sitelogo',
                        'type'     => 'media',
                        'title'    => __( 'Website Logo', $this->text_domain ),
                        'compiler' => 'true',
                        'mode'     => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        // 'desc'     => __( 'Basic media uploader with disabled URL input field.', $this->text_domain ),
                        'subtitle' => __( 'Upload your own logo', $this->text_domain ),
                    ),
                    array(
                        'id'       => 'opt-background',
                        'type'     => 'background',
                        'output'   => array( 'body' ),
                        'title'    => __( 'Body Background', $this->text_domain ),
                        'subtitle' => __( 'Body background with image, color, etc.', $this->text_domain ),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'       => 'opt-page-background',
                        'type'     => 'background',
                        'output'   => array( '#page' ),
                        'title'    => __( 'Page Background', $this->text_domain ),
                        'subtitle' => __( 'Page background with image, color, etc.', $this->text_domain ),
                        'desc' => 'Some description',
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'       => 'opt-post-1711-border',
                        'type'     => 'border',
                        'title'    => __( '#post-1711 Border', $this->text_domain ),
                        'subtitle' => __( 'Only color validation can be done on this field type', $this->text_domain ),
                        'output'   => array( '#post-1711' ),
                        // An array of CSS selectors to apply this font style to
                        'desc'     => __( 'This is the description field, again good for additional info.', $this->text_domain ),
                        'default'  => array(
                            'border-color'  => '#1e73be',
                            'border-style'  => 'solid',
                            'border-top'    => '3px',
                            'border-right'  => '3px',
                            'border-bottom' => '3px',
                            'border-left'   => '3px'
                        )
                    ),
                    array(
                        'id'       => 'opt-test-button-set',
                        'type'     => 'button_set',
                        'title'    => __( 'Test Button Set Option', $this->text_domain ),
                        'subtitle' => __( 'No validation can be done on this field type', $this->text_domain ),
                        'desc'     => __( 'This is the description field, again good for additional info.', $this->text_domain ),
                        //Must provide key => value pairs for radio options
                        'options'  => array(
                            '1' => 'Yes',
                            '2' => 'No',
                            '3' => 'Maybe'
                        ),
                        'default'  => '2'
                    ),
                    array(
                        'id'       => 'opt-test2-button-set',
                        'type'     => 'button_set',
                        'title'    => __( 'Test Button Set Option', $this->text_domain ),
                        'subtitle' => __( 'No validation can be done on this field type', $this->text_domain ),
                        'desc'     => __( 'This is the description field, again good for additional info.', $this->text_domain ),
                        'multi' => true,
                        //Must provide key => value pairs for radio options
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                        ),
                        'default'  => array('1','3'),
                    ),
                    array(
                        'id'       => 'opt-test-checkbox',
                        'type'     => 'checkbox',
                        'title'    => __( 'Checkbox Option', $this->text_domain ),
                        'subtitle' => __( 'No validation can be done on this field type', $this->text_domain ),
                        'desc'     => __( 'This is the description field, again good for additional info.', $this->text_domain ),
                        // 'data'  => 'pages',
                        // 'args' => array()
                    ),
                    array(
                        'id'       => 'opt-color-background',
                        'type'     => 'color',
                        'output'   => array( 'h2.site-description' ),
                        'title'    => __( 'Site-description Color', $this->text_domain ),
                        'subtitle' => __( 'Pick a background color for the theme.', $this->text_domain ),
                        'default'  => 'transparent',
                        'validate' => 'color',
                        'transparent' => true
                    ),
                    array(
                        'id'       => 'opt-color-header',
                        'type'     => 'color_gradient',
                        'title'    => __( 'Header Gradient Color Option', $this->text_domain ),
                        'subtitle' => __( 'Only color validation can be done on this field type', $this->text_domain ),
                        'desc'     => __( 'This is the description field, again good for additional info.', $this->text_domain ),
                        'default'  => array(
                            'from' => '#1e73be',
                            'to'   => '#00897e'
                        )
                    ),
                    array(
                        'id'       => 'opt-color-rgba',
                        'type'     => 'color_rgba',
                        'title'    => __( 'Color RGBA', $this->text_domain ),
                        'subtitle' => __( 'Gives you the RGBA color.', $this->text_domain ),
                        'output'   => array( '.widget-area' ),
                        'mode'     => 'background',
                        'validate' => 'colorrgba',
                        'options'  => array(
                            'show_palette' => true,
                            'show_selection_palette' => true,
                            'palette' => array(
                                array("#ffffff", "#000000", "#c00000", "#f79646", "#f5f445", "#7fd13b", "#4bacc6", "#1f497d", "#8064a2", "#ff0000"),
                                array("#f2f2f2", "#7f7f7f", "#f8d1d3", "#fdeada", "#fafdd7", "#e5f5d7", "#dbeef3", "#c6d9f0", "#e5e0ec", "#ffcc00"),
                                array("#d7d7d7", "#595959", "#f2a3a7", "#fbd5b5", "#fbfaae", "#cbecb0", "#b7dde8", "#8db3e2", "#ccc1d9", "#ffff00"),
                                array("#bebebe", "#414141", "#eb757b", "#fac08f", "#eef98e", "#b2e389", "#92cddc", "#548dd4", "#b2a2c7", "#00ff00"),
                                array("#a3a3a3", "#2a2a2a", "#a3171e", "#e36c09", "#dede07", "#5ea226", "#31859b", "#17365d", "#5f497a", "#0000ff"),
                                array("#7e7e7e", "#141414", "#6d0f14", "#974806", "#c0c00d", "#3f6c19", "#205867", "#0f243e", "#3f3151", "#9900ff")
                            ),
                            'cancel_text' => 'Anuleaza'
                        ),
                    ),
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', $this->text_domain) . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', $this->text_domain) . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', $this->text_domain) . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', $this->text_domain) . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            $this->sections[] = array(
                'title'     => __('Import / Export', $this->text_domain),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', $this->text_domain),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', $this->text_domain),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', $this->text_domain),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', $this->text_domain),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', $this->text_domain),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', $this->text_domain)
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', $this->text_domain),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', $this->text_domain)
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', $this->text_domain);
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'qrtrdx',
                'page_slug' => 'golfatica_options',
                'page_title' => __('Golfatica Options', $this->text_domain),
                'update_notice' => true,
                'intro_text' => __('<p>This text is displayed above the options panel. It isn\\’t required, but more info is always better! The intro_text field accepts all HTML.</p>', $this->text_domain),
                'footer_text' => __('<p>This text is displayed below the options panel. It isn\\’t required, but more info is always better! The footer_text field accepts all HTML.</p>', $this->text_domain),
                'menu_type' => 'submenu',
                'menu_title' => __('Optiuni'),
                'allow_sub_menu' => true,
                'page_parent' => 'themes.php',
                'page_parent_post_type' => 'your_post_type',
                'customizer' => true,
                'default_show' => true,
                'default_mark' => '*',
                'class' => 'golfatica',
                'dev_mode'             => false,
                'hints'  => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'   => 'light',
                        'shadow'  => true,
                        'rounded' => false,
                        'style'   => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'mouseover',
                        ),
                        'hide' => array(
                            'effect'   => 'slide',
                            'duration' => '500',
                            'event'    => 'click mouseleave',
                        ),
                    ),
                ),
                'output' => true,
                'output_tag' => true,
                'compiler' => true,
                'global_variable' => 'golf',
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'database' => 'options',
                'transient_time' => '3600',
                'network_sites' => true,
              );

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => __('Visit us on GitHub', $this->text_domain),
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => __('Like us on Facebook', $this->text_domain),
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => __('Follow us on Twitter', $this->text_domain),
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => __('Find us on LinkedIn', $this->text_domain),
                'icon'  => 'el-icon-linkedin'
            );

            if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                if ( ! empty( $this->args['global_variable'] ) ) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace( '-', '_', $this->args['opt_name'] );
                }
                $this->args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', $this->text_domain ), $v );
            } else {
                $this->args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', $this->text_domain );
            }

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Golfatica_Redux_Framework_Config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('golfatica_my_custom_field')):
    function golfatica_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('golfatica_validate_callback_function')):
    function golfatica_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
