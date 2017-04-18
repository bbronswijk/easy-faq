<?php
/**
 * Plugin Name: Easy FAQ
 * Description: Een plugin om een faq op te stellen. De FAQ kan worden weergegeven door gebruik te maken van een shortcode.
 * Version: 1.0
 * Author: B Bronswijk
 */

if (!defined('WPINC'))
	die ;

define('PLUGIN_BASE', plugin_dir_path(__FILE__));

$base = plugin_dir_path(__FILE__);

class Easy_Faq{
	
	protected $database;
	protected $plugin_slug;
	static public $plugin_path;
	protected $version;
	public $database_name;
	protected $database_version;
	public $shortcode_tag = 'faq';   
	
	public function __construct(){
		// set variables
		$this->plugin_slug = 'easy-faq';
        $this->version = '1.0';
 		$this->plugin_path = PLUGIN_BASE;
		global $wp;

		// fire methods
        $this->load_dependencies();
		
		// set variables database
		global $wpdb;
		$this->database_name = $wpdb->prefix.'faq';
		$this->database = new Database();
		$this->database_version = '1.0';
		
		// setup database if necessary 
		if(get_option('faq_db_version') != $this->database_version ) $this->database->setup_database();
		
		$this->define_hooks();
		$this->register_shortcodes();

	}
	
	// loads all the external files
	public function load_dependencies(){
		// upgrade.php necessary for nonces
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		require_once $this->plugin_path.'class-easy-faq-admin.php';
		require_once $this->plugin_path.'class-faq-shortcode.php';
		require_once $this->plugin_path.'class-database.php';
		require_once $this->plugin_path.'class-faq.php';
		require_once $this->plugin_path.'class-category.php';
	}
	
	// collection of all hooks
	public function define_hooks(){
		$admin_page = new Easy_Faq_Admin();
		add_action('admin_menu', array( $admin_page, 'create_faq_admin_page') );		
		add_action('admin_enqueue_scripts', array( $admin_page, 'enqueue_admin_scripts') );
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_faq_scripts_frontend') );
		add_action('admin_head', array( $this, 'admin_head') );
		
	}
	
	// admin head
	function admin_head() {
		if ( is_admin() ){
	        // check user permissions
	        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
	            return;
	        }
	 
	        // check if WYSIWYG is enabled
	        if ( 'true' == get_user_option( 'rich_editing' ) ) {
	            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
	            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
	        }
		}
    }
	
	// embed plugins	
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->shortcode_tag] = plugins_url( 'js/mce-button.js', __FILE__);
        return $plugin_array;
    }

    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }
	

	// add enqueue the styles and scripts at the frontend
	public function enqueue_faq_scripts_frontend(){		
		wp_enqueue_style( 'faq_style', '/wp-content/plugins/easy-faq/css/style.css');
		wp_enqueue_script( 'faq_script', '/wp-content/plugins/easy-faq/js/front_script.js', array('jquery') );
	}
	
	// register the [faq] shortcode 
	private function register_shortcodes(){
		$shortcode = new Faq_Shortcode();		
		add_shortcode('faq',array( $shortcode,'output_faq') );
	}
	
	function get_list($cat = '%'){
		// this also gets called in the faq class
		global $wpdb;    
		$q_s = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'faq'." WHERE category LIKE '".$cat."' ORDER BY position ASC;",ARRAY_A);
		
		return stripslashes_deep($q_s);
	}
	
}    

$easy_fag_plugin = new Easy_Faq();

