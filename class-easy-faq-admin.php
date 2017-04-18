<?php

class Easy_Faq_Admin extends Easy_Faq{
	
	private $faq_setting_page;
	protected $faq;
	protected $category;
	
	public function __construct(){		
		$this->category = new Category();
		$this->faq = new Faq();	
		$this->define_hooks();
	}
	
	public function create_faq_admin_page(){
		$this->faq_settings_page = add_menu_page('FAQ', 'FAQ', 'publish_posts', 'faq_page', array( $this, 'faq_page' ),'dashicons-sos','17.25');
	}
	
	public function faq_page(){		
		if ( !current_user_can( 'publish_posts' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			
		global $cur_category;
		
		// check if the form is submitted/saved -->
		if( isset($_POST['faq_nonce']) && wp_verify_nonce($_POST['faq_nonce'], 'faq_nonce') ){ 
			$this->faq->saveFaq(); 
			echo '<div class="updated"><p><strong>Settings saved</strong></p></div>';
		}
		
		$categories = $this->category->getListCategories();
		$list_items = $this->faq->get_faq($cur_category);
		
		require_once parent::$plugin_path . 'partials/admin_page.php';
	}
			
	// collection of all ajax calls
	public function define_hooks(){
		// ajax action needs to be called here
		add_action('wp_ajax_sort_faq_db', array( $this->faq, 'sortFaq') );
		add_action('wp_ajax_remove_faq_db', array( $this->faq, 'removeFaq') );
		//add_action('wp_ajax_remove_faq_db', check );
		add_action('wp_ajax_add_faq_db', array( $this->faq, 'addFaq') );		
		add_action('wp_ajax_delete_faq_category', array( $this->category, 'delete'));
		add_action('wp_ajax_add_faq_category', array( $this->category, 'add'));
		add_action('wp_ajax_get_categories', array( $this->category, 'get'));
		
	}
		
	public function enqueue_admin_scripts($hook){
		// enqueu admin style
		wp_enqueue_style( 'faq_tiny_mce', '/wp-content/plugins/easy-faq/css/tiny_mce.css');	
		
		// only load the scripts at the specific plugin page
		if( $hook != $this->faq_settings_page ) return;
		
		// enqueu admin style
		wp_enqueue_style( 'faq_admin_style', '/wp-content/plugins/easy-faq/css/admin_styles.css');		
		
		// load scripts
		wp_enqueue_script( 'jquery-ui-draggable', array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable') );
		wp_enqueue_media();
	  	wp_enqueue_script( 'admin_faq_script', '/wp-content/plugins/easy-faq/js/script.js', array('jquery'), false, true );
		
		// create nonce
		wp_localize_script('admin_faq_script','faq_vars',array('ajaxurl' => admin_url('admin-ajax.php'),'faq_nonce' => wp_create_nonce('faq_nonce')));

	}
	
	
	
}
