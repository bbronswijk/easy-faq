<?php

		
class EasyFaqAdmin extends EasyFaq
{	
	private $menuPage;
	protected $faq;
	protected $category;
	
	public function __construct()
	{	
		$this->category = new Category();
		$this->faq = new Faq();	
		$this->ajax_calls();
	}
	
	public function create()
	{
		$this->menuPage = add_menu_page(
			'FAQ', // page title
			'FAQ', // menu title
			'publish_posts', // capabilities
			'faq_page', // meny slug
			array( $this, 'admin_page' ),
			'dashicons-sos',
			'17.25'
		);
	}
	
	public function admin_page()
	{		
		if ( !current_user_can( 'publish_posts' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			
		global $cur_category;
		
		// check if the form is submitted/saved -->
		if( isset($_POST['faq_nonce']) && wp_verify_nonce($_POST['faq_nonce'], 'faq_nonce') ){ 
			$this->faq->save(); 
			echo '<div class="updated"><p><strong>Settings saved</strong></p></div>';
		}
		
		$categories = $this->category->getOption();
		$list_items = $this->faq->get($cur_category);

		global $wpdb;

		echo $this->database_name;

		require_once 'partials/admin_page.php';
	}
			
	// collection of all ajax calls
	public function ajax_calls()
	{
		add_action('wp_ajax_sort_faq_db', array( $this->faq, 'sort'));
		add_action('wp_ajax_remove_faq_db', array( $this->faq, 'delete'));
		add_action('wp_ajax_add_faq_db', array( $this->faq, 'add'));		

		add_action('wp_ajax_delete_faq_category', array( $this->category, 'delete'));
		add_action('wp_ajax_add_faq_category', array( $this->category, 'add'));
		add_action('wp_ajax_get_categories', array( $this->category, 'get'));
		
	}
		
	public function admin_scripts($hook)
	{
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) wp_enqueue_style( 'faq_tiny_mce', plugins_url($this->plugin_slug.'/css/tiny_mce.css'));	
		
		if( $hook === $this->menuPage ){
			wp_enqueue_media();
			wp_enqueue_style( 'faq_admin_style',plugins_url($this->plugin_slug.'/css/admin_styles.css'));		
			wp_enqueue_script( 'jquery-ui-draggable', array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable') );		
		  	wp_enqueue_script( 'admin_faq_script', plugins_url($this->plugin_slug.'/js/script.js'), array('jquery'), false, true );
			wp_localize_script('admin_faq_script','faq_vars',array('ajaxurl' => admin_url('admin-ajax.php'),'faq_nonce' => wp_create_nonce('faq_nonce')));
		}		
	}
	
	
	
}
