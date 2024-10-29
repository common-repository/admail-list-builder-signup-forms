<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Admail
 * @subpackage Admail/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/public/
 * @copyright  2019 Admail.net
 */
class Admail_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $admail The ID of this plugin.
	 */
	private $admail;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Show minified version of the CSSl
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var bool $minified The value is set to the option in the settings.
	 */
	public $minified = FALSE;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param      string $admail  The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $admail, $version ) {
		$this->admail   = $admail;
		$this->version  = $version;
		$this->minified = get_option( Admail_Admin::USE_MINIFIED_VERSIONS, FALSE );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {
		global $post;
		//Get the postdata if we don't have it yet.
		setup_postdata( $post );
		//Check to see if its a post.
		$isAPost = is_a( $post, 'WP_Post' );

		//if a post is the right object, check to see if it has the admail shortcode.
		if (
			$isAPost && ( has_shortcode( $post->post_content, 'admail' ) ||
			              has_shortcode( $post->post_content, 'admail_modal' ) ) ||
			Admail_Admin::display_site_wide() ) {

			/**
			 * An instance of this class should be passed to the run() function
			 * defined in Admail_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Admail_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			//Todo Delete this function should we not need it.
			wp_enqueue_style( $this->admail,
			                  plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/admail-public' .
			                  ( $this->minified ? '.min' : '' ) . '.css',
			                  array(),
			                  $this->version,
			                  'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Admail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $post;
		setup_postdata( $post );
		$isAPost = is_a( $post, 'WP_Post' );

		//Check if the shorcode is used.
		$sitewide = Admail_Admin::display_site_wide();
		if (
			$isAPost && ( has_shortcode( $post->post_content, 'admail' ) ||
			              has_shortcode( $post->post_content, 'admail_modal' ) ) || ( $sitewide ) ) {
			//add the JS if required.
			wp_enqueue_script( $this->admail,
			                   plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/admail-public' .
			                   ( $this->minified ? '.min' : '' ) . '.js',
			                   array( 'jquery' ),
			                   $this->version,
			                   FALSE );

			if ( has_shortcode( $post->post_content, 'admail_modal' ) || $sitewide ) {
				wp_enqueue_script( 'colorbox',
				                   plugin_dir_url( dirname( __FILE__ ) ) .
				                   'node_modules/jquery-colorbox/jquery.colorbox-min.js',
				                   array( 'jquery' ),
				                   '',
				                   TRUE );
			}
		}
	}

	/**
	 *  Includes the site wide modal/lightbox if enabled correctly in the settings
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function site_wide() {
		$sitewide = Admail_Admin::display_site_wide();

		$site_wide_form = Admail_Admin::site_wide_form();

		$site_wide_account = Admail_Admin::site_wide_account();

		if ( $sitewide && isset( $site_wide_form ) && isset( $site_wide_account ) ) {
			$atts                = array();
			$partial             = 'public/partials/admail-modal-public.php';
			$atts['account_id']  = $site_wide_account;
			$atts['form_id']     = $site_wide_form;
			$atts['link_text']   = NULL;
			$atts['expire_open'] = 1;
			$atts['id']          = 'site_wide';
			$atts['cookie']      = 'show_admail_site_wide';

			echo "<a id='site_wide'></a>";
			echo include_admail_modal_partial( $atts, "", $partial );
			//die();
		}
	}
}
