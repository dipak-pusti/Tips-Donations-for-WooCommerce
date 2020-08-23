<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://aroundtheweb.in
 * @since      1.0.0
 *
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/public
 * @author     Dipak Kumar Pusti <sipu.dipak@gmail.com>
 */
class Wc_Tips_And_Donations_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-tips-and-donations-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-tips-and-donations-public.js', array( 'jquery' ), $this->version, false );

		$params = array(
	      'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
	      'cart_url'			=> wc_get_cart_url(),
	      'checkout_url'		=> wc_get_checkout_url(),
	      'add_tips_nonce'		=> wp_create_nonce( 'add-order-tips' ),
	      'del_tips_nonce'		=> wp_create_nonce( 'del-order-tips' ),
	      'invalid_tip_text'	=> esc_html( 'Please enter a valid amount.', 'order-tips' ),
	    );

	    wp_localize_script( $this->plugin_name, 'otd_vars', $params );
	}

	public function order_tips_form() {
		
		ob_start();
		
		include_once dirname( __FILE__ ) . '/views/form.php';

		$content = ob_get_contents();
		ob_get_clean();

		echo $content;
	}

	public function add_tips() {
		
		check_ajax_referer( 'add-order-tips', 'security' );

		global $woocommerce;
		
		$this->amount = !empty($_REQUEST['amount']) ? absint( $_REQUEST['amount'] ) : 0;
		$woocommerce->session->set( 'order-tips-session', $this->amount);

		wp_die();
	}

	public function remove_tips() {
		
		check_ajax_referer( 'del-order-tips', 'security' );

		global $woocommerce;
		
		$this->amount = !empty($_REQUEST['amount']) ? absint( $_REQUEST['amount'] ) : 0;
		$woocommerce->session->set( 'order-tips-session', $this->amount);

		wp_die();
	}

	public function add_cart_fee() {

		global $woocommerce;

		$donation_amount = $this->get_donation_amount();
		if ($donation_amount && is_numeric($donation_amount) && $donation_amount > 0) :

			$fee_title 	 = get_option( '_otd_tips_title', true );
			$fee_taxable = get_option( '_otd_taxable', true );
			
			$taxable = ( $fee_taxable == 'yes' ) ? true : false;
			$woocommerce->cart->add_fee( $fee_title, $donation_amount, $taxable );
		
		endif;
	}

	public function get_donation_amount() {

		global $woocommerce;
		
		$amount = $woocommerce->session->get('order-tips-session');
		if ( $amount && is_numeric( $amount ) ) {
			return $amount;
		} else {
			return '0';
		}
	}

	public function thank_you() {

		global $woocommerce;
		$woocommerce->session->set( 'order-tips-session', 0 );
	}
}
