/**
 * @author Bram Bronswijk
 */

jQuery(document).ready(function($){
	
	$collapse = $('.faq-cat');
    
    $collapse.on('click',function(){
    	$(this).parent().toggleClass('open');
    });

    $item = $('.faq-item .question');
    
    $item.on('click',function(){
    	$(this).parent().toggleClass('open');
    });
			
 
});