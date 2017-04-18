<?php
class Category {
	
	public $all;
		
	public function __construct(){
		global $cur_category;
		$cur_category = $this->getCurrentCategory();
	}
	
	// get current category from URL
	public function getCurrentCategory(){
		if(empty($_GET['category'])){
			$option = $this->getListCategories();
			// if get is empty -> return first of the options
			return reset($option);
		}
		else{
			return $_GET['category'];		
		}	
	}
	
	public function getListCategories(){
		return get_option('faq_item_categories');
	}
	
	public function get(){
		$categories = '';
		
		foreach ($this->getListCategories() as $option) {
			$categories .= $option.',';
		}
		
		echo $categories;
		
		die();
	}
	
	public function getCategoryOptions(){
		if( $this->getListCategories() ){
			
			$categories = $this->getListCategories();
			
			global $cur_category;											
			// get current category		
			$selected = 'selected="selected"';
			
			if(!empty($categories)){
				foreach ($categories as $option) { 
					?> <option value="<?= $option ?>" <?php if( $option == $cur_category ){  echo $selected; } ?> ><?= $option ?></option> <?php 
				}	
			}				
		} else{ 
			?> <option value="default" selected="selected" >default</option> <?php
		}	
	}
	
	// add category from form at admin page -> ajax 
	public function add(){
		
		$option = $this->getListCategories();
		
		if( empty( $option ) ){
			$categories = array();
		} else{
			$categories = $option;
		}
		
		// push new category to options
		$new_category = $_POST['new_category'];
		
		array_push( $categories, $new_category ); 
		
		update_option( 'faq_item_categories', $categories );
		
		die();
	} 
    
	// delete category
	public function delete(){
		global $wpdb;
		
		$option = $this->getListCategories();
		if( empty( $option ) ){
			return false;
		} else{
			$categories = $option;
		}
		
		$delete_category = $_POST['category'];
		
		$categories  = array_diff($categories , array($delete_category));
		
		update_option( 'faq_item_categories', $categories );
		$wpdb->query("DELETE FROM ".$wpdb->prefix.'faq'." WHERE category='$delete_category' ",ARRAY_A);
		
		
		die();
	} 
	
}
