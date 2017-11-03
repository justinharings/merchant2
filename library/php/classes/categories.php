<?php
class categories extends motherboard
{
	/*
	**	Create a view of the categories.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed;
	**	data[4]	=	ParentID to load from.
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 5);
		
		$search = "";
		$parent = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		categories.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$parent = sprintf(
			"	AND		categories.parentID = %d",
			$data[4]
		);
		
		$_lang = parent::_allLanguages();
		$languages = "";
		
		foreach($_lang AS $value)
		{
			$languages .= sprintf(
				"	(
						SELECT		categories_lang.name
						FROM		categories_lang
						WHERE		categories_lang.categoryID = categories.categoryID
							AND		categories_lang.code = '%s'
					) AS %s_name, ",
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							categories.categoryID,
							categories.name,
							categories.stock_type,
							categories.active,
							categories.parentID,
							(
								SELECT		COUNT(categories_products.categoryID)
								FROM		categories_products
								WHERE		categories_products.categoryID = categories.categoryID
							) AS products,
							DATE_FORMAT(categories.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(categories.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(categories.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		categories
				WHERE		categories.merchantID = %d
					%s
					%s
				ORDER BY	%s
				LIMIT		%s",
			$languages,
			$data[0],
			$parent,
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load a certain categorie.
	**	data[0]	=	categoryID.
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
						SELECT		categories_lang.name
						FROM		categories_lang
						WHERE		categories_lang.categoryID = categories.categoryID
							AND		categories_lang.code = '%s'
					) AS %s_name, ",
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							categories.*,
							(
								SELECT		COUNT(categories_products.categoryID)
								FROM		categories_products
								WHERE		categories_products.categoryID = categories.categoryID
							) AS products
				FROM		categories
				WHERE		categories.categoryID = %d",
			$languages,
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$languages = "";
			
			foreach($_lang AS $value)
			{
				$languages .= sprintf(
					"	(
							SELECT		categories_filters_lang.name
							FROM		categories_filters_lang
							WHERE		categories_filters_lang.filterID = categories_filters.filterID
								AND		categories_filters_lang.code = '%s'
						) AS %s_name, ",
					$value['code'],
					$value['code']
				);
			}
			
			$query = sprintf(
				"	SELECT		%s
								categories_filters.*
					FROM		categories_filters
					WHERE		categories_filters.categoryID = %d",
				$languages,
				$data[0]
			);
			$result = parent::query($query);
			
			$return['filters'] = array();
			
			if(parent::num_rows($result))
			{
				$return['filters'] = parent::fetch_array($result);
			}
		}
		
		return $return;
	}
	
	
	
	/*
	**	Return information about a product based on a productID.
	**	data[0] = merchantID;
	**	data[1] = Post values.
	*/
	
	public function returnProductBasedOnID($data)
	{
		$query = sprintf(
			"	SELECT		products.*,
							LPAD(products.article_code, 5, 0) AS article_code,
							taxes.percentage AS taxrate
				FROM		products
				INNER JOIN	taxes ON taxes.taxesID = products.taxesID
				WHERE		products.productID = %d
					AND		products.merchantID = %d",
			$data[1]['productID'],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$promo = parent::_runFunction("promotions", "checkPromotion", array($row['productID']));
		$row['promo'] = false;
		
		if($promo > 0)
		{
			$row['promo'] = true;
			$row['price'] = $promo;
		}
		
		return $row;
	}
	
	
	
	/*
	**	Return information about a product based on a article_code.
	**	data[0] = merchantID;
	**	data[1] = Post values.
	*/
	
	public function returnProductBasedOnArticleCode($data)
	{
		$query = sprintf(
			"	SELECT		products.*,
							LPAD(products.article_code, 5, 0) AS article_code,
							taxes.percentage AS taxrate
				FROM		products
				INNER JOIN	taxes ON taxes.taxesID = products.taxesID
				WHERE		products.article_code = '%s'
					AND		products.merchantID = %d",
			$data[1]['article_code'],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$promo = parent::_runFunction("promotions", "checkPromotion", array($row['productID']));
		$row['promo'] = false;
		
		if($promo > 0)
		{
			$row['promo'] = true;
			$row['price'] = $promo;
		}
		
		return $row;
	}
	
	
	
	/*
	**	Return information about a product based on a article_code.
	**	data[0] = merchantID;
	**	data[1] = Post values.
	*/
	
	public function returnProductBasedOnBarcode($data)
	{
		$query = sprintf(
			"	SELECT		products.*,
							LPAD(products.article_code, 5, 0) AS article_code,
							taxes.percentage AS taxrate
				FROM		products
				INNER JOIN	taxes ON taxes.taxesID = products.taxesID
				WHERE		products.barcode = '%s'
					AND		products.merchantID = %d",
			$data[1]['barcode'],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$promo = parent::_runFunction("promotions", "checkPromotion", array($row['productID']));
		$row['promo'] = false;
		
		if($promo > 0)
		{
			$row['promo'] = true;
			$row['price'] = $promo;
		}
		
		return $row;
	}
	
	
	
	/*
	**	Save or update a categorie. If 'delete' is set
	**	in the post values, continue to the delete function.
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values.
	*/
	
	public function save($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}
		
		if(isset($data[1]['categoryID']) && $data[1]['categoryID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		categories
					SET			categories.name = '%s',
								categories.parentID = %d,
								categories.stock_type = %d,
								categories.active = %d,
								categories.date_update = NOW()
					WHERE		categories.categoryID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['parentID']),
				intval($data[1]['stock_type']),
				intval($data[1]['active']),
				$data[1]['categoryID']
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		categories_lang
					WHERE			categories_lang.categoryID = %d",
				intval($data[1]['categoryID'])
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		categories
					SET				categories.merchantID = %d,
									categories.name = '%s',
									categories.parentID = %d,
									categories.stock_type = %d,
									categories.active = %d,
									categories.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['parentID']),
				intval($data[1]['stock_type']),
				intval($data[1]['active'])
			);
			$result = parent::query($query);
			
			$data[1]['categoryID'] = parent::insert_id($result);
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
				"	INSERT INTO		categories_lang
					SET				categories_lang.categoryID = %d,
									categories_lang.code = '%s',
									categories_lang.name = '%s'",
				intval($data[1]['categoryID']),
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_name'])
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Store the array with filter options.
		*/
		
		foreach($data[1]['filter_name'] AS $key => $name)
		{
			if($name == "")
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		categories_filters
					SET				categories_filters.categoryID = %d,
									categories_filters.name = '%s',
									categories_filters.multiple_choice = %d",
				$data[1]['categoryID'],
				$name,
				$data[1]['multiple'][$key]
			);
			$result = parent::query($query);
			
			$filterID = parent::insert_id($result);
			
			foreach($_lang AS $value)
			{
				$query = sprintf(
					"	INSERT INTO		categories_filters_lang
						SET				categories_filters_lang.filterID = %d,
										categories_filters_lang.code = '%s',
										categories_filters_lang.name = '%s'",
					intval($filterID),
					$value['code'],
					parent::real_escape_string($data[1][$value['code'] . '_filter_name'][$key])
				);
				parent::query($query);
			}
		}

		
		return true;
	}
	
	
	
	/*
	**	Remove the categorye from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		categories
				WHERE			categories.categoryId = %d",
			$data[1]['categoryId']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		categories_lang
				WHERE			categories_lang.categoryId = %d",
			$data[1]['categoryId']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		categories_filters
				WHERE			categories_filters.categoryId = %d",
			$data[1]['categoryId']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		categories_filters_lang
				WHERE			categories_filters_lang.categoryId = %d",
			$data[1]['categoryId']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove a certain filter option from the DB.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteFilter($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		categories_filters
				WHERE			categories_filters.filterID = %d",
			$data[1]['filterID']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		categories_filters_lang
				WHERE			categories_filters_lang.filterID = %d",
			$data[1]['filterID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function front_viewBrands($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		brands.name
				FROM		categories_products
				INNER JOIN	products ON products.productID = categories_products.productID
				INNER JOIN	brands ON brands.brandID = products.brandID
				WHERE		categories_products.categoryID = %d
				GROUP BY	brands.name
				ORDER BY	brands.name",
			$data[0]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	data[0] =	categoryID
	*/
	
	public function front_filterValues($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		products_filters.value
				FROM		products_filters
				WHERE		products_filters.language = 'NL'
					AND		products_filters.filterID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		$return = array();
		
		while($row = parent::fetch_assoc($result))
		{
			if(!in_array($row['value'], $return))
			{
				$return[] = $row['value'];
			}
		}
		
		return $return;
	}
	
	
	
	/*
	**
	*/
	
	public function frontend_getStockType($data)
	{
		parent::_checkInputValues($data, 1);
		
		if($data[0] > 0)
		{
			$query = sprintf(
				"	SELECT		categories.stock_type
					FROM		categories
					WHERE		categories.categoryID = %d",
				$data[0]
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			return $row['stock_type'];
		}
		else
		{
			return 6;
		}
	}
}
?>