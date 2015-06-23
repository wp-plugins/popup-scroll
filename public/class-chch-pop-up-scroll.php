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

if ( !class_exists( 'ChChPUSFTemplate' ) )
  require_once ( CHCH_PUSF_PLUGIN_DIR . 'public/includes/chch-pusf-template.php' );

/**
 * @package ChChPopUpScroll
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPopUpScroll {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   1.0.0
   *
   * @var     string
   */
  const VERSION = '1.1.0';

  /**
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'chch-pusf';

  /**
   *
   * @since    1.0.0
   *
   * @var      array
   */
  private $pop_ups = array();

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct() {

    // Get all active Pop-Ups
    $this->pop_ups = $this->chch_pusf_get_pop_ups();

    // Activate plugin when new blog is added
    add_action( 'wpmu_new_blog', array( $this, 'chch_pusf_activate_new_site' ) );

    // Include fonts on front-end
    add_action( 'wp_head', array( $this, 'chch_pusf_hook_fonts' ) );

    // Include public fancing styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'chch_pusf_template_scripts' ) );

    // Display active Pop-Ups on front-end
    add_action( 'wp_footer', array( $this, 'chch_pusf_show_popup' ) );

    // Register ajax subscribe
    add_action( 'wp_ajax_chch_pusf_newsletter_subscribe', array( $this, 'chch_pusf_ajax_newsletter_subscribe' ) );
    add_action( 'wp_ajax_nopriv_chch_pusf_newsletter_subscribe', array( $this, 'chch_pusf_ajax_newsletter_subscribe' ) );

  }

  /**
   * Return the plugin slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug() {
    return $this->plugin_slug;
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
   * Fired when the plugin is activated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_activate();

          restore_current_blog();
        }

      } else {
        self::single_activate();
      }

    } else {
      self::single_activate();
    }

  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate( $network_wide ) {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

      if ( $network_wide ) {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {

          switch_to_blog( $blog_id );
          self::single_deactivate();

          restore_current_blog();

        }

      } else {
        self::single_deactivate();
      }

    } else {
      self::single_deactivate();
    }

  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    1.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site( $blog_id ) {

    if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
      return;
    }

    switch_to_blog( $blog_id );
    self::single_activate();
    restore_current_blog();

  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    0.1.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {

    global $wpdb;

    // get an array of blog ids
    $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

    return $wpdb->get_col( $sql );

  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    1.0.0
   */
  private static function single_activate() {

  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    1.0.0
   */
  private static function single_deactivate() {

  }

  /**
   * Get All Active Pop-Ups IDs
   *
   * @since  1.0.0
   *
   * @return   array - Active Pop-Ups ids
   */
  private function chch_pusf_get_pop_ups() {
    $list = array();

    $args = array(
      'post_type' => 'chch-pusf',
      'posts_per_page' => -1,
      'meta_query' => array( array(
          'key' => '_chch_pusf_status',
          'value' => 'yes',
          ), ),
      );

    $pop_ups = get_posts( $args );

    if ( $pop_ups ) {
      foreach ( $pop_ups as $pop_up ) {
        $list[] = $pop_up->ID;
      }
    }
    return $list;
  }

  /**
   * Include fonts on front-end
   *
   * @since  0.1.0
   */
  function chch_pusf_hook_fonts() {

    $output = "<link href='//fonts.googleapis.com/css?family=Playfair+Display:400,700,900|Lora:400,700|Open+Sans:400,300,700|Oswald:700,300|Roboto:400,700,300|Signika:400,700,300' rel='stylesheet' type='text/css'>";

    echo $output;
  }

  /**
   * Include Templates scripts on Front-End
   *
   * @since  1.0.0
   *
   * @return   array - Pop-Ups ids
   */
  function chch_pusf_template_scripts() {

    $pop_ups = $this->pop_ups;

    if ( !empty( $pop_ups ) ) {
      foreach ( $pop_ups as $id ) {

        $template_id = get_post_meta( $id, '_chch_pusf_template', true );
        $template_base = get_post_meta( $id, '_chch_pusf_template_base', true );

        $template = new ChChPUSFTemplate( $template_id, $template_base, $id );
        $template->enqueue_template_style();
      }
    }

  }

  /**
   * Display Pop-Up on Front-End
   *
   * @since  1.0.0
   */
  public function chch_pusf_show_popup() {

    $pop_ups = $this->pop_ups;

    if ( !empty( $pop_ups ) ) {
      foreach ( $pop_ups as $id ) {

        $user_role = get_post_meta( $id, '_chch_pusf_role', true );
        $user_login = is_user_logged_in();

        if ( $user_role == 'logged' && !$user_login ) {
          continue;
        }

        if ( $user_role == 'unlogged' && $user_login ) {
          continue;
        }

        $pages = get_post_meta( $id, '_chch_pusf_page', true );
        if ( is_array( $pages ) ) {
          if ( is_home() ) {
            if ( in_array( 'chch_home', $pages ) ) {
              continue;
            } else {
              $array_key = array_search( get_the_ID(), $pages );
              if ( $array_key ) {
                unset( $pages[$array_key] );
              }
            }
          }

          if ( in_array( 'chch_woocommerce_shop', $pages ) ) {
            if ( function_exists( 'is_shop' ) ) {
              if ( is_shop() ) {
                continue;
              }
            }
          }

          if ( in_array( 'chch_woocommerce_category', $pages ) ) {
            if ( function_exists( 'is_product_category' ) ) {
              if ( is_product_category() ) {
                continue;
              }
            }
          }

          if ( in_array( 'chch_woocommerce_products', $pages ) ) {
            if ( function_exists( 'is_product' ) ) {
              if ( is_product() ) {
                continue;
              }
            }
          }

          if ( in_array( get_the_ID(), $pages ) ) {
            continue;
          }
        }

        $template_id = get_post_meta( $id, '_chch_pusf_template', true );
        $template_base = get_post_meta( $id, '_chch_pusf_template_base', true );

        echo '<div style="display:none;" id="modal-' . $id . '" class="pro-scroll ' . $template_id . $template_base . '">';

        $template = new ChChPUSFTemplate( $template_id, $template_base, $id );
        $template->build_css();
        $template->get_template();
        $template->build_js();

        echo '</div>';
      }
    }
  }

  /**
   * Ajax subscribe function
   *
   * @since  1.0.0
   */
  public function chch_pusf_ajax_newsletter_subscribe() {
    if ( !check_ajax_referer( 'chch-pusf-newsletter-subscribe', 'nounce' ) ) {
      print json_encode( array( 'status' => 'cheating' ) );
      die();
    }

    $id = $_POST['popup'];
    $to_email = get_post_meta( $id, '_chch_pusf_email', true ) ? get_post_meta( $id, '_chch_pusf_email', true ) : get_option( 'admin_email' );

    if ( filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ) {

      $sent = wp_mail( $to_email, __( 'You have a new subscriber!', $this->plugin_slug ), sprintf( __( "Hello,\n\nA new user has subscribed through: %s.\n\nSubscriber's email: %s", $this->plugin_slug ), get_bloginfo( 'url' ), $_POST['email'] ) );

      if ( $sent ) {
        print json_encode( array( 'status' => 'ok' ) );
        die();
      } else {
        print json_encode( array( 'status' => 'send_error' ) );
        die();
      }
    }

    print json_encode( array( 'status' => 'wrong_email' ) );
    die();
  }
}
