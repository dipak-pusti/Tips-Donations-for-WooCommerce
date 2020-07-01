<?php

/**
 *
 * @link              http://aroundtheweb.in
 * @since             1.0.0
 * @package           Wc_Tips_And_Donations
 *
 * @wordpress-plugin
 * Plugin Name:       Tips & Donation for WooCommerce
 * Description:       Allow your customers to give a Donation or a Tip for your awesome services to them.
 * Version:           0.0.1
 * Author:            Dipak Kumar Pusti
 * Author URI:        https://profiles.wordpress.org/dipakbbsr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-tips
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCTD_VERSION', '0.0.1' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-tips-and-donations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wctd_initialize_plugin() {

	$plugin = new Wc_Tips_And_Donations();
	$plugin->run();

}
wctd_initialize_plugin();