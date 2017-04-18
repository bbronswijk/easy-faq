
(function($) {
	tinymce.PluginManager.add('faq', function(editor, url) {
		
		var sh_tag = 'faq';

		//add popup
		editor.addCommand('faq_popup', function(ui, v) {
			
			if (v.type)
				type = v.type;
				
			data = {
				action : 'get_categories'
			};
			
			$.post(ajaxurl,data,function(response){					
					
					response = response.split(',');
					response.splice(-1, 1);
					
					var values = [{
								text: 'Display all categories',
								value: 'all'
							}];
								
					$.each(response, function(i, val){						
							values.push({
								text: val,
								value: val
							});						
					})			
				
					//open the popup
					editor.windowManager.open({
							title : 'Faq Shortcode',
							body : [
		                    {
			                    type   : 'container',
			                    multiline: true,
			                    html   : 'Plaats een faq die u <strong><a href="admin.php?page=faq_page">hier</a></strong> heeft aangemaakt op een pagina.</br>Selecteer hier de categorie van de faq die u wilt weergeven.</br>', 
			                    minWidth: 400
			                },{
								type : 'listbox',
								name : 'category',
								label : 'Category',
								value : type,
								'values' : values,
								tooltip : 'Select the category to display'
							},{
			                    type   : 'container',
			                    multiline: true,
			                    html   : '</br>Wanneer u collapse selecteerd wordt de lijst met vragen per</br> categorie weergegeven.</br>', 
			                    minWidth: 400
			                },{
								type : 'listbox',
								name : 'collapse',
								label : 'Collapse Category',
								value : type,
								'values' : [{
									text : 'collapse',
									value : true
								}, {
									text : 'show complete list',
									value : false
								}],
								tooltip : 'Collapse category list of faq'
							},{
			                    type   : 'container',
			                    multiline: true,
			                    html   : '</br><i>* U kunt de shortcode tag [ faq ] ook handmatig invoeren,</br> wordpress herkent de tag automatisch.</i>', 
			                    minWidth: 400
			                }],
							onsubmit : function(e) {
								//start the shortcode tag
								var shortcode_str = '[' + sh_tag;
								
								//check category of faq
								if ( typeof e.data.category != 'undefined' && e.data.category.length)
									shortcode_str += ' category="' + e.data.category + '"';
								//check if faq needs to be collapsed
								if ( typeof e.data.collapse != 'undefined' && e.data.collapse == true)
									shortcode_str += ' collapse="' + e.data.collapse + '"';
								
								//add panel content
								shortcode_str += ']';
											
								//insert shortcode to tinymce
								editor.insertContent(shortcode_str);
							}
						});
				
			}); 	
				

		});

		//add button to tiny MCE 
		editor.addButton('faq', {
			icon : 'faq',
			tooltip : 'FAQ',
			onclick : function() {
				editor.execCommand('faq_popup', '', {
					header : '',
					footer : '',
					type : 'default',
					content : ''
				});
			}
		});

	});
})(jQuery); 