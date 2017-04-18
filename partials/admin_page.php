<?php
/*
 *  partial for admin page
 */
?>
		
<div class="wrap faq-plugin">
	<h2>Frequently Asked Questions</h2>
		
	<p>Op deze pagina kunt u de FAQ. Je kunt een grote lijst aanmaken of de vragen opdelen in verschillende categorieen.</p>
	
	<select class="select_category" onchange="location = '?page=faq_page&category='+this.options[this.selectedIndex].value;">
		<?php $this->category->getCategoryOptions(); ?>
	</select>
								
	<input class="delete_category_button button" id="<?= $cur_category ?>" type="button" value="Delete this Category" />
	<input class="new_category button" type="button" value="New Category" />
	<div class="add_category">
		<input class="category_input" type="text" />
		<input class="add_category_button button-primary" type="button" value="Add Category" />					
	</div>
				
	<form class="faq-container" id="faq-form" action="" method="POST">
		<div id="sortable" class="container">
			<div class="buttons" style="border-bottom: 1px solid #ddd;">
				<!-- hidden fields -->
				<input type="hidden" name="faq_nonce" value="<?= wp_create_nonce('faq_nonce'); ?>"/>
				<!-- buttons -->
				<input type="submit" name="submit_form" class="button-primary" value="Save"/>
				<input type="submit" name="Submit" class="extra_item button-secondary" value="New item" />
				<span class="spinner"></span>
			</div>
							
			<div class="items">
				<!-- SET UP FRAME FOR CLONE ITEM --> 
				<div class="ui-state-default item" id="item_default" data-list="">	
					<div class="preview_item">
						<div class="preview_content"></div>
						<div class="edit_buttons">
							<span class="delete_item_button">Verwijder | </span>
							<span class="edit_item_button">Bewerk</span>
						</div>									
					</div> <!-- /preview_item -->
				
					<div class="edit_item">									
						<table class="inner_table">
							<tbody>
								<tr>
									<th>Vraag</th>
									<td><input class="title" type="text" size="36" value='' /></td>
								</tr>
								<tr>
									<th>Antwoord</th>
									<td><textarea class="text" type="text" size="36" ></textarea></td>
								</tr>
								<tr>
									<th>Categorie</th>
									<td>
														
										<select class="category_options">
											<?php $this->category->getCategoryOptions(); ?>
										</select>
														
										<input class="new_category button" type="button" value="New Category" />
											<div class="add_category">
												<input class="category_input" type="text" />
												<input class="add_category_button button-primary" type="button" value="Add Category" />
											</div>
									 </td>	
								</tr>
								<tr>
									<td>
										<input type="submit" name="oc_submit_form" class="button-primary" value="Save" style="margin-top: 10px;"/>
									</td>												
								</tr>
							</tbody>
						</table>
					</div> <!-- edit -->
				</div> <!-- item -->
								
								
				<!-- LOAD ITEMS FROM DATABASE -->
				<?php if (empty($list_items)) echo '<p id="no-items">No items found!</p>'; ?>
								
				<?php foreach($list_items as $n => $list_item){ ?>
					
					<?php $item_id = $list_item['id']; ?>
					
						<div class="ui-state-default item" id="item_<?= $item_id; ?>" data-list="<?= $n; ?>">	
							<div class="preview_item">
								<div class="preview_content">
									<?= __($list_item['title']); ?>
								</div>
								<div class="edit_buttons">
									<span class="delete_item_button">verwijder | </span>
									<span class="edit_item_button">bewerk</span>
								</div>									
							</div>
							<div class="edit_item">									
								<table class="inner_table">
									<tbody>
										<tr>
											<th>Vraag</th>
											<td><input class="title" type="text" size="36" name="title_<?= $item_id; ?>" value='<?= $list_item['title']; ?>' /></td>
										</tr>
										</tr>
											<th>Antwoord</th>
											<td><textarea class="text" type="text" size="36" name="text_<?= $item_id; ?>"><?= $list_item['text']; ?></textarea></td>
										</tr>
										<tr>
											<th>Categorie</th>
											<td>				
												<select class="category_options" name="category_options_<?= $item_id; ?>">
														<?php $this->category->getCategoryOptions(); ?>
												</select>
														
												<input class="new_category button" type="button" value="New Category" />
													<div class="add_category">
														<input class="category_input" type="text" />
														<input class="add_category_button button-primary" type="button" value="Add Category" />
													</div>
									    	</td>	
										</tr>
										<tr>
											<td>
												<input type="submit" name="oc_submit_form" class="button-primary" value="Save" style="margin-top: 10px;"/>
											</td>												
										</tr>
									</tbody>
								</table>
							</div> <!-- edit -->
						</div> <!-- item -->
				<?php } // end each ?>
			</div> <!-- items -->
							
			<div class="buttons">
				<input type="submit" name="Submit" class="extra_item button-secondary" value="New item" />
			</div>							
							
			<div class="buttons" style="border-top: 1px solid #ddd;">
				<input type="submit" name="oc_submit_form" class="button-primary" value="Save"/>
				<p style="float:right;">Plaats de tag <strong>[ faq category="<?= $cur_category; ?>" ]</strong> op een pagina om deze FAQ weer te geven</p>
			</div>
			
		</div> <!-- /sortable -->
	</form>
</div> <!-- /wrap -->
			