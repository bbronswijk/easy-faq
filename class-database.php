<?php

class Database {
	
	public function setup_database(){
		global $wpdb;
		
		$option = array('default');
		
		// add_option only gets called when the option not allready exists
		add_option('faq_item_categories',$option);
		
		
		// create table if it not already exists
		if($wpdb->get_var("SHOW TABLES LIKE '".$this->database_name.'faq'."'") != $this->database_name.'faq') {
			$sql = "CREATE TABLE ".$this->database_name.'faq'." (
					  id int NOT NULL AUTO_INCREMENT,	
					  title text,
					  text text, 
					  position int,
					  category text, 				  		  
					  UNIQUE KEY id (id) );";	
			dbDelta( $sql );
		}	
		
		// store database version in option
		update_option( 'faq_db_version', $this->database_version );
	}
	
}
