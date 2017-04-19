/**
 * @author Bram Bronswijk
 * 
 * updatePreview
 * sortableInit --> saveSortingFaq
 * saveSortingFaq
 * toggleEditor
 * deleteFaq
 * addFaq
 * 
 */

var faqPlugin = (function($) {
	
	// define elements
	var $faqPlugin = $('.faq-plugin');
	var $form = $faqPlugin.find('#faq-form');
	var $no_items = $faqPlugin.find('#no-items');
	var $items_container = $faqPlugin.find('.items');
	var $items = $faqPlugin.find('.item');
	var $item_preview = $items.find('.preview_item');
	var $title = $items.find('.title'); 
	var $delete_btn = $items.find('.delete_item_button');
	var $edit_btn = $items.find('.edit_item_button');
	var $add_btn = $faqPlugin.find('.extra_item');
	var $toggleCategory = $faqPlugin.find('.new_category');
	var $add_category_btn = $faqPlugin.find('.add_category_button');
	var $delete_category_btn = $faqPlugin.find('.delete_category_button');
	var $spinner = $faqPlugin.find('.spinner');
	var $qtranslate = $('.qtranxs-lang-switch');
	
	// bind events
	$form.submit(saveForm);
	$title.on('keyup', updatePreview );
	$item_preview.live('click', toggleEditor );
	$delete_btn.live('click', deleteFaq );
	$add_btn.live('click', addFaq );
	$toggleCategory.live('click',toggleNewCategory);
	$add_category_btn.live('click', addCategory);
	$delete_category_btn.live('click', deleteCategory);
	$qtranslate.live('click',toggleQTranslate);
	
	// init function
	function init(){
		sortableInit();
	}
	
	// on page load
	// hide update message after a few seconds
	var timeOut = setTimeout(function(){
	  if($('.updated').length > 0){
	    $('.updated').fadeOut(300);
	    clearTimeout(timeOut);
	  }
	}, 1500);
	
	// show spinner in corner
	function showLoader(){
		$spinner.css('visibility', 'visible');
	}
	
	function hideLoader(){
		$spinner.css('visibility', 'hidden');
	}
	
	// update preview while typing
	function updatePreview(){
		var val = $(this).val();
    	$item = $(this).closest('.item');    	
    	$item.find('.preview_content').text(val); 
	}
	
	// initiate #sortable
	function sortableInit(){
		$( "#sortable" ).sortable({
			items: ".ui-state-default",
			placeholder: "ui-state-highlight",
			update: function( event, ui ) {
				saveSortingFaq();
			}
	    });
	}
	
	// Save sorting faq
	function saveSortingFaq(){
			showLoader();;   
			
			 $('.ui-state-default').each(function(){
			 	var id = $(this).attr('id').replace( /[^\d.]/g, '' );
			 	
				var index = $( '.ui-state-default' ).index(this);
				$(this).data('list', index );
				
				data = { 
					action : 'sort_faq_db',
					faq_nonce : faq_vars.faq_nonce,
					index : index,
					id: id, 
				}; $.post(ajaxurl,data,function(response){});
						
			}).promise().done( function(){ 
				// hide update spinner after a few seconds
				var timeOut = setTimeout(function(){
				  if($('.spinner').length > 0){
					  hideLoader();
				  }
				}, 200);
			} );
    }
	
	
	// delete faq items
	function deleteFaq(){		
		showLoader();	
		$item = $(this).closest('.item');
		
		var id = $item .attr('id').replace ( /[^\d.]/g, '' );
		
		data = { 
			action : 'remove_faq_db',
			faq_nonce : faq_vars.faq_nonce,
			id : id,
		};
		
		$.post(ajaxurl,data,function(response){			
			if(response === 0) return;
			$item.remove();
			hideLoader();	
		});
		
		return false;
	}
	
	function addFaq(e){	
		showLoader();
				
		// add new answer to database
		data = { 
			action : 'add_faq_db',
			faq_nonce : faq_vars.faq_nonce,
		};
		
		$.post(ajaxurl,data,function(response){
			
			if(response != 0){
				$no_items.remove();
				
				// create new item
				var id = parseInt(response); 								
				$items_container.append($('#item_default').clone().attr('id', 'item_' + id));				
				$new_item = $('#item_'+id);
								
				// set the id name and value of the items of the clone		
				$new_item.find('.preview_content').text('New item');
				$new_item.find('.title').attr('name','title_'+id);
				$new_item.find('.title').attr('value','Vraag '+id);
				$new_item.find('.text').attr('name','text_'+id);
				$new_item.find('.text').attr('value','');
				$new_item.find('select:first').attr('name','page_id_'+id);
				$new_item.find('select:first').attr('id','page_id_'+id);
				$new_item.find('.category_options').attr('name','category_options_'+id);
				
				// set number of list
				var last_number = parseInt($items.last().data('list')) + 1; 
				$new_item .data('list',last_number);
				
				hideLoader();
			}
		});
		
		return false;
	};
	

	// toggle editor 
	function toggleEditor(){
		$item = $(this).closest('.item'); 		
		$item.find('.edit_item').slideToggle();
		$item.find('.edit_item').toggleClass('active');
	}
		
	// save faq items
	// but first save sorting
	function saveForm(){
		saveSortingFaq();
		return;
	}
	
	// toggle new category box
	// show option to add category	
	function toggleNewCategory(){
		$(this).next('.add_category').slideToggle(100);
		$(this).next('.add_category').toggleClass('active');
		
	}
	
	// addCategory
	function addCategory(){
		showLoader();	
		
		var new_category = $(this).prev('input').val();
		$(this).prev('input').val('');
		
		data = {
			action : 'add_faq_category',
			faq_nonce : faq_vars.faq_nonce,
			new_category : new_category, 
		};
		$.post(ajaxurl,data,function(response)
		{
			// add new category to select
			$('select.category_options').append('<option>'+new_category+'</option>');
			$('select.select_category ').append('<option value="?page=faq_page&category='+new_category+'">'+new_category+'</option>');
			
			location = '?page=faq_page&category='+new_category;
		
			hideLoader();	
		});
	}
	
	// delete Category
	function deleteCategory(){
		if($('.select_category option').size() > 1 ){
			showLoader();
			var category = $(this).attr('id');
			
			data = {
				action : 'delete_faq_category',
				faq_nonce : faq_vars.faq_nonce,
				category : category, 
			};
			$.post(ajaxurl,data,function(response){
				
				// redirect
				location = '?page=faq_page';
				hideLoader();	
			}); 
		} else{
			alert('je moet tenminste 1 categorie hebben, maak eerste een nieuwe aan voordat je deze kunt verwijderen.');
		}
	}
	
	
	
	// toggle qtranslate
	// qtranslate-X	
	function toggleQTranslate(){
		$('.item').each(function(){
	    	$this = $(this);
	    	
	    	item_id = $this.attr('id').replace ( /[^\d.]/g, '' );
	    	
	    	title_val = $this.find('input.title').val();
	    	
	    	$this.find('.preview_content').html(title_val);    	
	    });
	}
	
	return {
		init: init
	};
	
})(jQuery);

faqPlugin.init();