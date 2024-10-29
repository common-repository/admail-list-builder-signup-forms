<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/admin
 * @copyright  2019 Admail.net All Rights Reserved
 */
class Admail_Admin {
	/** @var string Constants for the SiteWide Model fields names and database entries */
	const SITEWIDE_MODAL_ENABLE_ELEMENT_ID = 'admail-admin-sitewide-modal-enable';
	const SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID = 'admail-admin-sitewide-modal-account';
	const SITEWIDE_MODAL_FORM_ELEMENT_ID = 'admail-admin-sitewide-modal-form';

	const USE_MINIFIED_VERSIONS = 'admail-admin-minification';

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
	 * Is the site wide form enabled?
	 *
	 * @return mixed|null|boolean|string
	 * @access public
	 */
	public static function site_wide() {
		return get_option( self::SITEWIDE_MODAL_ENABLE_ELEMENT_ID, FALSE );
	}

	/**
	 * Get the Wordpress stored Form ID for the site_wide form
	 *
	 * @return mixed|null|int|string
	 * @access public
	 */
	public static function site_wide_form() {
		return get_option( self::SITEWIDE_MODAL_FORM_ELEMENT_ID, NULL );
	}

	/**
	 * Get the Wrodpress stored setting Account ID for the site_wide.
	 *
	 * @return mixed|null|int|string
	 * @access public
	 */
	public static function site_wide_account() {
		return get_option( self::SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID, NULL );
	}


	/**
	 * Do we display the Site Wide Form
	 *
	 *  includes is_admin();
	 *
	 * @return bool
	 * @access public
	 */
	public static function display_site_wide() {
		return self::site_wide() && ! is_admin();

	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $admail  The name of this plugin.
	 * @param      string $version The version of this plugin.
	 *
	 * @access   public
	 */
	public function __construct( $admail, $version ) {
		$this->admail  = $admail;
		$this->version = $version;
	}

	/**
	 * Admail Admin Settings Init
	 *
	 * Adds Admin Settings.
	 *
	 * @access public
	 */
	public function settings_init() {
		// Add the Admail section
		add_settings_section( 'admail-admin-section',                   // Section ID
		                      'Admail Settings',  // Section title
		                      array( $this, 'settings_section_description' ), // Section callback function
		                      'general'                          // Settings page slug
		);
		register_setting( 'general',             // Options group
		                  self::USE_MINIFIED_VERSIONS,      // Option name/database
		                  array( $this, 'settings_sanitize_bool' ) // Sanitize callback function
		);
		/* Register Settings */
		//Adds the site wide checkbox.
		register_setting( 'general',             // Options group
		                  self::SITEWIDE_MODAL_ENABLE_ELEMENT_ID,      // Option name/database
		                  array( $this, 'settings_sanitize_bool' ) // Sanitize callback function
		);

		$args = array(
			'type'              => 'string',
			'sanitize_callback' => array( $this, 'settings_sanitize_int' ),
		);
		/* Register Settings */
		//Adds the site wide popup Account number field.
		register_setting( 'general',                                     // Options group
		                  self::SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID,      // Option name/database
		                  $args // Sanitize callback function
		);

		/* Register Settings */
		//Adds the site wide popup form number field
		register_setting( 'general',                                     // Options group
		                  self::SITEWIDE_MODAL_FORM_ELEMENT_ID,         // Option name/database
		                  $args                                         // Sanitize callback function
		);
		$this->add_admin_section_setting( self::USE_MINIFIED_VERSIONS,
		                                  __( 'Use Minified Versions of JS and CSS (Recommended)' ),
		                                  'field_minified' );
		//Add the site form fields and sections to the Settings page.
		$this->add_admin_section_setting( self::SITEWIDE_MODAL_ENABLE_ELEMENT_ID,
		                                  __( 'Include Admail On Page' ),
		                                  'field_enable' );
		$this->add_admin_section_setting( self::SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID,
		                                  __( 'Account Number' ),
		                                  'field_account' );
		$this->add_admin_section_setting( self::SITEWIDE_MODAL_FORM_ELEMENT_ID, __( 'Form Number' ), 'field_form' );

	}


	/**
	 * Sanitize Callback Function
	 *
	 * @access public
	 *
	 * @param $input
	 *
	 * @return bool
	 */
	public function settings_sanitize_bool( $input ) {
		return isset( $input ) ? TRUE : FALSE;
	}

	/** Sanitize Callback Function
	 *
	 * @param $input
	 *
	 * @access public
	 * @return mixed
	 */
	public function settings_sanitize_int( $input ) {
		//die( 'Made it!' . $input );

		return filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
	}

	/** Setting Section Description
	 *
	 * @access public
	 */
	public function settings_section_description() {
		echo wpautop( __( "Show the Admail Lightbox to new users on every page of the site." ) );

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {

		/** An instance of this class is passed to the run() function
		 * defined in Admail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Admail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->admail,
		                  plugin_dir_url( __FILE__ ) . 'css/class-admail-admin.css',
		                  array(),
		                  $this->version,
		                  'all' );

	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {

		/**
		 * Enquire the java script.
		 */
		wp_enqueue_script( $this->admail,
		                   plugin_dir_url( __FILE__ ) . 'js/class-admail-admin.js',
		                   array( 'jquery' ),
		                   $this->version,
		                   FALSE );

	}

	/**
	 * Output the checkbox for the sitewide lightnox admin form field.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_minified() {
		$value = get_option( self::USE_MINIFIED_VERSIONS, FALSE );
		$this->field_input_checkbox( self::USE_MINIFIED_VERSIONS, $value );
	}

	/**
	 * Output the checkbox for the sitewide lightnox admin form field.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_enable() {
		$value = get_option( self::SITEWIDE_MODAL_ENABLE_ELEMENT_ID, FALSE );
		$this->field_input_checkbox( self::SITEWIDE_MODAL_ENABLE_ELEMENT_ID, $value );
	}

	/**
	 * Output the textbox for the sitewide lightbox admin form field.
	 *
	 * Handles the Account number.  This should be found on the Admail site information.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_account() {
		$value = get_option( self::SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID, NULL );
		$this->field_input_text( self::SITEWIDE_MODAL_ACCOUNT_ELEMENT_ID, $value );

	}

	/**
	 * Output the textbox for the sitewide lightbox admin form field.
	 *
	 * Handles the Account number.  This should be found on the Admail site information.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_form() {
		$value = get_option( self::SITEWIDE_MODAL_FORM_ELEMENT_ID, NULL );
		$this->field_input_text( self::SITEWIDE_MODAL_FORM_ELEMENT_ID, $value );
	}

	/**
	 * Adds a settings field for the admin section.  Places it in the general, admail-admin-section.  Utilizes internal
	 * class method $method
	 *
	 * @param int|string $element_ID
	 * @param string     $label
	 * @param string     $method Name of method in this $this to generate the field.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected function add_admin_section_setting( $element_ID, $label, $method ) {
		add_settings_field( $element_ID,
		                    '<label for="' . $element_ID . '">' . $label . '</label>',
		                    array( $this, $method ),
		                    'general',
		                    'admail-admin-section' );
	}

	/**
	 * Construct the input tag and field
	 *
	 * @param int|string $SITEWIDE_MODAL_ELEMENT_ID Sets the input types name and id (which is this value plus "-ID"
	 * @param            $value
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	protected function field_input_text( $SITEWIDE_MODAL_ELEMENT_ID, $value ) {
		echo '<input type="text" id="' . $SITEWIDE_MODAL_ELEMENT_ID . '-ID" name="' . $SITEWIDE_MODAL_ELEMENT_ID . '"';
		if ( $value ) {
			echo ' value="' . $value . '"';
		}
		echo '/>';
	}

	/**
	 * Construct a checkbox field tag.
	 *
	 * @param int|string      $SITEWIDE_MODAL_ENABLE_ELEMENT_ID Sets the input types name and id (which is this value
	 *                                                          plus "-ID"
	 * @param bool|int|string $value
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function field_input_checkbox( $SITEWIDE_MODAL_ENABLE_ELEMENT_ID, $value ) {
		echo '<input type="checkbox" id="' . $SITEWIDE_MODAL_ENABLE_ELEMENT_ID . '-ID" name="' .
		     $SITEWIDE_MODAL_ENABLE_ELEMENT_ID . '" ' . ( $value ? 'checked' : '' ) . '/>';
	}
}
