<?php
// Display content on page using shortcode
class FaqShortcode extends EasyFaq
{
	public function __construct()
	{
		add_shortcode($this->shortcode_tag, array($this,'output') );
	} 

	// gets called in shortcode and also gets called in the faq class
	function get_list($cat = '%')
	{
		global $wpdb;    
		$q_s = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix . "faq WHERE category LIKE '" . $cat . "' ORDER BY position ASC;", ARRAY_A);	
		return stripslashes_deep($q_s);
	}
 
	public function output($atts)
	{	
		wp_enqueue_style('faq_style');
		wp_enqueue_script('faq_script');

		$attr = shortcode_atts( array(
			'category' => 'all',
			'collapse' => false,
		), $atts );
		
		$category = $attr['category']; 
		$collapse = $attr['collapse']; 
		
		if( $category == 'all' )			
			$list_items = $this->get_list(); // if no category is supplied, load all questions
		else
			$list_items = $this->get_list($attr['category']);
			
		$html = '';
				
		if ( $collapse == true  ) $html .= '<div class="faq-collapse"><div class="faq-cat">'.$category .'</div>';
		
		$html .= '<div class="faq">';	
			foreach($list_items as $n => $item){
				$html .= '<div class="faq-item">';
					$html .= '<div class="question">'.$item['title'].'</div>';
					$html .= '<div class="answer">'.$item['text'].'</div>';
				$html .= '</div>';
			}	
		$html .= '</div>';
		
		if ( $collapse == true ) $html .= '</div>';
		
		return $html;
	} 
 
}
