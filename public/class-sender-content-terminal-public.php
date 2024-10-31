<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Sender_Content_Terminal_Public {

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

    //Canonical val
    private $canonical;

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
        $this->canonical = null;
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
		 * defined in Sender_Content_Terminal_ALoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sender_Content_Terminal_ALoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sender_Content_Terminal_ALoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sender_Content_Terminal_ALoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );

    }

    public function check_canonical()
    {
        if (is_single()) {
            global $post;

            $rss_pi_source_url = get_post_meta($post->ID, 'rss_pi_source_url', false);

            if(!empty($rss_pi_source_url)){
                $this->canonical = $rss_pi_source_url[0];
            }

            $ct_canonical = get_post_meta($post->ID, 'ct_canonical', false);

            if (!empty($ct_canonical)) {
                $this->canonical = $ct_canonical[0];
            }

            if ($this->canonical) {
                remove_action('wp_head', 'rel_canonical');
                add_action('wp_head', array($this, 'set_canonoical_noindex'));
            }
        }
    }

    public function set_canonoical_noindex()
    {
        echo "\n<meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\" />";
        echo "\n<link rel=\"canonical\" href=\"" . $this->canonical . "\">";
        echo "\n<meta name=\"robots\" content=\"noindex\">";
    }

	public function router()
	{
		if ($_SERVER["REQUEST_URI"] == '/sender-content-terminal/post-importer' && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$json = file_get_contents('php://input');
            $request = json_decode($json, TRUE);

			if(!$request['token']){
				wp_send_json_error('Request is missing token', 401);
            }

            if ($request['model'] == 'healthCheck') {
                $this->{$request['model']}($request);
            }

			if(get_option( 'sender_content_terminal_token') != $request['token']){
				wp_send_json_error('Token does not match please set the correct token in wordpress.', 401);
			}

			if (!$request['model']) {
				wp_send_json_error('Request is missing model', 405);
			}

			if (!method_exists($this, $request['model'])) {
				wp_send_json_error($request['model'] .' does not exist', 405);
			}

			$this->{$request['model']}($request);

			exit();
		}
	}

    public function postImport($request)
    {
        $assets = $request['payload'];
        $sent_created = array();
        $sent_failed = array();
        $wp_created = array();

        foreach ($assets as $key => $asset) {
            $slug = preg_replace('/-\d+$/', '', end(explode('/', $asset['slug'])));
            $post = array();

            $existing = get_posts(array(
                'name'        => $slug,
                'post_type'   => 'post',
                'numberposts' => 1
            ));

            $post['post_name']      = $slug;
            $post['post_title']     = strip_tags($asset['title']);
            $post['post_excerpt']   = htmlspecialchars_decode($asset['abstract']);
            $post['post_content']   = htmlspecialchars_decode($asset['content']);
            $post['post_status']    = $asset['wp_status'];
            $post['post_author']    = $asset['wp_author_id'];
            $post['post_category']  = $asset['wp_category_id'];
            $post['post_date']      = $this->checkIfDateIsFuture($asset['create_date']);
            $post['post_modified']  = $this->checkIfDateIsFuture($asset['last_modified_date']);
            $post['comment_status'] = ($asset['wp_comments']) ? 'open' : 'closed';
            $post['meta_input']     = array('ct_canonical' => $asset['canonical'], 'ct_org' => $asset['org'], 'ct_article_id' => $asset['id']);

            if ($asset['wp_replace_if_found']) {
                if ($existing) {
                    $post['ID'] = $existing[0]->ID;
                }
            }

            if (!$asset['wp_replace_if_found'] && isset($existing[0]) && $existing[0]->ID) {
                $insert = $existing[0]->ID;
            }else{
                $insert = wp_insert_post($post, $request['debug']);
            }

            if (!$insert->errors && $insert) {
                $sent_created[] = (int) $asset['id'];
                $wp_created[] = array('asset_id' => (int) $asset['id'], 'wp_id' => $insert, 'link' => wp_get_shortlink($insert));
            } else {
                if($request['debug']){
                    $sent_failed[] = array('id' => (int) $asset['id'], 'error' => $insert);
                }else{
                    $sent_failed[] = (int) $asset['id'];
                }
            }
        }

        $output = array(
            'total_sent' => count($assets),
            'total_created' => count($sent_created),
            'total_failed' => count($sent_failed),
            'created_asset_ids' => $sent_created,
            'failed_asset_ids' => $sent_failed,
            'created_wp_ids' => $wp_created,
        );

        if (count($sent_failed)) {
            wp_send_json_error($output, 409);
        } else {
            wp_send_json_success($output, 200);
        }
        exit();
    }

    private function checkIfDateIsFuture($date)
    {
        $posted_date = new DateTime($date);
        $current_date = new DateTime();

        if ($posted_date > $current_date)
        {
            return null;
        }
        return $date;
    }

    public function healthCheck($request)
    {

        if ( ! function_exists( 'is_plugin_active' ) ){
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        $author_roles = get_userdata($request['wp_author_id'])->roles;
        $roles = array('administrator', 'editor', 'author', 'contributor');
        $author_exist = ( count(array_intersect($roles, $author_roles)) && isset($request['wp_author_id']) ) ? true : false;

        $categories = array_keys($this->getCategoriesArray());
        $categories_exist = ( count(array_intersect($categories, $request['wp_category_id'])) == count($request['wp_category_id']) ) ? true : false;

        $output = array(
            'plugin_name' => $this->plugin_name,
            'plugin_version' => $this->version,
            'wp_site_url' => get_site_url(),
            'wp_author_exist' => $author_exist,
            'wp_category_exist' => $categories_exist,
            'accepted_terms' => (get_option('sender_content_terminal_accepted_terms', false)) ? true : false,
            'token_is_set_in_wp' => (get_option('sender_content_terminal_token', false)) ? true : false,
            'token_is_correct' => (get_option('sender_content_terminal_token') == $request['token']) ? true : false,
            'php_version' => phpversion(),
            'php_upload_max_filesize' => ini_get('upload_max_filesize'),
            'php_post_max_size' => ini_get('post_max_size'),
            'legacy_post_imported_is_active' => is_plugin_active('elderlawanswers-post-importer/index.php')
        );
        wp_send_json_success($output, 200);
    }

    public function getAuthors($request)
    {
        $output = array();

        $users = get_users();
        $roles = array('administrator', 'editor', 'author', 'contributor');

        foreach($users as $user){
            $user_meta = get_userdata($user->id);
            $user_roles = $user_meta->roles;
            if (count(array_intersect($roles, $user_roles))) {
                $output[] = array('id' => $user->id, 'name' => $user_meta->first_name.' '.$user_meta->last_name, 'roles' => $user_roles);
            }
        }

        wp_send_json_success($output, 200);
    }

    public function getCategories($request)
    {
        wp_send_json_success($this->getCategoriesArray());
    }


    public function getCategoriesArray()
    {
        $output = array();

        $categories = get_categories(array(
            'hide_empty' => 0
        ));

        foreach ($categories as $category) {
            $output[$category->cat_ID] = array('id' => $category->cat_ID, 'name' => $category->name, 'slug' => $category->slug, 'parent_id' => $category->parent);
        }

        return $output;
    }

}
