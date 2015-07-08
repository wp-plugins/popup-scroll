<?php
/**
 * Pop-Up CC - Scroll
 *
 * @package   ChChPopUpScroll
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

if ( ! class_exists( 'ChChPopUpScrollFreePeview' ) )
    require_once( CHCH_PUSF_PLUGIN_DIR. 'admin/includes/chch-pusf-preview.php' );

if ( ! class_exists( 'ChChPUSFTemplate' ) )
    require_once( CHCH_PUSF_PLUGIN_DIR . 'public/includes/chch-pusf-template.php' );
    
if ( file_exists( CHCH_PUSF_PLUGIN_DIR . 'admin/includes/CMB2/init.php' ) ) {
  require_once CHCH_PUSF_PLUGIN_DIR . 'admin/includes/CMB2/init.php';
}

/**
 * @package ChChPopUpScroll
 * @author 	Chop-Chop.org <shop@chop-chop.org>
 */
 

class ChChPopUpScrollAdmin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	function __construct() {
		$this->plugin = ChChPopUpScroll::get_instance(); 
		$this->plugin_slug = $this->plugin->get_plugin_slug();
		
		// Register Post Type and messages
		add_action( 'init', array( $this, 'chch_pusf_register_post_type' ) );
		// Customize the columns in the popup list.
		add_filter( 'manage_chch-pusf_posts_columns',array( $this, 'chch_pusf_custom_pop_up_columns') );
		// Returns the content for the custom columns.
		add_action( 'manage_chch-pusf_posts_custom_column',array( $this, 'chch_pusf_manage_custom_pop_up_columns' ),10, 2); 
		add_filter( 'post_updated_messages',  array( $this, 'chch_pusf_post_type_messages') );
		
		// Register Post Type Meta Boxes and fields
		add_action( 'cmb2_init', array( $this, 'chch_pusf_initialize_cmb_meta_boxes') );
		add_filter( 'cmb2_render_chch_pusf_pages_select', array( $this, 'chch_pusf_render_pages_select'), 10, 5  );
		add_filter( 'cmb2_render_chch_pusf_cookie_select', array( $this, 'chch_pusf_render_cookie_select'), 10, 5  ); 
		add_filter( 'cmb2_render_chch_pusf_newsletter_select', array( $this, 'chch_pusf_render_newsletter_select'), 10, 5  ); 
		add_action( 'add_meta_boxes_chch-pusf', array( $this, 'chch_pusf_metabox' )); 
		
		
		// Save Post Data
		add_action( 'save_post', array( $this, 'chch_pusf_save_pop_up_meta'), 10, 3 ); 
		 
		// Templates view
		add_action('edit_form_after_title',array( $this, 'chch_pusf_templates_view' ));
		
		// admin scripts and AJAX
		add_action( 'admin_init', array( $this,'chch_pusf_tinymce_event') );
		add_action( 'admin_head', array( $this, 'chch_pusf_admin_head_scripts') );    
		add_action( 'admin_enqueue_scripts', array( $this, 'chch_pusf_enqueue_admin_scripts' ));
		add_action( 'wp_ajax_chch_pusf_load_preview_module', array( $this, 'chch_pusf_load_preview_module'  ));  
		 
	} 
	
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	
	/**
	 * Register Custom Post Type
	 *
	 * @since    0.1.0
	 */
	public function chch_pusf_register_post_type() {
		
		$domain = $this->plugin_slug;
		
		$labels = array(
			'name'                => _x( 'Pop-Up CC Scroll Free', 'Post Type General Name', $domain),
			'singular_name'       => _x( 'Pop-Up Scroll Free', 'Post Type Singular Name', $domain),
			'menu_name'           => __( 'Pop-Up CC Scroll Free', $domain),
			'parent_item_colon'   => __( 'Parent Item:', $domain),
			'all_items'           => __( 'Pop-Ups CC Scroll Free', $domain),
			'view_item'           => __( 'View Item', $domain),
			'add_new_item'        => __( 'Add New Pop-Up Scroll Free', $domain),
			'add_new'             => __( 'Add New Pop-Up Scroll Free', $domain),
			'edit_item'           => __( 'Edit Pop-Up Scroll Free', $domain),
			'update_item'         => __( 'Update Pop-Up Scroll Free', $domain),
			'search_items'        => __( 'Search Pop-Up Scroll Free', $domain),
			'not_found'           => __( 'Not found', $domain),
			'not_found_in_trash'  => __( 'No Pop-Up Close found in Trash', $domain),
		);
 

		$args = array(
			'label'               => __( 'Pop-Up Scroll Free', $domain),
			'description'         => __( '', $domain),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 65, 
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false
		);
		register_post_type( 'chch-pusf', $args );
	}
	
	
	/**
	 * Register custom post list columns
	 *
	 * @since     1.0.0
	 * 
	 */
	function chch_pusf_custom_pop_up_columns($defaults) {
		$defaults['chch_pusf_status'] = __('Active',$this->plugin_slug);
	 	$defaults['chch_pusf_clicks'] = __('Clicks',$this->plugin_slug);
		$defaults['chch_pusf_template'] = __('Template',$this->plugin_slug);
		
		return $defaults;
	}
	
	
	/**
	 * Fill columns in Pop-ups list
	 *
	 * @since     1.0.0  
	 */
	function chch_pusf_manage_custom_pop_up_columns($column, $post_id) {
		global $post;
		if ($column === 'chch_pusf_status') {
			echo ucfirst(get_post_meta($post_id,'_chch_pusf_status', true));
		}
		
	 	if ($column === 'chch_pusf_clicks') {
			$clicks = get_post_meta($post_id,'_chch_pusf_clicks',true) ? get_post_meta($post_id,'_chch_pusf_clicks',true) : '0';
			echo $clicks;
		} 
		
		if ($column === 'chch_pusf_template') {
			echo ucfirst(get_post_meta($post_id,'_chch_pusf_template', true));
		}
	}
	
	
	/**
	 * Pop-Ups update messages. 
	 *
	 * @param array $messages Existing post update messages.
	 *
	 * @return array Amended post update messages with new CPT update messages.
	 */
	function chch_pusf_post_type_messages( $messages ) {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );
	
		$messages['chch-pusf'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Pop-Up updated.', $this->plugin_slug ),
			2  => __( 'Custom field updated.', $this->plugin_slug),
			3  => __( 'Custom field deleted.',$this->plugin_slug),
			4  => __( 'Pop-Up updated.', $this->plugin_slug ), 
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Pop-Up restored to revision from %s', $this->plugin_slug ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Pop-Up published.', $this->plugin_slug ),
			7  => __( 'Pop-Up saved.', $this->plugin_slug ),
			8  => __( 'Pop-Up submitted.', $this->plugin_slug ),
			9  => sprintf(
				__( 'Pop-Up scheduled for: <strong>%1$s</strong>.', $this->plugin_slug ), 
				date_i18n( __( 'M j, Y @ G:i', $this->plugin_slug ), strtotime( $post->post_date ) )
			),
			10 => __( 'Pop-Up draft updated.', $this->plugin_slug )
		);
	
		if ( $post_type_object->publicly_queryable ) {
			$permalink = get_permalink( $post->ID );
	
			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Pop-Up',  $this->plugin_slug ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;
	
			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Pop-Up',  $this->plugin_slug ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}
	
		return $messages;
	}
	
	
	/**
	 * Initialize Custom Metaboxes Class
	 *
	 * @since  0.1.0 
	 */
	function chch_pusf_initialize_cmb_meta_boxes() { 
	 $domain = $this->plugin_slug;
    $prefix = '_chch_pusf_';

    $general_metabox = new_cmb2_box( array(
      'id' => 'chch-pus-metabox-general',
      'title' => __( 'GENERAL', $domain ),
      'object_types' => array( 'chch-pusf' ),
      'priority'   => 'low',
      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Pop-up Status', $domain ),
      'desc' => __( 'Enable or disable the plugin.', $domain ),
      'id' => $prefix . 'status',
      'type' => 'radio_inline',
      'default' => 'yes',
      'options' => array(
        'yes' => __( 'Turned ON', $domain ),
        'no' => __( 'Turned OFF', $domain ),
        ),
      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Show on mobile devices?', $domain ),
      'desc' => __( 'The pop-up will be visible on mobile devices.', $domain ),
      'id' => $prefix . 'show_on_mobile',
      'type' => 'checkbox',
      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Show only on mobile devices?', $domain ),
      'desc' => __( 'The pop-up will be visible on mobile devices only.', $domain ),
      'id' => $prefix . 'show_only_on_mobile',
      'type' => 'checkbox',
      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Show after', $domain ),
      'desc' => __( 'seconds', $domain ),
      'id' => $prefix . 'timer',
      'type' => 'text_small',
      'default' => '0' ) );

    $general_metabox->add_field( array(
      'name' => __( 'Show once per', $domain ),
      'desc' => __( '', $domain ),
      'id' => $prefix . 'show_only_once',
      'type' => 'chch_pusf_cookie_select', 
      'default' => 'refresh',

      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Auto close the pop-up after the sign-up', $domain ),
      'desc' => __( '', $domain ),
      'id' => $prefix . 'auto_closed',
      'type' => 'checkbox',
      ) );

    $general_metabox->add_field( array(
      'name' => __( 'Close after:', $domain ),
      'desc' => __( 'seconds', $domain ),
      'id' => $prefix . 'close_timer',
      'type' => 'text_small',
      'default' => '0' ) );

    /**
     * DISPLAY CONTROL
     */
    $display_metabox = new_cmb2_box( array(
      'id' => 'chch-pu-metabox-control',
      'title' => __( 'Display Control', $domain ),
      'object_types' => array( 'chch-pusf' ),
      'priority'   => 'low',
      ) );

    $display_metabox->add_field( array(
      'name' => __( 'By Role:', $domain ),
      'desc' => __( 'Decide who will see the pop-up.', $domain ),
      'id' => $prefix . 'role',
      'type' => 'radio',
      'options' => array(
        'all' => __( 'All', $domain ),
        'unlogged' => __( 'Show to unlogged users', $domain ),
        'logged' => __( 'Show to logged in users', $domain ),
        ),
      'default' => 'all' ) );

    $display_metabox->add_field( array(
      'name' => __( 'Disable on:', $domain ),
      'desc' => __( 'Decide on which pages the pop-up will not be visible. <br> Hold the ctrl key and click to select the pages which should not display the pop-up.', $domain ),
      'id' => $prefix . 'page',
      'type' => 'chch_pusf_pages_select',
      ) );
      
      /**
     * Newsletter
     */

    $newsletter_metabox = new_cmb2_box( array(
      'id' => 'chch-puf-metabox-newsletter',
      'title' => __( 'Newsletter', $domain ),
      'object_types' => array( 'chch-pusf' ),
      'priority'   => 'low',
      ) );

    $newsletter_metabox->add_field( array(
      'name' => __( 'Newsletter Status:', $domain ),
      'desc' => __( 'Enable or disable newsletter subscribe form on the front-end.', $domain ),
      'id' => $prefix . 'newsletter',
      'type' => 'radio_inline',
      'default' => 'yes',
      'options' => array(
        'yes' => __( 'Active', $domain ),
        'no' => __( 'Inactive', $domain ),
        ),
      ) );

      $newsletter_metabox->add_field( array(
					'name' => __( 'Save emails to:', $domain  ),
					'desc'    => __( '', $domain  ),
					'id'   => $prefix . 'save_emails',
					'type' => 'chch_pusf_newsletter_select', 
				));

    $newsletter_metabox->add_field( array(
      'name' => __( 'E-mail Address:', $domain ),
      'desc' => __( '<br>Subscription notifications will be sent to this email. If there is no email provided, admin email will be used.', $domain ),
      'id' => $prefix . 'email',
      'type' => 'text_medium',
      ) );
	}
	
	
	/**
   * Return a pages_select field for CMB
   *
   * @since     1.0.0
   * 
   */
  function chch_pusf_render_pages_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    $all_pages = $this->get_all_pages();
    printf( "<select class=\"cmb_select\" name=\"%s[]\" id=\"%s\" multiple=\"multiple\">", $field->args( '_name' ), $field->args( '_name' ) );
    $custom_pages = array(
      'chch_home' => 'Home (Latest Posts)',
      'chch_woocommerce_shop' => 'Woocommerce (Shop Page)',
      'chch_woocommerce_category' => 'Woocommerce (Category Page)',
      'chch_woocommerce_products' => 'Woocommerce (Single Product)',
      );

    foreach ( $custom_pages as $value => $title ):
      $selected = '';
      if ( !empty( $escaped_value ) && is_array( $escaped_value ) ) {
        if ( in_array( $value, $escaped_value ) ) {
          $selected = 'selected';
        }
      }
      echo '<option value="' . $value . '" ' . $selected . '>' . $title . '</option>	';
    endforeach;

    foreach ( $all_pages as $value => $title ):
      $selected = '';
      if ( !empty( $escaped_value ) ) {
        if ( in_array( $value, $escaped_value ) ) {
          $selected = 'selected';
        }
      }
      echo '<option value="' . $value . '" ' . $selected . '>' . $title . '</option>	';
    endforeach;
    echo '</select>';
    echo '<p class="cmb_metabox_description">Decide on which pages the pop-up will not be visible. <br> Hold the ctrl key and click to select the pages which should not display the pop-up.</p>';

  } 
	
	
	/**
   * Return a cookie_select field for CMB
   *
   * @since     1.0.0
   * 
   */
  function chch_pusf_render_cookie_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    $cookie_expire = array(
      'refresh' => 'Refresh',
      'session' => 'Session',
      'Day' => 'Day (Available in Pro)',
      'Week' => 'Week (Available in Pro)',
      'Month' => 'Month (Available in Pro)',
      'Year' => 'Year (Available in Pro)',
      );
    printf( "<select class=\"cmb_select\" name=\"%s\" id=\"%s\" >", $field->args( '_name' ), $field->args( '_name' ) );
    foreach ( $cookie_expire as $value => $title ):
      $selected = '';
      $disable = '';

      if ( !empty( $escaped_value ) ) {
        if ( $value == $escaped_value ) {
          $selected = 'selected';
        }
      }

      if ( $value != 'refresh' && $value != 'session' ) {
        $disable = 'disabled';
      }

      echo '<option value="' . $value . '" ' . $selected . ' ' . $disable . '>' . $title . '</option>';
    endforeach;

    echo '</select> <a href="http://ch-ch.org/puspro" target="_blank">Get Pro</a>';
  }
	
	  /**
   * Return a pages_select field for CMB
   *
   * @since     1.0.0
   * 
   */
  function chch_pusf_render_newsletter_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    $newsletter_expire = array(
      'Email' => 'Email',
      'MailChimp' => 'MailChimp (Available in Pro)',
      'GetResponse' => 'GetResponse (Available in Pro)',
      'CampaingMonitor' => 'CampaingMonitor (Available in Pro)',
      );

    printf( "<select class=\"cmb_select\" name=\"%s\" id=\"%s\">", $field->args( '_name' ), $field->args( '_name' ) );

    foreach ( $newsletter_expire as $value => $title ):
      $selected = '';
      $disable = '';

      if ( !empty( $escaped_value ) ) {
        if ( $value == $escaped_value ) {
          $selected = 'selected';
        }
      }

      if ( $value != 'Email' ) {
        $disable = 'disabled';
      }

      echo '<option value="' . $value . '" ' . $selected . ' ' . $disable . '>' . $title . '</option>';
    endforeach;

    echo '</select> <a href="http://ch-ch.org/pupro" target="_blank">Get Pro</a>';

  }
	
	/**
	 * Remove help tabs from post view.
	 *
	 * @since     1.0.7
	 * 
	 */
	function chch_pusf_remove_help_tabs($old_help, $screen_id, $screen){
		if ( 'post' == $screen->base && 'chch-pop-up' === $screen->post_type) {
			$screen->remove_help_tabs();
			return $old_help;
		}
	}
	
	
	/**
	 * Get all pages for CMB select pages field
	 *
	 * @since  0.1.0 
	 */
	private function get_all_pages() {
		
		$args = array(
		   'public'   => true,
		   '_builtin' => true
		);
		
		$post_types = get_post_types( $args );
		
        $args = array(
			'post_type' => $post_types,
			'posts_per_page' => -1, 
			'orderby' => 'title',
			'order' => 'ASC'
		);
		
		$post_list = get_posts($args);
		
		$all_posts = array();
		
		if($post_list):
			foreach($post_list as $post):
				$all_posts[$post->ID] = get_the_title($post->ID);
			endforeach;
		endif;
		
        return $all_posts; 
	}
	
	
	/**
	 * Register custom metaboxes with CMB
	 *
	 * @since  1.0.0 
	 */
	function chch_pusf_post_type_metaboxes( array $meta_boxes ) {
		
		$domain = $this->plugin_slug; 
		$prefix = '_chch_pusf_';
		
		/**
		 * GENERAL OPTIONS
		 */
		$meta_boxes['chch-pusf-metabox-general'] = array(
			'id'         => 'chch-pusf-metabox-general',
			'title'      => __( 'GENERAL', $domain ),
			'pages'      => array( 'chch-pusf', $domain ), 
			'context'    => 'normal',
			'priority'   => 'low',
			'show_names' => true,  
			'fields'     => array( 
				
				array(
					'name'    => __( 'Pop-up Status', $domain ),
					'desc'    => __( 'Enable or disable the plugin.', $domain  ),
					'id'      => $prefix . 'status',
					'type'    => 'radio_inline',
					'std'	=> 'yes',
					'options' => array(
						'yes' => __( 'Turned ON', $domain ),
						'no'   => __( 'Turned OFF', $domain ), 
					),
				),
				array(
					'name' => __( 'Show on mobile devices?', $domain  ),
					'desc'    => __( 'The pop-up will be visible on mobile devices.', $domain  ),
					'id'   => $prefix . 'show_on_mobile',
					'type' => 'checkbox',
				),
				array(
					'name' => __( 'Show only on mobile devices?', $domain  ),
					'desc'    => __( 'The pop-up will be visible on mobile devices only.', $domain  ),
					'id'   => $prefix . 'show_only_on_mobile',
					'type' => 'checkbox',
				), 
				array(
					'name' => __( 'Show once per', $domain  ),
					'desc'    => __( '', $domain  ),
					'id'   => $prefix . 'show_once_per',
					'type' => 'chch_pusf_cookie_select', 
				), 
				array(
					'name' => __( 'Auto close the pop-up after the sign-up', $domain  ),
					'desc'    => __( 'The pop-up will close after a given number of seconds after somebody has signed up.', $domain  ),
					'id'   => $prefix . 'auto_closed',
					'type' => 'checkbox', 
				),  
				array(
					'name'       => __( 'Close after:', $domain ),
					'desc'    => __( 'seconds', $domain  ),
					'id'         => $prefix . 'close_timer',
					'type'       => 'text_small', 
					'default' => '0'
				),  
			),

		); 	
	
		
		/**
		 * DISPLAY CONTROL
		 */
		$meta_boxes['chch-pusf-metabox-control'] = array(
			'id'         => 'chch-pusf-metabox-control',
			'title'      => __( 'Display Control', $domain ),
			'pages'      => array( 'chch-pusf', ), 
			'context'    => 'normal',
			'priority'   => 'low',
			'show_names' => true,  
			'fields'     => array( 
				array(
					'name' => __( 'By Role:', $domain  ),
					'desc'    => __( 'Decide who will see the pop-up.', $domain  ),
					'id'   => $prefix . 'role',
					'type' => 'radio',
					'options' => array(
						'all' => __( 'All', $domain  ),
						'unlogged' => __( 'Show to unlogged users', $domain  ),
						'logged' => __( 'Show to logged in users', $domain  ),
					),
					'default' => 'all'
				),
				array(
					'name' => __( 'Disable on:', $domain  ),
					'desc'    => __( 'Decide on which pages the pop-up will not be visible. <br> Hold the ctrl key and click to select the pages which should not display the pop-up.', $domain  ),
					'id'   => $prefix . 'page',
					'type' => 'chch_pusf_pages_select',  
				), 
			),
		); 
		
		/**
		 * Newsletter
		 */
		$meta_boxes['chch-pusf-metabox-newsletter'] = array(
			'id'         => 'chch-pusf-metabox-newsletter',
			'title'      => __( 'Newsletter', $domain ),
			'pages'      => array( 'chch-pusf', ), 
			'context'    => 'normal',
			'priority'   => 'low',
			'show_names' => true,  
			'fields'     => array( 
				array(
					'name'    => __( 'Newsletter Status:', $domain ),
					'desc'    => __( 'Enable or disable newsletter subscribe form on the front-end.', $domain  ),
					'id'      => $prefix . 'newsletter',
					'type'    => 'radio_inline',
					'default'	=> 'yes',
					'options' => array(
						'yes' => __( 'Active', $domain ),
						'no'   => __( 'Inactive', $domain ), 
					),
				),
				array(
					'name' => __( 'Save emails to:', $domain  ),
					'desc'    => __( '', $domain  ),
					'id'   => $prefix . 'save_emails',
					'type' => 'chch_pusf_newsletter_select', 
				),
				array(
					'name' => __( 'E-mail Address:', $domain  ),
					'desc'    => __( '<br>Subscription notifications will be sent to this email. If there is no email provided, admin email will be used.', $domain  ),
					'id'   => $prefix . 'email',
					'type' => 'text_medium',
				), 
			),
		); 
		
		return $meta_boxes;
	}
	
	/**
	 * Register custom metaboxes
	 *
	 * @since  0.1.0 
	 */
	public function chch_pusf_metabox( $post ) {
		remove_meta_box( 'slugdiv', 'chch-pusf', 'normal' );
		 
		
		add_meta_box(
			'chch-pusf-metabox-scroll',
			__( 'Scroll Options', $this->plugin_slug ),
			array( $this, 'chch_pusf_render_scroll_metabox'),
			'chch-pusf'
			,'normal'
			,'high'
		);
		
		$post_boxes = array(
			'chch-pusf-metabox-general',
			'chch-pusf-metabox-content',
			'chch-pusf-metabox-control',
			'chch-pusf-metabox-scroll', 
			'chch-pusf-metabox-newsletter', 
		);	
		
		foreach($post_boxes as $post_box)
		{
			add_filter( 'postbox_classes_chch-pusf_'.$post_box,array( $this, 'chch_pusf_add_metabox_classes') );
		}
	}
	
	
	/**
	 * Add metabox class for tabs
	 *
	 * @since  1.0.0 
	 */
	function chch_pusf_add_metabox_classes( $classes ) {
 		array_push( $classes, "chch-pusf-tab-2 chch-pusf-tab" );
		return $classes; 
	}
	
	/**
	 * View for Scroll Metabox
	 *
	 * @since  1.0.0 
	 */
	function chch_pusf_render_scroll_metabox($post) {
 		require_once( CHCH_PUSF_PLUGIN_DIR . 'admin/views/metabox/scroll-metabox.php' );
	} 
	
	
	/**
	 * Save Post Type Meta
	 *
	 * @since  0.1.0 
	 */
	function chch_pusf_save_pop_up_meta( $post_id, $post, $update ) { 
		if (
			!isset($_POST['chch_pusf_save_nonce']) 
			|| ! wp_verify_nonce($_POST['chch_pusf_save_nonce'],'chch_pusf_save_nonce_'.$post_id) 
		) 
		{
			return;
		}
		
		if(defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) return;
		
		$slug = 'chch-pusf';
		
		  
		if ( $slug != $post->post_type ) {
			return;
		}
		
		$template =  $_REQUEST['_chch_pusf_template'];
		update_post_meta( $post_id, '_chch_pusf_template', sanitize_text_field( $_REQUEST['_chch_pusf_template']) );
		update_post_meta( $post_id, '_chch_pusf_template_base', sanitize_text_field( $_REQUEST['_chch_pusf_template_base']) );
		
		$scroll_adapter = $_REQUEST['_chch_pusf_scroll_type'];
		update_post_meta( $post_id, '_chch_pusf_scroll_type', sanitize_text_field( $_REQUEST['_chch_pusf_scroll_type']));	
		
		update_post_meta( $post_id, '_chch_pusf_scroll_px', sanitize_text_field( $_REQUEST['_chch_pusf_px']));	
		update_post_meta( $post_id, '_chch_pusf_scroll_percent', sanitize_text_field( $_REQUEST['_chch_pusf_percent']));	
		update_post_meta( $post_id, '_chch_pusf_scroll_item', sanitize_text_field( $_REQUEST['_chch_pusf_item'])); 
		
		if(!empty($template))
		{
			$template_data = array();
			
			if ( isset( $_REQUEST['_'.$template.'_size_custom'] ) ) { 
				$template_data['size'] = array(
					'custom' => 1
				);   
			} else {
				$template_data['size'] = array(
					'custom' => 0
				); 
			}
			
			$template_data['size']['width'] = sanitize_text_field($_REQUEST['_'.$template.'_size_width']); 
			$template_data['size']['height'] = sanitize_text_field($_REQUEST['_'.$template.'_size_height']);
			 
			$template_data['background']= array(
				'color' => sanitize_text_field($_REQUEST['_'.$template.'_background_color']), 
				'type' => sanitize_text_field($_REQUEST['_'.$template.'_background_type']), 
				'image' => sanitize_text_field($_REQUEST['_'.$template.'_background_image']), 
				'pattern' => sanitize_text_field($_REQUEST['_'.$template.'_background_pattern']), 
				'repeat' => sanitize_text_field($_REQUEST['_'.$template.'_background_repeat']), 
				 
			);
			
			$template_data['overlay']= array( 
				'hide' => $_REQUEST['_'.$template.'_overlay_hide']
			);
			
			$template_data['input']= array(   
				'text' => sanitize_text_field($_REQUEST['_'.$template.'_input_text']),
			);
			
			$template_data['button']= array( 
				'text' => sanitize_text_field($_REQUEST['_'.$template.'_button_text']),
			);
			
			$p_array = array('</p>','<p>');
			
			$header_content = wpautop($_REQUEST['_'.$template.'_contents_header']);
			$subheader_content = wpautop($_REQUEST['_'.$template.'_contents_subheader']);
			$privacy_message_content = wpautop($_REQUEST['_'.$template.'_contents_privacy_message']);
			$main_content = $_REQUEST['_'.$template.'_contents_content'];
		 
			$template_data['contents']= array(
				'header' => wp_kses_post(str_replace($p_array, '', $header_content)),  
				'subheader' => wp_kses_post(str_replace($p_array, '', $subheader_content)),   
				'content' => $main_content,  
				'privacy_message' => wp_kses_post(str_replace($p_array, '', $privacy_message_content)),  
				'privacy_link' => sanitize_text_field($_REQUEST['_'.$template.'_contents_privacy_link']),  
				'privacy_link_label' => sanitize_text_field($_REQUEST['_'.$template.'_contents_privacy_link_label']), 
				'thank_you' => sanitize_text_field($_REQUEST['_'.$template.'_contents_thank_you']),     
			); 
			 
			update_post_meta($post_id, '_'.$template.'_template_data', $template_data);	
		}
	}
	 
	
	
	/**
	 * Add Templates View
	 *
	 * @since  0.1.0 
	 */
	public function chch_pusf_templates_view( $post ) { 
		  
		$screen = get_current_screen();
		if ( 'post' == $screen->base && 'chch-pusf' === $screen->post_type) {
			
			include(CHCH_PUSF_PLUGIN_DIR . 'admin/views/templates.php' );
		}
	}
	
	
	/**
	 * Return list of templates
	 *
	 * @since     1.0.0
	 *
	 * @return    array - template list
	 */
	public function get_templates() {
		if ( ! class_exists( 'PluginMetaData' ) )
			require_once( CHCH_PUSF_PLUGIN_DIR . 'admin/includes/PluginMetaData.php' ); 
		$pmd = new PluginMetaData;
		$pmd->scan(CHCH_PUSF_PLUGIN_DIR . 'public/templates');
		return $pmd->plugin;
	}
	
	
	/**
	 * Register TinyMce event
	 *
	 * @since     1.0.0
	 * 
	 */
	function chch_pusf_tinymce_event() { 
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			if ( get_bloginfo('version') < 3.9 ) { 
				add_filter( 'mce_external_plugins', array( $this, 'chch_pusf_tinymce_keyup_old') );
			} else
			{
				add_filter( 'mce_external_plugins', array( $this, 'chch_pusf_tinymce_keyup') );	 
			} 
		} 
	} 
	
	
	/**
	 * Add keyup to tineMce for WP version > 3.9
	 *
	 * @since     1.0.0
	 * 
	 */
	function chch_pusf_tinymce_keyup($plugin_array) { 
	 	$plugin_array['keyup_event'] = CHCH_PUSF_PLUGIN_URL .'admin/assets/js/chch-tinymce.js'; 
		return $plugin_array;
	}
	
	
	/**
	 * Add keyup to tineMce for WP version < 3.9
	 *
	 * @since     1.0.0
	 * 
	 */
	function chch_pusf_tinymce_keyup_old($plugin_array) { 
	 	$plugin_array['keyup_event'] = CHCH_PUSF_PLUGIN_URL .'admin/assets/js/chch-tinymce-old.js'; 
		return $plugin_array;
	}
	  
	
	/**
	 * Include google fonts
	 *
	 * @since  0.1.0 
	 */
	public function chch_pusf_admin_head_scripts() {
	 	$screen = get_current_screen();
		if ( 'post' == $screen->base && 'chch-pusf' === $screen->post_type) { 
			
			$js ="<link href='//fonts.googleapis.com/css?family=Playfair+Display:400,700,900|Lora:400,700|Open+Sans:400,300,700|Oswald:700,300|Roboto:400,700,300|Signika:400,700,300' rel='stylesheet' type='text/css'>";
			echo $js;
		}
	 } 
	 
	  
	/**
	 * Register and enqueue admin-specific style sheet.
	 
	 */
	public function chch_pusf_enqueue_admin_scripts() {

		$screen = get_current_screen();
		if ( 'post' == $screen->base && 'chch-pusf' === $screen->post_type) { 
			wp_enqueue_style('wp-color-picker' ); 
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-slider'); 
			
			wp_enqueue_media();
			
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), ChChPopUpScroll::VERSION );
			
			wp_enqueue_script( $this->plugin_slug .'-admin-scripts', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), ChChPopUpScroll::VERSION );  
			wp_localize_script( $this->plugin_slug .'-admin-scripts', 'chch_pusf_ajax_object', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ),'chch_pusf_url' => CHCH_PUSF_PLUGIN_URL) );
			
			wp_enqueue_style( $this->plugin_slug .'-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css', null, ChChPopUpScroll::VERSION,'all' );
			
			if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/defaults.css'))
			{
				wp_enqueue_style($this->plugin_slug .'_template_defaults', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/defaults.css', null, ChChPopUpScroll::VERSION, 'all');  
			}
			
			if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/fonts.css'))
			{
				wp_enqueue_style($this->plugin_slug .'_template_fonts', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/fonts.css', null, ChChPopUpScroll::VERSION, 'all');  
			} 
		}   
	}
	
	
	/**
	 * Load preview by ajax
	 
	 */
	function chch_pusf_load_preview_module() {
 
		$template = $_POST['template'];
		$template_base = $_POST['base'];
		$popup = $_POST['id'];
		
		$template = new ChChPUSFTemplate($template,$template_base,$popup); 
		$template->get_template();	
		die();
	} 
}
