<?php
// Display content on page using shortcode
class Faq_Shortcode extends Easy_Faq{
	
	public function __construct(){} 
 
	public function output_faq($atts) {
	
		$attr = shortcode_atts( array(
			'category' => 'all',
			'collapse' => false,
		), $atts );
		
		$category = $attr['category']; 
		$collapse = $attr['collapse']; 
		
		if( $category == 'all' )
			// if no category is supplied, load all questions
			$list_items = parent::get_list();
		else
			$list_items = parent::get_list($attr['category']);
			
		ob_start();
				 	
		if ( $collapse == true  ) echo '<div class="faq-collapse"><div class="faq-cat">'.$category .'</div>';
		
		echo '<div class="faq">';	
			foreach($list_items as $n => $item){
				echo '<div class="faq-item">';
					echo '<div class="question">'.$item['title'].'</div>';
					echo '<div class="answer">'.$item['text'].'</div>';
				echo '</div>';
			}	
		echo '</div>';
		
		if ( $collapse == true ) echo '</div>';
		
		
		$output_string = ob_get_contents();
		ob_end_clean();
	
		return $output_string;
	} 
 

}
