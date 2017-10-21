<?php
/**
 * Plugin Name: Easy FAQ
 * Description: Een plugin om een faq op te stellen. De FAQ kan worden weergegeven door gebruik te maken van een shortcode.
 * Version: 1.0
 * Author: B Bronswijk
 */

if (!defined('WPINC')) die() ;

$easy_fag_plugin = new EasyFaq();

class EasyFaq
{	
	protected $database;
	protected $plugin_slug = 'easy-faq';
	protected $version;
	protected $database_version = '1.0';
	protected $shortcode_tag = 'faq';
	
	public function __construct()
	{
        $this->load_dependencies();

		$this->database = new Database();	
		$this->adminPage = new EasyFaqAdmin();
		$this->shortcode = new FaqShortcode();
		$this->editorButton = new EditorButton();

		add_action('admin_menu', array( $this->adminPage, 'create') );		
		add_action('admin_enqueue_scripts', array( $this->adminPage, 'admin_scripts') );
		add_action('wp_enqueue_scripts', array( $this, 'frontend_scripts') );
		add_action('admin_head', array( $this->editorButton, 'admin_head') );
	}
	
	public function load_dependencies(){
		require_once ABSPATH . 'wp-admin/includes/upgrade.php'; // upgrade.php necessary for nonces
		require_once 'class-easy-faq-admin.php';
		require_once 'class-faq-shortcode.php';
		require_once 'class-database.php';
		require_once 'class-faq.php';
		require_once 'class-category.php';
		require_once 'class-editor-button.php';
	}
	
	public function frontend_scripts(){		
		wp_register_style( 'faq_style', plugins_url($this->plugin_slug.'/css/style.css' ) );
		wp_register_script( 'faq_script', plugins_url($this->plugin_slug.'/js/front_script.js'), array('jquery') );
	}

}