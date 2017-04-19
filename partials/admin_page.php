<?php
/*
 *  partial for admin page
 */
?>
		
<div class="wrap faq-plugin">
	<div class="faq-page-header">
		<h2>Frequently Asked Questions</h2>
			
		<p>Op deze pagina kunt u de FAQ. Je kunt een grote lijst aanmaken of de vragen opdelen in verschillende categorieen.</p>
		
		<select class="select_category" onchange="location = '?page=faq_page&category='+this.options[this.selectedIndex].value;">
			<?php $this->category->getCategoryOptions(); ?>
		</select>
		<input id="current_category" value="<?= $cur_category ?>" style="display: none;"/>							
		<input class="delete_category_button button" id="<?= $cur_category ?>" type="button" value="Delete this Category" />
		<input class="new_category button" type="button" value="New Category" />
		<div class="add_category">
			<input class="category_input" type="text" />
			<input class="add_category_button button-primary" type="button" value="Add Category" />					
		</div>
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
	<div class="faq-container shortcode-metabox">
		<h3>FAQ weergeven</h3>
		<p><i>Plak de volgende shortcode op de pagina om de faq weer te geven. </i><strong>[faq category="<?= $cur_category ?>"]</strong></p>
		<hr>
		<p><strong>Laat de categorie ingeklapt zien</strong></br>[faq category="<?= $cur_category ?>" collapse="true"]</p>
		<p><i>Plak de shortcodes van de verschillende categorie&euml;n achter elkaar om de complete lijst van vragen weer te geven. Of gebruik de shortcode [faq]</i></p>
	</div>
	
	<?php 
		
		if ( is_plugin_active( 'qtranslate-x/qtranslate.php' ) ) {
			echo '<div class="faq-container qtranslate-metabox">';
			echo '<h3>ondersteuning qtranslate</h3>';
			echo '<p>Deze FAQ-plugin ondersteund ook de qtranslate plugin.</p>';
			echo '<p>Worden language knoppen niet weergegeven? Voeg dan de volgende code "plugins/easy-faq/i18n-config.json" toe aan het tekstvak met configuratie bestanden.</p>';
			
			echo '<p><a href="'.get_admin_url().'options-general.php?page=qtranslate-x#integration" class="button-primary">configuratie bestanden</a></p>';
			echo '<p>Je kunt ook een specifieke engelse en nederlandse categorie aanmaken. Niet alle vragen hoeven dan te worden vertaald. Het kan namelijk zo zijn dat internationale bezoekers niet exact dezelfde vragen hebben als de nederlandse.</p>';
			echo '</div>';
		}
		
	?>

</div> <!-- /wrap -->
			