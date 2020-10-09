<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       boldgrid.com
 * @since      1.0.0
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/admin
 * @author     Jesse C Owens <jesseo@boldgrid.com>
 */
class Jco_Userecho2bbpress_Admin {

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
	 * Register the options menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_menu() {
		add_management_page( 'UserEcho 2 bbPress', 'UserEcho 2 bbPress', 'manage_options', 'jco_userecho2bbpress', array($this, 'display_plugin_menu') );
	}

	/**
	* Add the admin menu displayed
	*
	* @since		1.0.0
	*/
	public function display_plugin_menu() {
		include plugin_dir_path( __FILE__ ) . 'partials/jco-userecho2bbpress-admin-display.php';
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jco_Userecho2bbpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jco_Userecho2bbpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jco-userecho2bbpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jco_Userecho2bbpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jco_Userecho2bbpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jco-userecho2bbpress-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	* Check if UserEcho data files exist
	* @return 	bool
	*
	* @since 1.0.0
	*/
	public function data_files_exist() {
		return ( file_exists( plugin_dir_path( __DIR__ ) . 'data/comments.json' ) && file_exists( plugin_dir_path( __DIR__ ) . 'data/forums.json' ) && file_exists( plugin_dir_path( __DIR__ ) . 'data/topics.json' ) && file_exists( plugin_dir_path( __DIR__ ) . 'data/users.json' ) );
	}

	public function display_forum_data() {
		if ( $this->data_files_exist() ) {
			$forum = new Jco_Userecho2bbpress_Forum( plugin_dir_path( __DIR__ ) . 'data/forums.json' );
		} else {
			return '<span class="alert">Could not find UserEcho Files.</span>';
		}
		$display = '<table><tr><th>ID</th><th>Name</th><th>Topics</th><tr>';
		$public_forums = $forum->get_public_forums();
		foreach ( $public_forums as $id=>$name ) {
			$display .= '<tr><td>' . $id . '</td><td>' . $name . '</td><td>' . $forum->get_forum_topic_count( $id ) . '</td></tr>';
		}

		$display .= '</table>';
		//return $forum->get_forum();
		return $display;
	}

}
