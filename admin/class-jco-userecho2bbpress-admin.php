<?php

/**
 * The controller for the importer.
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

	private $forum;


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
		$this->create_forum();
	}

	private function create_forum() {
		if ( $this->data_files_exist() ) {
			$this->forum = new Jco_Userecho2bbpress_Forum( plugin_dir_path( __DIR__ ) . 'data/' );
		} else {
			$this->forum = null;
		}
	}

	/**
	 * Register the options menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_menu() {
		add_management_page( 'UserEcho 2 bbPress', 'UserEcho 2 bbPress', 'manage_options', $this->plugin_name, array($this, 'display_plugin_menu') );
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

	/**
	* Display information about the forums in the data export
	*
	* @return string $display A snippet of HTML to display information about the Forums
	*/
	public function display_forum_data() {
		if ( is_null($this->forum) ) {
			return '<span class="alert">Could not find UserEcho Files.</span>';
		}
		$display = '<table><tr><th>ID</th><th>Name</th><th>Topics</th><tr>';
		$public_forums = $this->forum->get_public_forums();
		foreach ( $public_forums as $id=>$name ) {
			$display .= '<tr><td>' . $id . '</td><td>' . $name . '</td><td>' . $this->forum->get_forum_topic_count( $id ) . '</td></tr>';
		}

		$display .= '</table>';
		return $display;
	}


	/**
	* Display the forum selector format
	*
	* @return string $display An HTML Form containing the dropdown list of Forums
	*/
	public function display_forum_selector_form() {
		if ( is_null($this->forum) ) {
			return '<span class="alert">Could not find UserEcho Files.</span>';
		}
		$public_forums = $this->forum->get_public_forums();
		$auth_nonce = wp_create_nonce( 'jco_select_forum_nonce');

		$display = '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post" id="jco_userecho2bbpress_forum_selector">';
		$display .= '<input type="hidden" name="action" value="jco_forum_selection" />';
		$display .= '<input type="hidden" name="jco_select_forum_nonce" value="' . $auth_nonce .'" />';
		$display .= '<select required id="jco_forum_id" name="jco[forum_id]">';
		$display .= '<option value="">Select a forum ID to work with</option>';

		foreach ( $public_forums as $id=>$name ) {
			$display .= '<option value="' . $id .'">' . $id . ' - ' . $name . '</option>';
		}

		$display .= '</select>';
		$display .= '<input type="submit" name="submit_forum_selection" id="submit_forum_selection" class="button button-primary" value="Submit" />';
		$display .= '</form>';

		return $display;
	}

	public function display_topic_mapping_form( $id ) {
		if ( is_null($this->forum) ) {
			return '<span class="alert">Could not find UserEcho Files.</span>';
		}

		$auth_nonce = wp_create_nonce( 'jco_topic_mapping_nonce');

		$display = '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post" id="jco_userecho2bbpress_topic_mapping">';
		$display .= '<input type="hidden" name="action" value="jco_topic_mapping" />';
		$display .= '<input type="hidden" name="jco_topic_mapping_nonce" value="' . $auth_nonce .'" />';
		$display .= '<input type="hidden" name="jco[forum_id]" value="' . $id . '" />';
		$display .= '<table><tr><th>ID</th><th>Name</th><th>Topics</th><th>Import into</th></tr>';
		foreach ( $this->forum->get_forum_categories( $id ) as $category ) {
			$display .= '<tr><td>' . $category['id'] . '</td><td>' . $category['name'] . '</td><td>' . $category['topic_count'] . '</td><td>' . $this->bbpress_forum_picker( $category['id'] ) . '</td></tr>';
		}
		$display .= '<tr><td>N/A</td><td>Uncategorized</td><td>' . $this->forum->count_uncategorized_topics( $id ) . '</td><td>' . $this->bbpress_forum_picker() . '</td></tr>';
		$display .= '</table>';
		$display .= '<input type="submit" name="submit_topic_mapping" id="submit_topic_mapping" class="button button-primary" value="Submit" />';
		$display .= '</form>';
		//return $this->forum->get_forum_categories($id); 		//debug info
		return $display;
	}

	/**
 	* Display a preview of an imported post
 	*
 	* @param  array [topic_id, category_map]
 	* @return string html to display preview of imported content
 	*/
	public function display_preview_form( $args ) {
		$forum_id = $args['forum_id'];
		$ue_topic_id = $this->forum->get_preview_topic( $forum_id );
		$category_map = $args['category_map'];
		$topic_id = $this->insert_bbp_topic( $ue_topic_id, $category_map );

		if ( $topic_id ){
			$permalink = get_permalink( $topic_id );
			$display .= '<a href="' . $permalink . '">' . $permalink . '</a>';
			$display .= '<iframe src="/?p=' . $topic_id . '" height="600" width="1200"></iframe>';
		} else {
			$display = '<div class="notice notice-error"><p>Topic insertion failed</p></div>';
		}
		return $display;
	}

	/**
	* Sanitize input and redirect to step 2 of the import process
	*
	*/
	public function handle_forum_selector(){
		if ( isset( $_POST['jco_select_forum_nonce'] ) && wp_verify_nonce( $_POST['jco_select_forum_nonce'], 'jco_select_forum_nonce') ){
			$forum_id = sanitize_text_field( $_POST['jco']['forum_id'] );

			wp_safe_redirect( esc_url_raw( add_query_arg( array(
				'jco' => array(
					'forum_id' => $forum_id,
					'step' => 2,
				),
			), admin_url('admin.php?page=' . $this->plugin_name )
		)));
		exit;
		} else {
			wp_die( 'Invalid Nonce' );
		}
	}

	/**
	 * Sanitize input and redirect to step 3 of the import process.
	 *
	 */
	public function handle_topic_mapping(){
		if ( isset( $_POST['jco_topic_mapping_nonce'] ) && wp_verify_nonce( $_POST['jco_topic_mapping_nonce'], 'jco_topic_mapping_nonce' ) ) {
			$category_map = array();
			$forum_id = sanitize_text_field( $_POST['jco']['forum_id'] );
			unset( $_POST['jco']['forum_id'] );
			foreach ( $_POST['jco'] as $from_id => $to_id ){
					$category_map[sanitize_text_field($from_id)] = sanitize_text_field($to_id);
			}

			wp_safe_redirect( esc_url_raw( add_query_arg( array(
				'jco' => array(
					'forum_id' => $forum_id,
					'step' => 3,
					'category_map' => $category_map,
				),
			), admin_url( 'admin.php?page=' . $this->plugin_name )
		)));
		} else {
			wp_die( 'Invalid Nonce' );
		}
	}

	public function bbpress_forum_picker( $from_id = 0 ) {
		$bbforums = get_posts( array( 'numberposts' => -1, 'post_type' => 'forum'));
		$picker = '<select required class="jco-bbpicker" name="jco[' . $from_id . ']"><option value="">Select One</option>';
		foreach ( $bbforums as $bbforum ) {
			$picker .= '<option value="' . $bbforum->ID . '">' . $bbforum->post_title . '</option>';
		}
		$picker .= '</select>';

		return $picker;
	}

	public function insert_bbp_topic( $ue_topic_id, $category_map ) {
		$forum_id = $category_map[$this->forum->get_topic_category( $ue_topic_id )];
		$post_title = $this->forum->get_topic_title( $ue_topic_id );
		$post_content = $this->forum->get_topic_content( $ue_topic_id );
		if ( ! $post_content ) {
			$post_content = $post_title;
		}

		$post_content = $this->import_and_replace_media( $post_content );
		$post_date = $this->forum->get_topic_date( $ue_topic_id );
		$reply_count = $this->forum->get_topic_reply_count( $ue_topic_id );
		$post_name = $this->forum->get_topic_slug( $ue_topic_id );

		$topic_data = array(
			'post_author' => 0,
			'post_parent' => $forum_id, // forum ID
			'post_password' => '',
			'post_name' => $post_name,
			'post_content' => $post_content,
			'post_title' => $post_title,
			'post_date_gmt' => $post_date,
			'comment_status' => 'closed',
			'menu_order' => 0,
		);

		$topic_meta = array(
			'forum_id' => $forum_id,
			'voice_count' => 1,
			'reply_count' => $reply_count,
			'reply_count_hidden' => 0,
			'last_reply_id' => 0,
		);

		$topic_id = bbp_insert_topic( $topic_data, $topic_meta );

		if ( ! $topic_id ) {
			return false;
		}

		$this->update_anonymous_topic_user( $ue_topic_id, $topic_id, 'topic' );

		$replies = $this->insert_all_replies( $ue_topic_id, $topic_id );

		return $topic_id;
	}

	public function insert_all_replies( $ue_topic_id, $bbp_topic_id ){
		$replies = $this->forum->get_all_replies( $ue_topic_id );
		$reply_ids = array();

		foreach ( $replies as $reply ) {
			$reply_ids[] = $this->insert_bbp_reply( $bbp_topic_id, $reply );
		}
		return $reply_ids;
	}

	public function insert_bbp_reply( $bbp_topic_id, $ue_reply_id ) {
		$reply_privacy = $this->forum->get_reply_privacy( $ue_reply_id );
		if ( ! $reply_privacy == 'PUBLIC' ) {
			return false;
		}
		$post_content = $this->forum->get_reply_content( $ue_reply_id );
		if ( ! $post_content ) {
			return false;
		}
		$post_content = $this->import_and_replace_media( $post_content );
		$post_date = $this->forum->get_reply_date( $ue_reply_id );

		$reply_data = array(
			'post_author' => 0,
			'post_parent' => $bbp_topic_id, // forum ID
			'post_password' => '',
			'post_content' => $post_content,
			'post_date_gmt' => $post_date,
			'comment_status' => 'closed',
		);
		$reply_id = bbp_insert_reply( $reply_data );

		if ( ! $reply_id ) {
			return false;
		}

		$this->update_anonymous_reply_user( $ue_reply_id, $reply_id, 'reply' );

		return $reply_id;
	}

	public function update_anonymous_topic_user( $ue_topic_id, $bbp_topic_id, $post_type ) {
		$anonymous_name = $this->forum->get_topic_author_name( $ue_topic_id );
		$anonymous_email = $this->forum->get_topic_author_email( $ue_topic_id );
		$anonymous_website = $this->forum->get_topic_author_website( $ue_topic_id );

		$anonymous_data = array(
			'bbp_anonymous_name' => $anonymous_name,
			'bbp_anonymous_email' => $anonymous_email,
			'bbp_anonymous_website' => $anonymous_website
		);

		bbp_update_anonymous_post_author( $bbp_topic_id, $anonymous_data, $post_type );
		return;
	}

	public function update_anonymous_reply_user( $ue_reply_id, $bbp_topic_id, $post_type ) {
		$anonymous_name = $this->forum->get_reply_author_name( $ue_reply_id );
		$anonymous_email = $this->forum->get_reply_author_email( $ue_reply_id );
		$anonymous_website = $this->forum->get_reply_author_website( $ue_reply_id );

		$anonymous_data = array(
			'bbp_anonymous_name' => $anonymous_name,
			'bbp_anonymous_email' => $anonymous_email,
			'bbp_anonymous_website' => $anonymous_website
		);

		bbp_update_anonymous_post_author( $bbp_topic_id, $anonymous_data, $post_type );
		return;
	}

	public function import_and_replace_media( $content ){
		// TODO:
		return $content;
	}
}
