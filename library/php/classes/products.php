<?php
class products extends motherboard
{
	/*
	**	Create a view of the products.
	**	data[0]	=	merchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			if($data[1] != " " && $data[1] != "bookmarks")
			{
				$split = explode(" ", $data[1]);
				
				foreach($split AS $string)
				{
					if(intval($string) == 0)
					{
						$search .= sprintf(
							"	AND		products.name LIKE ('%%%s%%')",
							parent::real_escape_string($string)
						);
					}
					else
					{
						$search .= sprintf(
							"	AND		(
											products.article_code = %d
									OR		products.supplier_code = %d
									OR		products.barcode = %d
										)",
							$string,
							$string,
							$string
						);
					}
				}
			}
			else if($data[1] == "bookmarks")
			{
				$search = " AND products.bookmarks = 1";
			}
		}
		else
		{
			return array();
		}
		
		$query = sprintf(
			"	SELECT		products.productID,
							LPAD(products.article_code, 5, 0) AS article_code,
							products.supplier_code,
							products.barcode,
							products.name,
							products.price,
							products.visibility,
							products.status,
							(
								SELECT		SUM(products_stock.stock)
								FROM		products_stock
								WHERE		products_stock.productID = products.productID
							) AS stock,
							DATE_FORMAT(products.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(products.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(products.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		products
				WHERE		products.merchantID = %d
					AND		products.deleted = 0
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		$return = array();
		
		while($row = parent::fetch_assoc($result))
		{
			$promo = parent::_runFunction("promotions", "checkPromotion", array($row['productID']));
			$row['promo'] = false;
			
			if($promo > 0)
			{
				$row['promo'] = true;
				$row['price'] = $promo;
			}
			
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	
	/*
	**	Load a certain percentage.
	**	data[0]	=	productID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$_lang = parent::_allLanguages();
		$languages = "";
		
		foreach($_lang AS $value)
		{
			$languages .= sprintf(
				"	(
						SELECT		products_lang.name
						FROM		products_lang
						WHERE		products_lang.productID = products.productID
							AND		products_lang.code = '%s'
					) AS %s_name,
					(
						SELECT		products_lang.description
						FROM		products_lang
						WHERE		products_lang.productID = products.productID
							AND		products_lang.code = '%s'
					) AS %s_description,
					(
						SELECT		products_lang.price
						FROM		products_lang
						WHERE		products_lang.productID = products.productID
							AND		products_lang.code = '%s'
					) AS %s_price,
					(
						SELECT		products_lang.price_adviced
						FROM		products_lang
						WHERE		products_lang.productID = products.productID
							AND		products_lang.code = '%s'
					) AS %s_price_adviced,",
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							products.*,
							LPAD(products.article_code, 5, 0) AS article_code_long,
							taxes.percentage AS taxrate
				FROM		products
				INNER JOIN	taxes ON taxes.taxesID = products.taxesID
				WHERE		products.productID = %d",
			$languages,
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		categories.name,
								categories.categoryID
					FROM		categories_products
					INNER JOIN	categories ON categories.categoryID = categories_products.categoryID
					WHERE		categories_products.productID = %d
					ORDER BY	categories.parentID",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['categories'] = array();
			
			if(parent::num_rows($result))
			{
				$categories = parent::fetch_array($result);
				
				$cnt = 0;
				
				foreach($categories AS $category)
				{
					$return['categories'][$cnt] = $category;
					$return['categories'][$cnt]['filters'] = parent::_runFunction("categories", "load", array($category['categoryID']));
					
					$cnt++;
				}
			}
			
			
			$query = sprintf(
				"	SELECT		products_media.*
					FROM		products_media
					WHERE		products_media.productID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['images'] = array();
			
			if(parent::num_rows($result))
			{
				$return['images'] = parent::fetch_array($result);
			}
			
			
			$query = sprintf(
				"	SELECT		products_properties.*
					FROM		products_properties
					WHERE		products_properties.productID = %d
					ORDER BY	products_properties.language",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['products_properties'] = array();
			
			if(parent::num_rows($result))
			{
				$return['products_properties'] = parent::fetch_array($result);
			}
			
			
			return $return;
		}
		
		return array();
	}
	
	
	
	/*
	**	Load a product based on the article_code.
	**	Usefull in some cases.
	**	data[0]	=	merchantID;
	**	data[0]	=	article_code.
	*/
	
	public function loadFromCode($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		products.productID
				FROM		products
				WHERE		products.article_code = '%s'
					AND		products.merchantID = %d",
			$data[1],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		if($row['productID'] != "")
		{
			return $this->load(array($row['productID']));
		}
	}
	
	
	
	
	/*
	**	Load the filter values based on:
	**	data[0] =	productID;
	**	data[1] =	filterID;
	**	data[2] =	language.
	*/
	
	public function loadFilterValue($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	SELECT		products_filters.value
				FROM		products_filters
				WHERE		products_filters.productID = %d
					AND		products_filters.filterID = %d
					AND		products_filters.language = '%s'",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['value'];
	}
	
	
	
	/*
	**	Save or update a product. If 'delete' is set
	**	in the post values, continue to the delete function.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function save($data)
	{
		parent::_checkInputValues($data, 3);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}
		
		if($data[1]['workorders_manhours'] == 1)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.workorders_manhours = 0"
			);
			parent::query($query);
		}
		
		if($data[1]['workorders_products'] == 1)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.workorders_products = 0"
			);
			parent::query($query);
		}
		
		if(isset($data[1]['productID']) && $data[1]['productID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.shipmentID = %d,
								products.taxesID = %d,
								products.groupID = %d,
								products.brandID = %d,
								products.externalStockID = %d,
								products.deleted = 0,
								products.workorders_products = %d,
								products.workorders_manhours = %d,
								products.bookmarks = %d,
								products.delivery_days = %d,
								products.status = %d,
								products.visibility = %d,
								products.maximum = %d,
								products.name = '%s',
								products.article_code = '%s',
								products.supplier_code = '%s',
								products.barcode = '%s',
								products.description = '%s',
								products.price = '%.2f',
								products.price_adviced = '%.2f',
								products.price_purchase = '%.2f',
								products.weight = '%.2f',
								products.date_update = NOW()
					WHERE		products.productID = %d",
				$data[1]['shipmentID'],
				$data[1]['taxesID'],
				$data[1]['groupID'],
				$data[1]['brandID'],
				$data[1]['externalStockID'],
				$data[1]['workorders_products'],
				$data[1]['workorders_manhours'],
				$data[1]['bookmark'],
				$data[1]['delivery_days'],
				$data[1]['status'],
				$data[1]['visibility'],
				$data[1]['maximum'],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['article_code']),
				parent::real_escape_string($data[1]['supplier_code']),
				parent::real_escape_string($data[1]['barcode']),
				parent::real_escape_string($data[1]['description']),
				parent::floatvalue($data[1]['price']),
				parent::floatvalue($data[1]['price_adviced']),
				parent::floatvalue($data[1]['price_purchase']),
				parent::floatvalue($data[1]['weight']),
				intval($data[1]['productID'])
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		products_lang
					WHERE			products_lang.productID = %d",
				intval($data[1]['productID'])
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		products_filters
					WHERE			products_filters.productID = %d",
				intval($data[1]['productID'])
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		products
					SET				products.merchantID = %d,
									products.shipmentID = %d,
									products.taxesID = %d,
									products.groupID = %d,
									products.brandID = %d,
									products.externalStockID = %d,
									products.deleted = 0,
									products.workorders_products = %d,
									products.workorders_manhours = %d,
									products.bookmarks = %d,
									products.delivery_days = %d,
									products.status = %d,
									products.visibility = %d,
									products.maximum = %d,
									products.name = '%s',
									products.article_code = '%s',
									products.supplier_code = '%s',
									products.barcode = '%s',
									products.description = '%s',
									products.price = '%.2f',
									products.price_adviced = '%.2f',
									products.price_purchase = '%.2f',
									products.weight = '%.2f',
									products.date_added = NOW()",
				$data[0],
				$data[1]['shipmentID'],
				$data[1]['taxesID'],
				$data[1]['groupID'],
				$data[1]['brandID'],
				$data[1]['externalStockID'],
				$data[1]['workorders_products'],
				$data[1]['workorders_manhours'],
				$data[1]['bookmark'],
				$data[1]['delivery_days'],
				$data[1]['status'],
				$data[1]['visibility'],
				$data[1]['maximum'],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['article_code']),
				parent::real_escape_string($data[1]['supplier_code']),
				parent::real_escape_string($data[1]['barcode']),
				parent::real_escape_string($data[1]['description']),
				parent::floatvalue($data[1]['price']),
				parent::floatvalue($data[1]['price_adviced']),
				parent::floatvalue($data[1]['price_purchase']),
				parent::floatvalue($data[1]['weight'])
			);
			$result = parent::query($query);
			
			$data[1]['productID'] = parent::insert_id($result);
		}
		
		
		
		/*
		**	Store fields with multilanguage support.
		**	The available languages are also stored in the database
		**	and manage through the motherboard.
		*/
		
		$_lang = parent::_allLanguages();
		
		foreach($_lang AS $value)
		{
			$query = sprintf(
				"	INSERT INTO		products_lang
					SET				products_lang.productID = %d,
									products_lang.code = '%s',
									products_lang.name = '%s',
									products_lang.description = '%s',
									products_lang.price = '%.2f',
									products_lang.price_adviced = '%.2f'",
				intval($data[1]['productID']),
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_name']),
				parent::real_escape_string($data[1][$value['code'] . '_description']),
				parent::real_escape_string($data[1][$value['code'] . '_price']),
				parent::real_escape_string($data[1][$value['code'] . '_price_adviced'])
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Save the added categories.
		*/
		
		foreach($data[1]['categories'] AS $category)
		{
			if($category == 0)
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		categories_products
					SET				categories_products.categoryID = %d,
									categories_products.productID = %d",
				$category,
				$data[1]['productID']
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Save the added images.
		*/
		
		$data[2]['image'] = parent::_reArrayFiles($data[2]['image']);
		
		foreach($data[1]['thumb'] AS $key => $thumb)
		{
			if($data[2]['image'][$key]['tmp_name'] == "")
			{
				continue;
			}
			
			if($thumb == 1)
			{
				$query = sprintf(
					"	UPDATE		products_media
						SET			products_media.thumb = 0
						WHERE		products_media.productID = %d",
					$data[1]['productID']
				);
				$result = parent::query($query);
			}
			
			$query = sprintf(
				"	INSERT INTO		products_media
					SET				products_media.productID = %d,
									products_media.type = 'image',
									products_media.youtube_url = '',
									products_media.thumb = %d",
				$data[1]['productID'],
				intval($thumb)
			);
			$result = parent::query($query);
			
			$itemID = parent::insert_id($result);
			
			
			/*
			**	Upload the picture. This part is done by a upload function
			**	on the main motherboard. Ofcourse we need to give some data.
			*/
			
			if($data[2]['image'][$key]['tmp_name'] != "")
			{
				$path = $_SERVER['DOCUMENT_ROOT'] . "/library/media/products/" . $itemID;
				
				$options = array(
					"extension" => "png"
				);
				
				parent::_uploadFile($data[2]['image'][$key], $path, $options);
			}
		}
		
		
		
		/*
		**	Save the added properties.
		*/
		
		foreach($data[1]['filter_key'] AS $key => $filter_key)
		{
			if($filter_key == "")
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		products_properties
					SET				products_properties.productID = %d,
									products_properties.language = '%s',
									products_properties.key = '%s',
									products_properties.value = '%s'",
				$data[1]['productID'],
				$data[1]['filter_language'][$key],
				parent::real_escape_string($data[1]['filter_key'][$key]),
				parent::real_escape_string($data[1]['filter_value'][$key])
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Save the added filters.
		*/
		
		foreach($data[1]['filter_id'] AS $key => $filterID)
		{
			$query = sprintf(
				"	INSERT INTO		products_filters
					SET				products_filters.productID = %d,
									products_filters.filterID = %d,
									products_filters.language = '%s',
									products_filters.value = '%s'",
				$data[1]['productID'],
				$filterID,
				$data[1]['filter_languages'][$key],
				parent::real_escape_string($data[1]['filter_values'][$key])
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Mutate the stock that is given inside the form.
		**	This mutation is handled by the stock class.
		*/
		
		foreach($data[1]['stock_mutation'] AS $key => $stock_mutation)
		{
			if(intval($stock_mutation) != "")
			{
				parent::_runFunction("stock", "updateStock", array(intval($data[1]['productID']), intval($data[1]['stock_location'][$key]), $stock_mutation));
			}
		}
		
		return $data[1]['productID'];
	}
	
	
	
	/*
	**
	*/
	
	public function saveBarcode($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	UPDATE		products
				SET			products.barcode = '%s'
				WHERE		products.article_code = %d",
			($data[2] > 0 ? $data[2] : ""),
			$data[1]
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove the percentage from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		taxes
				WHERE			taxes.taxesID = %d",
			$data[1]['taxesID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove the link between product and category.
	*/
	
	public function deleteCategory($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		categories_products
				WHERE			categories_products.categoryID = %d",
			$data[1]['categoryID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove a media item.
	*/
	
	public function deleteMedia($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		products_media
				WHERE			products_media.productMediaID = %d",
			$data[1]['productMediaID']
		);
		parent::query($query);
		
		$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/products/" . intval($data[1]['itemID']) . ".png";
		
		if(file_exists($image))
		{
			unlink($image);
		}
		
		return true;
	}
	
	
	
	/*
	**	Transform the visibility code into
	**	readable text for the user.
	*/
	
	public function translateVisibility($data)
	{
		switch($data[0])
		{
			case 1:
				return "Kassa";
			break;
			
			case 2:
				return "Webwinkel";
			break;
			
			case 3:
				return "Kassa, webwinkel";
			break;
		}
	}
	
	
	
	/*
	**	Transform the status code into
	**	readable text for the user.
	*/
	
	public function translateStatus($data)
	{
		switch($data[0])
		{
			case 1:
				return "Artikel draait volledig mee";
			break;
			
			case 2:
				return "Uitverkoop, laatste varianten";
			break;
			
			case 3:
				return "Tijdelijk uitverkocht, komt nog terug";
			break;
			
			case 3:
				return "Uitverkocht, komt niet terug in de voorraad";
			break;
		}
	}
	
	
	
	/*
	** $data[0] = 	merchantID.
	*/
	
	public function front_highRated($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		products.*,
							products_media.productMediaID
				FROM		reviews
				INNER JOIN	products ON products.productID = reviews.productID
				INNER JOIN	products_media ON products_media.productID = products.productID
					AND		products_media.thumb = 1
				GROUP BY	reviews.productID
                ORDER BY 	SUM(reviews.stars) DESC
				LIMIT		0,5",
			$data[0]
		);
		$result = parent::query($query);
		
		$return = array();
		
		while($row = parent::fetch_assoc($result))
		{
			$row['image'] = "https://" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "mechant") . ".justinharings.nl/library/media/products/" . $row['productMediaID'] . ".png";
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	
	/*
	** data[0] =	merchantID;
	** data[1] =	categoryID.
	*/
	
	public function front_loadProducts($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		products_cache.*
				FROM		products_cache
				WHERE		products_cache.merchantID = %d
					%s
				GROUP BY	products_cache.productID
				ORDER BY	products_cache.name_sort",
			$data[0],
			($data[1] > 0 ? "AND products_cache.categoryID = " . intval($data[1]) : "AND products_cache.sale = 1")
		);
		$result = parent::query($query);
		
		return parent::fetch_array($result);
	}
}
?>