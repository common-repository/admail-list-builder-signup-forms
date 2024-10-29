<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Admail Signup Forms
 * Plugin URI:        https://admail.net/
 * Description:       This plugin adds an Admail form and lightbox to your pages.
 * Version:           1.0.3
 * Author:            Michael Parisi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Admail
 * Domain Path:       /languages *
 * @author            Michael Parisi <mgparisicpu@gmail.com>
 * @link              https://Admail.net
 * @since             1.0.0
 * @package           Admail
 * @subpackage        Admail
 * @copyright         2019 Admail.net
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 *
 *
 * @since    1.0.0
 */
define( 'ADMAIL_VERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-admail-activator.php
 *
 * @since    1.0.0
 */
function activate_admail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admail-activator.php';
	Admail_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-admail-deactivator.php
 *
 * @since    1.0.0
 */
function deactivate_admail() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admail-deactivator.php';
	Admail_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_admail' );
register_deactivation_hook( __FILE__, 'deactivate_admail' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-admail.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_admail() {

	$plugin = new Admail();
	$plugin->run();
}


/**
 * Respond to the admail shortcode to create a iframe of the admail site and include javascripts and CSS when its used.
 *
 * @param array  $atts
 * @param null   $content
 * @param string $tag
 *
 * @return false|string
 *
 * @since    1.0.0
 */
function run_admail_shortcode( $atts = [], $content = NULL, $tag = '' ) {

	$partial = 'public/partials/admail-public.php';

	return include_admail_partial( $atts, $tag, $partial );

}


/**
 * Respond to the admail shortcode to create a iframe of the admail site and include javascripts and CSS when its used.
 *
 * @param array  $atts
 * @param null   $content
 * @param string $tag
 *
 * @return false|string
 *
 *
 */
function run_admail_modal_shortcode( $atts = [], $content = NULL, $tag = '' ) {

	$partial = 'public/partials/admail-modal-public.php';

	return include_admail_modal_partial( $atts, $tag, $partial );

}


/**
 * Include a partial file into the system, run it, and return the output.
 *
 * @param $atts
 * @param $tag
 * @param $partial
 *
 * @return false|string
 * @since    1.0.0
 */
function include_admail_partial( $atts, $tag, $partial ) {
	$admail_atts = get_admail_atts( $atts, $tag );

	//Make sure the two required attributes are set.
	if ( isset( $admail_atts['account_id'] ) && isset( $admail_atts['form_id'] ) ) {
		//Start recording the output of any html we echo out to return as a result of the function call.
		ob_start();
		include( $partial );

		return ob_get_clean();
	} else {
		//warn the user of an invalid shortcode.
		return "Admail Warning: Please add a valid account_id and the form attributes to the AdNail shortcode. " .
		       "Check the Admail.org website for these numbers. " .
		       "The format of the shortcode is [admail account_id=# form_id=#].";
	}
}


/**
 * @param $atts
 * @param $tag
 * @param $partial
 *
 * @return false|string
 * @since    1.0.0
 */
function include_admail_modal_partial( $atts, $tag, $partial ) {
	$admail_atts = get_admail_atts( $atts, $tag );

	//Make sure the two required attributes are set.
	if (
		isset( $admail_atts['account_id'] ) && isset( $admail_atts['form_id'] ) ) {
		//Start recording the output of any html we echo out to return as a result of the function call.
		ob_start();
		include( $partial );

		return ob_get_clean();
	} else {
		//warn the user of an invalid shortcode.
		return "Admail Warning: Please add a valid account_id, the form_id and link_text attributes to the AdNail " .
		       "shortcode. Check the Admail.org website for these numbers. " .
		       "The format of the shortcode is [admail_modal account_id=# form_id=# link_text='Click Here'].";
	}
}


/**
 * @param $atts
 * @param $tag
 *
 * @return mixed
 * @since    1.0.0
 */
function get_admail_atts( $atts, $tag ) {
// normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// override default attributes with user attributes
	$admail_atts = shortcode_atts( [
		                               'account_id'  => NULL,
		                               'form_id'     => NULL,
		                               'link_text'   => 'Click Here',
		                               'id'          => uniqid( 'admail-' ),
		                               'start_open'  => ( isset( $atts['start_open'] ) &&
		                                                  $atts['start_open'] ? 1 : NULL ),
		                               'expire_open' => ( isset( $atts['expire_open'] ) &&
		                                                  $atts['expire_open'] ? 1 : NULL ),
	                               ],
	                               $atts,
	                               $tag );

	return $admail_atts;
}


//Add the entire structure of the plugin, including Javascript, CSS, and any resources.
run_admail();
//** The heart and soul of what we do.  */
add_shortcode( 'admail', 'run_admail_shortcode' );
add_shortcode( 'admail_modal', 'run_admail_modal_shortcode' );