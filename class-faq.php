<?php

class Faq extends Easy_Faq_Admin{

	public function __construct(){}
	
	// load all faq
	public function get_faq($cat = '%'){
		global $wpdb;   
		$q_s = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.'faq'." WHERE category LIKE '".$cat."' ORDER BY position ASC;",ARRAY_A);		
		
		return stripslashes_deep($q_s);
	}

	// add item to database
	public function addFaq() {
		global $wpdb;
		global $cur_category;
				
		// check if ajax call is legit
		if (!isset($_POST['faq_nonce']) || !wp_verify_nonce($_POST['faq_nonce'], 'faq_nonce')) {
			die('permission check denied');
		}
		
		$currentNumber = $this->CountFaq();
		
		$numberFaq = $currentNumber + 1;
		
		$wpdb -> insert($wpdb -> prefix . 'faq', array('title' => 'New Item', 'text' => '', 'position' => $numberFaq, 'category' => $cur_category));
		
		// return id of inserted item
		echo $wpdb -> insert_id;

		die();
	}

	public function CountFaq() {

		global $wpdb;
		global $cur_category;

		$results = $wpdb -> get_results("SELECT * FROM " . $wpdb -> prefix . 'faq' . " WHERE category='" . $cur_category . "';", ARRAY_A);
		
		return count($results);

	}

	// remove item from database
	public function removeFaq() {
		
		global $wpdb;

		// check if ajax call is legit
		if (!isset($_POST['faq_nonce']) || !wp_verify_nonce($_POST['faq_nonce'], 'faq_nonce')) {
			die('permission check denied');
		}

		$id = $_POST['id'];

		$result = $wpdb -> query('DELETE FROM ' . $wpdb -> prefix . 'faq' . ' WHERE id="' . $id . '" ;');

		die();
	}

	// saving fields to database, niet via ajax omdat je veel losse velden moet posten
	public function saveFaq() {
		
		global $wpdb;

		foreach ($_POST as $name => $value) {

			// update title
			if (substr($name, 0, 6) == "title_") {
				$this_id = substr($name, 6);
				$wpdb -> update($wpdb -> prefix . 'faq', array('title' => $value), array('id' => $this_id));
			}

			// update text
			if (substr($name, 0, 5) == "text_") {
				$this_id = substr($name, 5);
				$wpdb -> update($wpdb -> prefix . 'faq', array('text' => $value), array('id' => $this_id));
			}

			// update category
			if (substr($name, 0, 17) == "category_options_") {
				$this_id = substr($name, 17);
				$wpdb -> update($wpdb -> prefix . 'faq', array('category' => $value), array('id' => $this_id));
			}
		
		}

		$wpdb -> flush();

	}

	// sorting items in database
	public function sortFaq() {
		global $wpdb;

		// check if ajax call is legit
		if (!isset($_POST['faq_nonce']) || !wp_verify_nonce($_POST['faq_nonce'], 'faq_nonce')) {
			die('permission check denied');
		}

		$id = $_POST['id'];

		$index = $_POST['index'];

		$wpdb -> update($wpdb -> prefix . 'faq', array('position' => $index), array('id' => $id));

		die();
	}

}
