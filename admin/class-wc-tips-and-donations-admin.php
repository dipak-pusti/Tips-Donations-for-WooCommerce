<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://aroundtheweb.in
 * @since      1.0.0
 *
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Tips_And_Donations
 * @subpackage Wc_Tips_And_Donations/admin
 * @author     Dipak Kumar Pusti <sipu.dipak@gmail.com>
 */
class Wc_Tips_And_Donations_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-tips-and-donations-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-tips-and-donations-admin.js', array( 'jquery' ), $this->version, false );
	}

		public function tips_tab( $tabs ) {

		$tabs['wctd'] = __( 'Tips / Donation', 'wc-tips' );
        return $tabs;
	}

	public function settings_fields() {

		$settings = array(
	        array(
	            'name'     	=> __( 'Tips / Donation Settings', 'wc-tips' ),
	            'type'     	=> 'title',
	            'id'       	=> 'wc_settings_tab_order_tips'
	        ),
	        array(
	        	'title'   	=> __( 'Enable Tips', 'wc-tips' ),
	        	'id'      	=> '_otd_enable',
	        	'type'    	=> 'checkbox',
	        	'default' 	=> 'yes',
	        ),
	        array(
	        	'title'     => __( 'Display Location', 'wc-tips' ),
	        	'desc'      => __( 'Choose where you would want to display Tips option.', 'wc-tips' ),
	        	'id'        => '_otd_display_location',
	        	'type'      => 'select',
	        	'desc_tip'  =>  true,
	        	'options'   => array(
	        		'cart_only' 	=> __( 'On Cart Page', 'wc-tips' ),
	        		'checkout_only' => __( 'On Checkout Page', 'wc-tips' ),
	        		'on_both' 		=> __( 'On Both Pages', 'wc-tips' )
	        	),
	        	'class'     => 'wc-enhanced-select',
	        ),
	        array(
	        	'title'     => __( 'Tips Form Heading', 'wc-tips' ),
	        	'id'        => '_otd_tips_heading',
	        	'type'      => 'text',
	        ),
	        array(
	        	'title'     => __( 'Tips Button Text', 'wc-tips' ),
	        	'id'        => '_otd_tips_text',
	        	'default'   => __( 'Tips', 'wc-tips' ),
	        	'type'      => 'text',
	        ),
	        array(
	        	'title'   	=> __( 'Allow Remove Tips Option', 'wc-tips' ),
	        	'id'      	=> '_otd_allow_remove',
	        	'type'    	=> 'checkbox',
	        	'default' 	=> 'yes',
	        ),
	        array(
	        	'title'     => __( 'Remove Tips Text', 'wc-tips' ),
	        	'id'        => '_otd_remove_tips_text',
	        	'default'   => __( 'Remove', 'wc-tips' ),
	        	'type'      => 'text',
	        ),
	        array(
	        	'title'     => __( 'Tips Fee Title', 'wc-tips' ),
	        	'id'        => '_otd_tips_title',
	        	'default'   => __( 'Tips', 'wc-tips' ),
	        	'type'      => 'text',
	        ),
	        array(
	        	'title'   	=> __( 'Is Taxable?', 'wc-tips' ),
	        	'id'      	=> '_otd_taxable',
	        	'type'    	=> 'checkbox'
	        ),
	        array(
	             'type' 	=> 'sectionend',
	             'id' 		=> 'wc_settings_tab_order_tips'
	        )
	    );

	    return apply_filters( 'wc_settings_tab_order_tips', $settings );
	}

	public function tips_opts() {
	    // Output the Settings Fields
	    woocommerce_admin_fields( $this->settings_fields() );
	}

	public function tips_save() {
	    // Save the Settings Fields
	    woocommerce_update_options( $this->settings_fields() );
	}
}