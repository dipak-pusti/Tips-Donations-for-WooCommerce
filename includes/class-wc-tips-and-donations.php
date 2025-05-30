<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://aroundtheweb.in
 * @since      1.0.0
 *
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/includes
 * @author     Dipak Kumar Pusti <sipu.dipak@gmail.com>
 */
class Wc_Tips_And_Donations {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Tips_And_Donations_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version = WCTD_VERSION;
		$this->plugin_name = 'wc-tips-and-donations';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Tips_And_Donations_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Tips_And_Donations_i18n. Defines internationalization functionality.
	 * - Wc_Tips_And_Donations_Admin. Defines all hooks for the admin area.
	 * - Wc_Tips_And_Donations_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-tips-and-donations-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-tips-and-donations-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-tips-and-donations-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-tips-and-donations-public.php';

		$this->loader = new Wc_Tips_And_Donations_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Tips_And_Donations_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Tips_And_Donations_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Tips_And_Donations_Admin( $this->get_plugin_name(), $this->get_version() );

		// Enqueue Admin Styles and Scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Order Tips related settings and Save functionality
		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $plugin_admin, 'tips_tab', 50 );
		$this->loader->add_filter( 'woocommerce_settings_tabs_wctd', $plugin_admin, 'tips_opts' );
		$this->loader->add_filter( 'woocommerce_update_options_wctd', $plugin_admin, 'tips_save' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Tips_And_Donations_Public( $this->get_plugin_name(), $this->get_version() );

		// Frontend Styles and Scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Enable the Tips Filter by Checking Settings
		if( 'yes' === get_option('_otd_enable') ) {
			$display_location = get_option( '_otd_display_location', 'on_both' );
			switch ( $display_location ) {
				case 'cart_only':
					$this->loader->add_action( 'woocommerce_after_cart_table', $plugin_public, 'order_tips_form' );
					break;

				case 'checkout_only':
					///$this->loader->add_action( 'woocommerce_review_order_before_submit', $plugin_public, 'order_tips_form' );
					$this->loader->add_action( 'woocommerce_review_order_before_order_total', $plugin_public, 'order_tips_form' );
					break;
				
				default:
					$this->loader->add_action( 'woocommerce_after_cart_table', $plugin_public, 'order_tips_form' );
					///$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'order_tips_form' );
					$this->loader->add_action( 'woocommerce_review_order_before_order_total', $plugin_public, 'order_tips_form' );
					break;
			}
		}

		// Ajax Add Tips Amount
		$this->loader->add_action( 'wp_ajax_add_tips', $plugin_public, 'add_tips' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_tips', $plugin_public, 'add_tips' );

		// Ajax Remove Tips Amount
		$this->loader->add_action( 'wp_ajax_remove_tips', $plugin_public, 'remove_tips' );
		$this->loader->add_action( 'wp_ajax_nopriv_remove_tips', $plugin_public, 'remove_tips' );

		// Add Donation to Cart Fees
		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'add_cart_fee' );

		// Update Tips Session Once the purchase is done
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'thank_you');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Tips_And_Donations_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
