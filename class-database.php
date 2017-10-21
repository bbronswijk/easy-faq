<?php

class Database
{
	public function __contstructor()
	{
		// setup database if necessary 
		if( get_option('faq_db_version') != $this->database_version ) $this->database->setup();
	}
	
	public function setup()
	{
		global $wpdb;

		// add_option only gets called when the option not already exists
		add_option('faq_item_categories', array('default'));
				
		// create table if it not already exists
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."faq'") !== $wpdb->prefix.'faq') {

			$sql = "CREATE TABLE ".$wpdb->prefix."faq (
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
