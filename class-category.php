<?php
class Category
{	
	public $all;
		
	public function __construct(){
		global $cur_category;
		$cur_category = $this->current();
	}
	
	// get current category from URL
	public function current()
	{
		if(empty($_GET['category'])){
			$option = $this->getOption(); 			
			return reset($option); // if get is empty -> return first of the options
		} else{
			return $_GET['category'];		
		}	
	}
	
	public function getOption()
	{
		return get_option('faq_item_categories');
	}
	
	public function get()
	{
		$categories = '';
		
		foreach ($this->getOption() as $option) {
			$categories .= $option.',';
		}
		
		echo $categories;
		
		die();
	}
	
	public function getCategoryOptions()
	{
		if( $this->getOption() ){
			
			$categories = $this->getOption();
			
			global $cur_category;			
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

	function get_list($cat = '%'){
		global $wpdb;    
		$q_s = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix . "faq WHERE category LIKE '" . $cat . "' ORDER BY position ASC;", ARRAY_A);	
		return stripslashes_deep($q_s);
	}
	
	// add category from form at admin page -> ajax 
	public function add()
	{		
		$option = $this->getOption();
		
		if( empty( $option ) ){
			$categories = array();
		} else{
			$categories = $option;
		}
		
		$new_category = $_POST['new_category'];		
		array_push( $categories, $new_category ); 		
		update_option( 'faq_item_categories', $categories );
		
		die();
	} 
    
	// delete category
	public function delete(){
		global $wpdb;
		
		$option = $this->getOption();
		if( empty( $option ) ){
			return false;
		} else{
			$categories = $option;
		}
		
		$delete_category = $_POST['category'];		
		$categories  = array_diff($categories , array($delete_category));
		
		update_option( 'faq_item_categories', $categories );
		$wpdb->query("DELETE FROM ".$wpdb->prefix.'faq'." WHERE category='$delete_category' ", ARRAY_A);
				
		die();
	} 
	
}
