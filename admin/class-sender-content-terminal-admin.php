<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Sender_Content_Terminal
 * @subpackage Sender_Content_Terminal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sender_Content_Terminal
 * @subpackage Sender_Content_Terminal/admin
 * @author     Your Name <email@example.com>
 */
class Sender_Content_Terminal_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sender_content_terminal    The ID of this plugin.
	 */
	private $sender_content_terminal;

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
	 * @param      string    $sender_content_terminal       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $sender_content_terminal, $version ) {

		$this->sender_content_terminal = $sender_content_terminal;
		$this->version = $version;
		$this->init_ajax();

	}

	/**
	 * Register the Ajax Endpoints for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function init_ajax()
	{
		add_action('wp_ajax_sender_content_terminal_accept_terms', array($this, 'sender_content_terminal_accept_terms'));
		add_action('wp_ajax_sender_content_terminal_save_token', array($this, 'sender_content_terminal_save_token'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$current_screen = get_current_screen();
		if (strpos($current_screen->base, '-content-terminal') !== false) {
			wp_enqueue_style($this->sender_content_terminal, plugin_dir_url(__FILE__) . 'css/tailwind.css', array(), $this->version, 'all');
		}
		wp_enqueue_style( $this->sender_content_terminal, plugin_dir_url( __FILE__ ) . 'css/sender-content-terminal-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->sender_content_terminal, plugin_dir_url( __FILE__ ) . 'js/sender-content-terminal-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * ender_content_terminal_accept_terms function.
	 *
	 * @since    1.0.0
	 */
	public function sender_content_terminal_accept_terms()
	{
		if (!get_option('sender_content_terminal_accepted_terms')) {
			add_option('sender_content_terminal_accepted_terms', sanitize_text_field($_POST['accepted']));
			echo "Thank you for accepted the terms.";
		} else {
			update_option('sender_content_terminal_accepted_terms', sanitize_text_field($_POST['accepted']));
			echo "hank you for accepted the terms.";
		}
		wp_die();
	}

	/**
	 * sender_content_terminal_save_token function.
	 *
	 * @since    1.0.0
	 */
	public function sender_content_terminal_save_token()
	{
		if (!get_option('sender_content_terminal_token')) {
			add_option('sender_content_terminal_token', sanitize_text_field($_POST['plugin_token']));
			echo "saved";
		}else{
			update_option('sender_content_terminal_token', sanitize_text_field($_POST['plugin_token']));
			echo "updated";
		}
		wp_die();
	}

	/**
	 * Admin Menu.
	 *
	 * @since    1.0.0
	 */
	public function sender_content_terminal_custom_menu()
	{
		add_menu_page(
			'Sender Content Terminal Settings',
			'Sender.law',
			'activate_plugins',
			'sender-content-terminal-index',
			array($this, 'get_admin_index'),
			'dashicons-plugins-checked',
			100
		);
		add_submenu_page('myplugin/myplugin-admin-page.php', 'My Sub Level Menu Example', 'Sub Level Menu', 'manage_options', 'myplugin/myplugin-admin-sub-page.php', 'myplguin_admin_sub_page');
	}

	/**
	 * get_admin_index function.
	 *
	 * @since    1.0.0
	 */
	public function get_admin_index()
	{
		$sender_content_terminal_accepted_terms = get_option('sender_content_terminal_accepted_terms');
		$sender_content_terminal_token = get_option('sender_content_terminal_token');
		include(SENDER_CONTENT_TERMINAL_PLUGIN_PATH . 'admin/templates/index.php');
	}
}
