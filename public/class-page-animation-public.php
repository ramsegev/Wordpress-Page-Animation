<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www
 * @since      1.0.0
 *
 * @package    Page_animation
 * @subpackage Page_animation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Page_animation
 * @subpackage Page_animation/public
 * @author     Ram Segev <ramsegev@gmail.com>
 */
class Page_animation_Public {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Page_animation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Page_animation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		wp_register_style( 'page-animation-public.css',  plugin_dir_url( __FILE__ ) . 'css/page-animation-public.css', array(), null, 'all' );
		wp_register_style( 'animate.css',  plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), null, 'all' );
		wp_enqueue_style( 'animate.css' );
		wp_enqueue_style( 'page-animation-public.css' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', false, '3.1.0');
        wp_enqueue_script('jquery');
		/* wp_deregister_script('jquery-ui');
		wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', false, '1.12.1');
        wp_enqueue_script('jquery-ui'); */
		wp_deregister_script('ScrollMagic');
		wp_register_script('ScrollMagic', plugin_dir_url( __FILE__ ) . 'js/scrollmagic/uncompressed/ScrollMagic.js', false);
        wp_enqueue_script('ScrollMagic');
		wp_deregister_script('debug_addIndicators_js');
		wp_register_script('debug_addIndicators_js', plugin_dir_url( __FILE__ ) . 'js/scrollmagic/uncompressed/plugins/debug.addIndicators.js', false);
        wp_enqueue_script('debug_addIndicators_js');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/page-animation-public.js', array( 'jquery' ), $this->version, false );
	}

}
