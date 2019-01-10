<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

if(isset($_GET['dataID']) && $_GET['dataID'] > 0)
{
	$data = $mb->_runFunction("products", "load", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "article-management") ?></li>
	<li><?= (isset($_GET['dataID']) ? $data['name'] : $mb->_translateReturn("forms", "add")) ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/catalogus/products.php" enctype="multipart/form-data">
	<input type="hidden" name="productID" id="productID" value="<?= isset($_GET['dataID']) && !isset($_GET['duplicate']) ? $_GET['dataID'] : 0 ?>" />
	<input type="hidden" name="returnURL" id="returnURL" value="<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/[dataID]/" ?>" />
	
	<div class="simple-form">
		<div class="form-header">
			<h1><?= (isset($_GET['dataID']) ? $mb->_translateReturn("forms", "edit", array(_chopString(strip_tags($data['name']), 40))) : $mb->_translateReturn("forms", "add-new-title")) ?></h1>
			
			<input type="button" name="return" id="return" value="<?= $mb->_translateReturn("forms", "button-cancel") ?>" class="show-load" />
			
			<?php
			if(!isset($_GET['duplicate']))
			{
				?>
				<input type="button" name="duplicate" id="duplicate" value="<?= $mb->_translateReturn("forms", "button-duplicate") ?>" class="show-load" />
				<?php
			}
				
			if($data['deleted'] == 0)
			{
				if(isset($_GET['dataID']) && $data['stock'] == 0)
				{
					?>
					<input type="button" name="delete-item" id="delete-item" value="<?= $mb->_translateReturn("forms", "button-delete") ?>" class="white show-load" />
					<?php
				}
				?>
				
				<input type="submit" name="save" id="save" value="<?= $mb->_translateReturn("forms", "button-save") ?>" class="red show-load validate-form" />
				<?php
			}
			?>
		</div>
		
		<div class="form-tabs">
			<div class="fa fa-bars"></div>
			
			<div class="fa fa-pencil"></div>
			<div class="fa fa-credit-card"></div>
			<div class="fa fa-tags"></div>
			<div class="fa fa-picture-o"></div>
			<div class="fa fa-youtube-play"></div>
			<div class="fa fa-list"></div>
			<div class="fa fa-filter"></div>
			<div class="fa fa-exchange"></div>
			<div class="fa fa-search"></div>
		</div>
		
		<div class="tab tab-1">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-numbers") ?>
				</div>
				
				<input type="text" name="article_code" id="article_code" value="<?= isset($_GET['dataID']) && !isset($_GET['duplicate']) ? $data['article_code'] : $mb->_runFunction("orders", "getNewArticleCode", array($_SESSION['merchantID'])) ?>" class="width-100 margin" holder="<?= $mb->_translateReturn("forms", "form-products-ac") ?>" validation-required="true" validation-type="int" unique-article="<?= $data['article_code'] ?>" />
				<input type="text" name="supplier_code" id="supplier_code" value="<?= isset($_GET['dataID']) ? $data['supplier_code'] : "" ?>" class="width-100 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-sc") ?>" />
				<input type="text" name="barcode" id="barcode" value="<?= isset($_GET['dataID']) ? $data['barcode'] : "" ?>" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-barcode") ?>" />
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-manage-content") ?>
				</div>
				
				<input type="text" name="name" id="name" value="<?= isset($_GET['dataID']) ? $data['name'] : "" ?>" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-products-name") ?>" <?= $_SESSION['merchantID'] == 1 ? 'holder-eg="[INCH] [BRAND] [TYPE] [GEARS] [ELECTRIC] [COLOR] [SEX] [SIZE]"' : "" ?> validation-required="true" validation-type="text" />
				
				<?php
				$_lang = $mb->_allLanguages();
				
				if($mb->num_rows($_lang) > 0)
				{
					?>
					<div class="languages width-300">
						<span class="fa fa-chevron-circle-down"></span>
						
						<?php
						foreach($_lang AS $value)
						{
							?>
							<fieldset>
								<legend><?= $value['language'] ?></legend>
								<input type="text" name="<?= $value['code'] ?>_name" id="<?= $value['code'] ?>_name" value="<?= isset($_GET['dataID']) ? $data[$value['code'] . '_name'] : "" ?>" class="width-100-percent" validation-required="true" validation-type="text" icon="fa-globe" />
							</fieldset>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
				
				<select name="description-template" id="description-template" class="width-200 margin" holder="Description template" question="U kunt onder 'Catalogus beheer' > 'Omschrijving templates' vooraf templates instellen en hier uitkiezen. Op deze manier hoeft u niet steeds te copy/pasten of dergelijke en kunt u sneller werken.">
					<option value=""></option>
					
					<?php
					$data_description = $mb->_runFunction("categories", "viewDescriptionTemplates", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "descriptions.name", "0,50"));
					
					if($mb->num_rows($data_description))
					{
						foreach($data_description AS $value)
						{
							?>
							<option value="<?= $value['descriptionID'] ?>"><?= $value['name'] ?></option>
							<?php
						}
					}
					?>
				</select>
				
				<textarea name="description" id="description" class="width-100-percent" holder="<?= $mb->_translateReturn("forms", "form-products-description") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-products-description-eg") ?>" validation-required="true" validation-type="text"><?= isset($_GET['dataID']) ? $data['description'] : "" ?></textarea>
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-shipment-and-weights") ?>
				</div>
				
				<select name="shipmentID" id="shipmentID" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-products-shipment") ?>" question="Dit is de verzendmethode die bij dit artikel hoort. Vooraf scannen we de winkelwagen en kijken we welke artikelen samen verzonden kunnen worden en welke apart moeten. Ook kunnen we kleinere artikelen samenvoegen met grotere artikelen die per grotere verzendmethode gaan. Bijvoorneeld een luchtenveloppe bij een normaal pakket in.">
					<option value=""></option>
					
					<?php
					$data_shipments = $mb->_runFunction("shipment_methods", "view", array($_SESSION['merchantID'], "", "shipment_methods.name", "0,50"));
					
					foreach($data_shipments AS $values)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['shipmentID'] == $values['shipmentID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['shipmentID'] ?>"><?= $values['name'] ?> (&euro;&nbsp;<?= number_format($values['price'], 2, ",", ".") ?>)</option>
						<?php
					}
					?>
				</select>
				
				<input type="text" name="weight" id="weight" value="<?= isset($_GET['dataID']) ? $data['weight'] : "" ?>" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-products-weight") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-products-weight-eg") ?>" />
			</div>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-others") ?>
				</div>
				
				<input type="text" name="maximum" id="maximum" value="<?= isset($_GET['dataID']) ? $data['maximum'] : "" ?>" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-maximum") ?>" question="Een klant kan niet meer dan het maximum toevoegen aan zijn of haar winkelwagen. Bijvoorbeeld voor artikelen met een messcherpe voordeelprijs." />
				
				<select name="brandID" id="brandID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-brand") ?>">
					<option value=""></option>
					
					<?php
					$data_brands = $mb->_runFunction("brands", "view", array($_SESSION['merchantID'], "", "brands.name", "0,99999"));
					
					foreach($data_brands AS $values)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['brandID'] == $values['brandID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['brandID'] ?>"><?= $values['name'] ?></option>
						<?php
					}
					?>
				</select>
				
				<select name="groupID" id="groupID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-group") ?>">
					<option value=""></option>
					
					<?php
					$data_groups = $mb->_runFunction("groups", "view", array($_SESSION['merchantID'], "", "groups.name", "0,50"));
					
					foreach($data_groups AS $values)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['groupID'] == $values['groupID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['groupID'] ?>"><?= $values['name'] ?></option>
						<?php
					}
					?>
				</select>
				
				<select name="visibility" id="visibility" class="width-200 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-visibility") ?>" question="U kunt instellen of dit artikel alleen zichtbaar moet zijn in de kassa of ook in de webwinkel. Zo kunt u kassa-specifieke artikelen uit de webshop houden.">
					<option <?= isset($_GET['dataID']) && $data['visibility'] == 1 ? "selected=\"selected\"" : "" ?> value="1">Kassa</option>
					<option <?= isset($_GET['dataID']) && $data['visibility'] == 2 ? "selected=\"selected\"" : "" ?> value="2">Webwinkel</option>
					<option <?= isset($_GET['dataID']) && $data['visibility'] == 3 ? "selected=\"selected\"" : "" ?> value="3">Kassa, Webwinkel</option>
				</select>
				
				<input type="checkbox" <?= isset($_GET['dataID']) && $data['bookmarks'] == 1 ? "checked=\"checked\"" : "" ?> name="bookmark" id="bookmark" value="1" class="double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-bookmark") ?>" question="Zet dit artikel in de favorieten van uw POS. Zo kunt u bepaalde artikelen (plastic tassen, vaste diensten etcetera) onder de bookmark van uw POS zetten." />
				<input type="checkbox" <?= isset($_GET['dataID']) && $data['workorders_products'] == 1 ? "checked=\"checked\"" : "" ?> name="workorders_products" id="workorders_products" value="1" class="margin" holder="<?= $mb->_translateReturn("forms", "form-products-parts") ?>" question="Gebruik dit artikel als 'product' vanuit de werkorders." />
				<input type="checkbox" <?= isset($_GET['dataID']) && $data['workorders_manhours'] == 1 ? "checked=\"checked\"" : "" ?> name="workorders_manhours" id="workorders_manhours" value="1" holder="<?= $mb->_translateReturn("forms", "form-products-manhours") ?>" question="Gebruik dit artikel als 'manuren/laag percentage' vanuit de werkorders." />
			</div>
		</div>
		
		<div class="tab tab-2">
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-prizes") ?>
				</div>
				
				<input type="text" name="price" id="price" value="<?= isset($_GET['dataID']) ? $data['price'] : "" ?>" class="width-150 margin" holder="<?= $mb->_translateReturn("forms", "form-products-price") ?>" icon="fa-euro" validation-required="true" validation-type="int" />
				
				<input type="text" name="price_adviced" id="price_adviced" value="<?= isset($_GET['dataID']) ? $data['price_adviced'] : "" ?>" class="width-150 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-price-adviced") ?>" icon="fa-euro" question="Deze prijs komt doorgestreept bij de producten te staan. Dit komt eruit te zien als een van-voor prijs." />
				
				<input type="text" name="price_purchase" id="price_purchase" value="<?= isset($_GET['dataID']) ? $data['price_purchase'] : "" ?>" class="width-150 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-price-purchase") ?>"holder-eg="<?= $mb->_translateReturn("forms", "form-products-purchase-eg") ?>" icon="fa-euro" />
				
				<select name="taxesID" id="taxesID" class="width-200" holder="<?= $mb->_translateReturn("forms", "form-products-taxes") ?>" validation-required="true" validation-type="int">
					<option value="-"></option>
					
					<?php
					$data_groups = $mb->_runFunction("taxes", "view", array($_SESSION['merchantID'], "", "taxes.name", "0,50"));
					
					foreach($data_groups AS $values)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['taxesID'] == $values['taxesID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['taxesID'] ?>"><?= $values['name'] . " (" . $values['percentage'] . "%)" ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		
		<div class="tab tab-3">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="500"><?= $mb->_translateReturn("forms", "form-products-categories") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						if(!isset($_GET['duplicate']))
						{
							foreach($data['categories'] AS $value)
							{
								?>
								<tr>
									<td><?= $value['name'] ?></td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_category.php?categoryID=<?= $value['categoryID'] ?>&productID=<?= $_GET['dataID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" .$_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						else
						{
							foreach($data['categories'] AS $value)
							{
								?>
								<tr>
									<td>
										<select name="categories[]" id="categories" class="width-200">
											<option value=""></option>
											
											<?php
											$data_categories = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", 0));
											
											foreach($data_categories AS $values)
											{
												?>
												<option <?= $value['categoryID'] == $values['categoryID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['categoryID'] ?>"><?= $values['name'] ?></option>
			
												<?php
												$data_categories_2 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $values['categoryID']));
												
												foreach($data_categories_2 AS $values2)
												{
													?>
													<option <?= $value['categoryID'] == $values2['categoryID'] ? "selected=\"selected\"" : "" ?> value="<?= $values2['categoryID'] ?>">&nbsp;&nbsp;-&nbsp;<?= $values2['name'] ?></option>
			
													<?php
													$data_categories_3 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $values2['categoryID']));
												
													foreach($data_categories_3 AS $values3)
													{
														?>
														<option <?= $value['categoryID'] == $values3['categoryID'] ? "selected=\"selected\"" : "" ?> value="<?= $values3['categoryID'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;<?= $values3['name'] ?></option>
														<?php
													}
												}
											}
											?>
										</select>
									</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td>
								<select name="categories[]" id="categories" class="width-200">
									<option value=""></option>
									
									<?php
									$data_categories = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", 0));
									
									foreach($data_categories AS $values)
									{
										?>
										<option value="<?= $values['categoryID'] ?>"><?= $values['name'] ?></option>
	
										<?php
										$data_categories_2 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $values['categoryID']));
										
										foreach($data_categories_2 AS $values2)
										{
											?>
											<option value="<?= $values2['categoryID'] ?>">&nbsp;&nbsp;-&nbsp;<?= $values2['name'] ?></option>
	
											<?php
											$data_categories_3 = $mb->_runFunction("categories", "view", array($_SESSION['merchantID'], "", "categories.name", "0,50", $values2['categoryID']));
										
											foreach($data_categories_3 AS $values3)
											{
												?>
												<option value="<?= $values3['categoryID'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;<?= $values3['name'] ?></option>
												<?php
											}
										}
									}
									?>
								</select>
							</td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="tab tab-4">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="500"><?= $mb->_translateReturn("forms", "form-products-images") ?></td>
							<td width="500"><?= $mb->_translateReturn("forms", "form-products-images-thumb") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						if(!isset($_GET['duplicate']))
						{
							$exists = false;
							
							foreach($data['images'] AS $value)
							{
								$exists = ($exists == true || $value['thumb'] == 1 ? true : false);
								?>
								<tr>
									<td>
										<?php	
										$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/products/" . $value['productMediaID'] . ".png";
										
										if(file_exists($image))
										{
											?>
											<img src="/library/media/products/<?= $value['productMediaID'] ?>.png" class="product" />
											<?php
										}
										else
										{
											print "Geen foto";
										}
										?>
									</td>
									<td><span class="fa large <?= $value['thumb'] ? "fa-check-circle green" : "fa-times-circle red" ?>"></span></td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_media.php?productMediaID=<?= $value['productMediaID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td><input type="file" name="image[]" id="image_+" value="" class="width-200" validation-type="image" image-extension="png" /></td>
							<td><input type="checkbox" <?= $exists ? "" : "checked=\"checked\"" ?> name="thumb[]" id="thumb_+" value="1" class="width-200" validation-required="true" validation-type="int" /></td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="tab tab-5">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="500"><?= $mb->_translateReturn("forms", "form-products-videos") ?></td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						if(!isset($_GET['duplicate']))
						{
							foreach($data['videos'] AS $value)
							{
								?>
								<tr>
									<td>
										<a href="<?= $value['url'] ?>" target="_blank">
											<?= $value['url'] ?>
										</a>
										&nbsp;<small><span class="fa fa-external-link"></span></small>
									</td>
									<td><span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_video.php?videoID=<?= $value['videoID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" . $_GET['form'] . "/" . $_GET['dataID'] ?>"></span></td>
								</tr>
								<?php
							}
						}
						?>
						
						<tr class="new-row">
							<td><input type="text" name="video[]" id="video_+" value="" class="width-200" validation-type="text" /></td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		
		<div class="tab tab-6">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td><?= $mb->_translateReturn("forms", "form-products-properties-language") ?></td>
							<td><?= $mb->_translateReturn("forms", "form-products-properties-key") ?></td>
							<td><?= $mb->_translateReturn("forms", "form-products-properties-value") ?></td>
							<td width="60">
								<span class="add-row fa fa-plus-circle add-specification" style="float: right;" onclick="$('.specs-table').hide();"></span>
								<span class="fa fa-gg-circle" style="float: right; margin: 1px 5px 0px 0px; font-size: 18px;" onclick="$('.specs-table').show();"></span>
								<span class="fa fa-trash trash-properties" style="float: right; margin: 1px 5px 0px 0px; font-size: 18px;"></span>
							</td>
						</tr>
					</thead>
					
					<tbody>
						<tr class="specs-table" style="display: none; background-color: #f1f1f1;">
							<td align="center"><span class="fa fa-gg-circle" style="font-size: 20px;"></span></td>
							<td><strong>Deze tabel inladen:</strong></td>
							<td>
								<select name="specificationID" id="specificationID" class="width-200">
									<option value=""></option>
									
									<?php
									$dataSpecifications = $mb->_runFunction("categories", "viewSpecifications", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "specifications.name", "0,99999"));
									
									foreach($dataSpecifications AS $specifications)
									{
										?>
										<option value="<?= $specifications['specificationID'] ?>"><?= $specifications['name'] ?></option>
										<?php
									}
									?>
								</select>
							</td>
							<td>&nbsp;</td>
						</tr>
						
						<?php
						if(!isset($_GET['duplicate']))
						{
							$_lang = $mb->_allLanguages();
							$_lang_abbr = array("nl" => "Nederlands");
							
							foreach($_lang AS $value)
							{
								$_lang_abbr[$value['code']] = $value['language'];
							}
							
							foreach($data['products_properties'] AS $value)
							{
								?>
								<tr>
									<td><?= $_lang_abbr[$value['language']] ?></td>
									<td class="prop_keys"><?= $value['key'] ?></td>
									<td class="prop_values"><?= $value['value'] ?></td>
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_specificatie.php?productPropertieID=<?= $value['productPropertieID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" .$_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						} 
						?>
						
						<tr class="new-row">
							<td>
								<select name="filter_language[]" id="filter_language_+" class="width-200">
									<option <?= isset($_GET['dataID']) && $data['language_code'] == "nl" ? "selected=\"selected\"" : "" ?> value="nl">Nederlands</option>
									<?php
									foreach($_lang AS $value)
									{
										?>
										<option <?= isset($_GET['dataID']) && $data['language_code'] == $value['code'] ? "selected=\"selected\"" : "" ?> value="<?= $value['code'] ?>"><?= $value['language'] ?></option>
										<?php
									}
									?>
								</select>
							</td>
							<td><input type="text" name="filter_key[]" id="filter_key_+" value="" class="width-300 prop_keys" validation-required="true" validation-type="text" /></td>
							<td class="values"><input type="text" name="filter_value[]" id="filter_value_+" value="" class="width-300 prop_values" validation-required="true" validation-type="text" /></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="tab tab-7">
			<?php
			if(!isset($_GET['duplicate']))
			{
				foreach($data['categories'] AS $key => $value)
				{
					$filter_values = $data['categories'][$key]['filters']['filters'];
					
					if(is_array($filter_values) && count($filter_values) == 0)
					{
						continue;
					}
					
					?>
					<div class="form-content">
						<table class="form-table">
							<thead>
								<tr>
									<td width="500"><?= $mb->_translateReturn("forms", "form-products-filters-language") ?></td>
									<td width="500"><?= $mb->_translateReturn("forms", "form-products-filters-key") ?></td>
									<td width="500"><?= $mb->_translateReturn("forms", "form-products-filters-value") ?></td>
									<td><span class="fa fa-refresh"></span></td>
								</tr>
							</thead>
							
							<tbody>
								<?php
								foreach($filter_values AS $key => $filter)
								{
									?>
									
									<tr class="filter-item" name="<?= $mb->real_escape_string($filter['name']) ?>">
										<td>Nederlands</td>
										<td><?= $filter['name'] ?></td>
										<td>
											<input type="hidden" name="filter_id[]" id="filter_id_nl_<?= $key ?>" value="<?= $filter['filterID'] ?>" />
											<input type="hidden" name="filter_languages[]" id="filter_languages_nl_<?= $key ?>" value="NL" />
											<input type="text" class="filter-value" name="filter_values[]" id="filter_values_nl_<?= $key ?>" value="<?= $mb->_runFunction("products", "loadFilterValue", array($_GET['dataID'], $filter['filterID'], "NL")) ?>" validation-required="true" validation-type="text" /></td>
										</td>
									</tr>
									
									<?php
									$_lang = $mb->_allLanguages();
									
									foreach($_lang AS $lang_value)
									{
										?>
										<tr class="filter-item" name="<?= $mb->real_escape_string($filter[$lang_value['code'] . '_name']) ?>">
											<td><?= $lang_value['language'] ?></td>
											<td><?= $filter[$lang_value['code'] . '_name'] ?></td>
											<td>
												<input type="hidden" name="filter_id[]" id="filter_id_<?= $lang_value['code'] ?>_<?= $key ?>" value="<?= $filter['filterID'] ?>" />
												<input type="hidden" name="filter_languages[]" id="filter_languages_<?= $lang_value['code'] ?>_<?= $key ?>" value="<?= $lang_value['code'] ?>" />
												<input type="text" class="filter-value" name="filter_values[]" id="filter_values_<?= $lang_value['code'] ?>_<?= $key ?>" value="<?= $mb->_runFunction("products", "loadFilterValue", array($_GET['dataID'], $filter['filterID'], $lang_value['code'])) ?>" validation-required="true" validation-type="text" /></td>
											</td>
										</tr>
										<?php
									}
									?>
									
									<tr>
										<td colspan="3"></td>
									</tr>
									
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
					<?php
				}
			}
			?>
		</div>
		
		<div class="tab tab-8">
			<?php
			$data_locations = $mb->_runFunction("stock", "viewLocations", array($_SESSION['merchantID'], "", "locations.name", "0,50"));
			
			foreach($data_locations AS $location)
			{
				$stock = $mb->_runFunction("stock", "getStock", array($_GET['dataID'], $location['locationID']));
				?>
				
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-stocks") ?> &#187; <?= $location['name'] ?>
					</div>
					
					<table>
						<tr>
							<td width="130">Voorraad:</td>
							<td><?= $stock['stock'] ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
						</tr>
						
						<tr>
							<td><strong>Gereserveerd:</strong></td>
							<td><?= $stock['reserved'] ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
						</tr>
						
						<tr>
							<td>Economisch:</td>
							<td><?= ($stock['stock']-$stock['reserved']) ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
						</tr>
					</table>
				</div>
				
				<div class="form-content">
					<div class="content-header">
						<span class="fa fa-pencil-square-o"></span>
						<?= $mb->_translateReturn("forms", "legend-stock-mutation") ?> &#187; <?= $location['name'] ?>
					</div>
					
					<input type="hidden" name="stock_location[]" id="stock_location_<?= $location['locationID'] ?>" value="<?= $location['locationID'] ?>" />
					<input type="text" name="stock_mutation[]" id="stock_mutation_<?= $location['locationID'] ?>" class="width-250" value="" holder="<?= $mb->_translateReturn("forms", "form-products-mutation") ?>" holder-eg="<?= $mb->_translateReturn("forms", "form-products-mutation-eg") ?>" />
				</div>
				<?php
			}
			?>
			
			<div class="form-content">
				<div class="content-header">
					<span class="fa fa-pencil-square-o"></span>
					<?= $mb->_translateReturn("forms", "legend-stock-settings") ?>
				</div>
				
				<select name="status" id="status" class="width-300 margin" holder="<?= $mb->_translateReturn("forms", "form-products-status") ?>" question="Producten die staan op 'uitverkoop' worden automatisch naar 'uitverkocht' gezet wanneer de voorraad op 0 staat. Deze controle loopt ieder uur.">
					<option <?= isset($_GET['dataID']) && $data['status'] == 1 ? "selected=\"selected\"" : "" ?> value="1">Artikel draait volledig mee</option>
					<option <?= isset($_GET['dataID']) && $data['status'] == 2 ? "selected=\"selected\"" : "" ?> value="2">Uitverkoop, laatste varianten</option>
					<option <?= isset($_GET['dataID']) && $data['status'] == 3 ? "selected=\"selected\"" : "" ?> value="3">Tijdelijk uitverkocht, komt nog terug</option>
					<option <?= isset($_GET['dataID']) && $data['status'] == 4 ? "selected=\"selected\"" : "" ?> value="4">Uitverkocht, komt niet terug in de voorraad</option>
				</select>
				
				<select name="stock_type" id="stock_type" class="width-300 double-margin" holder="<?= $mb->_translateReturn("forms", "form-products-default-stock-type") ?>" question="Wanneer een artikel niet via een categorie wordt benadert (bijvoorbeeld via zoeken) dan gaat het systeem over op deze voorraad.">
					<option value=""></option>
					
					<?php
					$data_stock = $mb->_runFunction("stock", "viewStockType", array());
					
					foreach($data_stock AS $stockType => $name)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['stock_type'] == $stockType ? "selected=\"selected\"" : "" ?> value="<?= $stockType ?>"><?= $name ?></option>
						<?php
					}
					?>
				</select>
				
				<select name="externalStockID" id="externalStockID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-supplier") ?>" question="Merchant is gekoppeld met een aantal leveranciers. Het koppelen gebeurt op basis van een barcode (EAN code). Indien de barcode bij dit artikel is ingevuld en u het artikel koppelt aan een geldige leverancier zou het artikel op uw webwinkel verschijnen met 'artikel is op voorraad bij de leverancier'.">
					<option value=""></option>
					<option <?= isset($_GET['dataID']) && $data['externalStockID'] == 1 ? "selected=\"selected\"" : "" ?> value="1">Popal Fietsen Nederland</option>
					<option <?= isset($_GET['dataID']) && $data['externalStockID'] == 2 ? "selected=\"selected\"" : "" ?> value="2">Juncker Bikeparts</option>
					<option <?= isset($_GET['dataID']) && $data['externalStockID'] == 3 ? "selected=\"selected\"" : "" ?> value="3">Accell Fietsen Nederland</option>
					<option <?= isset($_GET['dataID']) && $data['externalStockID'] == 4 ? "selected=\"selected\"" : "" ?> value="4">Hoop Fietsen Nederland</option>
					<option <?= isset($_GET['dataID']) && $data['externalStockID'] == 5 ? "selected=\"selected\"" : "" ?> value="5">Union Fietsen Nederland</option>
				</select>
				
				<select name="delivery_days" id="delivery_days" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-delivery-days") ?>" question="Indien het artikel niet bij u op voorraad is maar wel bij de leverancier dan kunt u uw klanten vertellen hoe lang het duurt voordat het artikel bij hem of haar is.">
					<option value=""></option>
					
					<?php
					for($i = 1; $i < 20; $i++)
					{
						?>
						<option <?= isset($_GET['dataID']) && $data['delivery_days'] == $i ? "selected=\"selected\"" : "" ?> value="<?= $i ?>">Binnen <?= $i ?> dag<?= $i > 1 ? "en" : "" ?> bij de klant</option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		
		<div class="tab tab-9">
			<div class="form-content">
				<table class="form-table">
					<thead>
						<tr>
							<td width="375"><?= $mb->_translateReturn("forms", "form-products-pricecheck-website") ?></td>
							<td>
								Verzendkosten
								<span class="fa fa-question-circle question" title="Bij het toevoegen van deze pricecheck kun je kiezen voor 'Gratis verzending'. Als dit aan staat houd de pricecheck er rekening mee dat die ingecalculeert moet worden in de prijs van de aanbieder."></span>
							</td>
							<td>Prijs</td>
							<td>Update</td>
							<td width="1"><span class="add-row fa fa-plus-circle"></span></td>
						</tr>
					</thead>
					
					<tbody>
						<?php
						if(!isset($_GET['duplicate']))
						{
							$found = false;
							
							foreach($data['pricecheck'] AS $value)
							{
								$profit = $value['profit'];
								$found = true;
								?>
								<tr>
									<td>
										<input type="text" name="pricecheck_website_234" id="pricecheck_website" value="<?= $value['website'] ?>" class="width-300" />
										&nbsp;
										<a href="<?= $value['website'] ?>" target="_blank" style="color: inherit;">
											<small><span class="fa fa-external-link"></span></small>
										</a>
									</td>
									
									<td><?= $value['free_shipment'] == 1 ? "Gratis verzending" : "Betaalde verzending" ?></td>
									
									<td>
										<?php
										if($value['date_update'] == "n.v.t.")
										{
											?>
											<span class="fa fa-search"></span>
											<span class="fa fa-spin fa-spinner"></span> ...
											<?php
										}
										else if($value['price'] > 0)
										{
											print "&euro;&nbsp;". _frontend_float($value['price']);
										}
										else
										{
											print "Onbereikbaar";
										}
										?>
									</td>
									
									<td>
										<?php
										if($value['date_update'] == "n.v.t.")
										{
											?>
											<span class="fa fa-search"></span>
											<span class="fa fa-spin fa-spinner"></span> ...
											<?php
										}
										else
										{
											print $value['date_update'] . " uur";
										}
										?>
									</td>
									
									<td>
										<span class="remove-row fa fa-remove" post="/library/php/posts/catalogus/verwijder_pricewatch.php?productWebsiteID=<?= $value['productWebsiteID'] ?>&returnURL=<?= "/" . _LANGUAGE_PACK . "/modules/" . $_GET['module'] . "/" . $_GET['file'] . "/" .$_GET['form'] . "/" . $_GET['dataID'] ?>"></span>
									</td>
								</tr>
								<?php
							}
						}
						else
						{
							foreach($data['pricecheck'] AS $value)
							{
								$cnt = 1;
								?>
								<tr>
									<td><input type="text" name="pricecheck_website[]" id="pricecheck_website_<?= $cnt ?>" value="<?= $value['website'] ?>" class="width-300" validation-required="true" validation-type="text" /></td>
									<td colspan="3">&nbsp;</td>
								</tr>
								<?php	
							}
						}
						?>
						
						<tr class="new-row">
							<td><input type="text" name="pricecheck_website[]" id="pricecheck_website_+" value="" class="width-300" validation-required="true" validation-type="text" /></td>
							<td><input type="checkbox" name="free_shipment[]" id="free_shipment_+" value="1" /></td>
							<td colspan="3">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="form-content" style="<?= $found == false ? "display: none;" : "" ?>">
				<input type="text" name="profit" id="profit" value="<?= $profit ?>" holder="Minimale winst" class="width-100" icon="fa-euro" validation-required="true" validation-type="int" question="Bij het toevoegen van deze pricecheck kun je een minimaal winstbedrag opgeven. De pricecheck zal dan niet lager gaan dan inkoop + BTW + dit winstbedrag. Als het winstbedrag op 0,00 (nul) euro staat, zal er als minimale prijs inkoop + BTW gerekent worden." />
			</div>
		</div>
	</div>
</form>