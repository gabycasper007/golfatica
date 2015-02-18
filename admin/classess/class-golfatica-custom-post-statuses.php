<?php 
defined( 'ABSPATH' ) or exit;

if (!class_exists('Golfatica_Custom_Post_Statuses')) {
	/**
	* Create Custom Post Status
	* @since 1.0
	*/
	class Golfatica_Custom_Post_Statuses {
		
		public $args;

		public function __construct( $args = array() ) {

			if ( ! is_admin() )
				return;

			$defaults = array(
				'post_type' => 'post',
				'statuses' => array()
			);
			$args = wp_parse_args( $args, $defaults );
			$this->args = (object) $args;
			

			add_action( 'init', array( $this, 'register_custom_post_status' ) );
			add_action( 'admin_footer-post.php', array( $this, 'display_when_edit_post') );
			add_filter( 'display_post_states', array( $this, 'display_next_to_the_post_title' ) );
			add_action( 'admin_footer-edit.php', array( $this, 'display_in_bulk_and_quick_edit' ) );

		} // End __construct()

		/**
		 * Register a post status. 
		 * It will automatically display a link in the tables view when you have posts assigned to that post status.
		 * @return void
		 */
		public function register_custom_post_status() {
			if ( isset($this->args->statuses) ) {
				foreach ( $this->args->statuses as $key => $status ) {
					$capitalized = ucwords($key);
					$defaults = array(
						'label'                     => _x( $capitalized, 'post' ),
						'public'                    => true,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						'label_count'               => _n_noop( $capitalized . ' <span class="count">(%s)</span>', $capitalized . ' <span class="count">(%s)</span>' ),
					);
					$this->args->statuses[ $key ] = wp_parse_args( $status, $defaults );
					register_post_status( $key, $this->args->statuses[ $key ] );
				}
			}
		} // End register_custom_post_status()

		/**
		 * Show Status When Editing a Post
		 * @return void
		 */
		public function display_when_edit_post() {
		    global $post;
		    
		    if( $post->post_type == $this->args->post_type && isset($this->args->statuses) ) {

		    	$current = '';
		    	$currentkey = '';
		    	foreach ( $this->args->statuses as $key => $status ) {

		    		if ( isset( $status['label'] ) ) {
			   
					    $selected = '';
					    $label = '';

				        if( $post->post_status == $key ){
				           $selected = 'selected="selected"';
				           $current = $status['label'];
				           $currentkey = $key;
				           $label = '<span id="post-status-display"> ' . $status['label'] . '</span>';
				        }

				        $option = '<option value="' . $key . '" ' . $selected . ' >' . $status['label'] . '</option>';

				        ?>

				        <script>
					        jQuery(document).ready(function($) {
					        	var current = <?php echo json_encode( $current ); ?>,
					        		currentkey = <?php echo json_encode( $currentkey ); ?>;

					            $( 'select#post_status' ).append( <?php echo json_encode( $option ); ?> );
					            $( '.misc-pub-section label' ).append( <?php echo json_encode( $label ); ?> );
					            $( '#save-post' ).text( 'Save ' + current ).val( 'Save ' + current );
					            // $( 'select#post_status').val( currentkey );
					        });
				        </script>
				        <?php 
				    }
			    }
		    }
		} // End display_when_edit_post()

		/**
		 * Show the post status next to the post when viewing all posts, and hide it when you view the list of archived posts
		 * @param  array $states [current states]
		 * @return array $states [post states]
		 */
		public function display_next_to_the_post_title( $states ) {
		    global $post;

		    $screen = get_current_screen();

		    if ( isset( $this->args->statuses ) ) {
		    	foreach ( $this->args->statuses as $key => $status ) {
			    	$arg = get_query_var( 'post_status' );

				    if ( $arg != $key && isset( $status['label'] ) && $post->post_status == $key ) {
				        return array( $status['label'] );
				    }
				}
		    }
		    return $states;
		} // End display_next_to_the_post_title()

		/**
		 * Adding custom post status to Bulk and Quick Edit boxes: Status dropdown
		 * @return void
		 */
		public function display_in_bulk_and_quick_edit() { 
			
			$screen = get_current_screen();

			if ( isset( $this->args->statuses ) ) { 

				$option = '';
				foreach ( $this->args->statuses as $key => $status ) { 
					if ( isset( $status['label'] ) && $screen->post_type == $this->args->post_type) {
						$option .= '<option value="' . $key . '">' . $status['label'] . '</option>';
					}
				}
				?>
				<script>
					jQuery(document).ready(function($){
						$(".inline-edit-status select").append(<?php echo json_encode( $option ); ?>);
					});
				</script>
				<?php 
			}
		} // End display_in_bulk_and_quick_edit()

	} // End class Golfatica_Custom_Post_Statuses
} // End if (!class_exists('Golfatica_Custom_Post_Statuses'))