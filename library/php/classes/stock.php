<?php
class stock extends motherboard
{
	/*
	**	Create a view of the locations
	**	that are holding stocks.
	*/
	
	public function viewLocations($data)
	{
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		locations.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		locations.*,
							DATE_FORMAT(locations.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(locations.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(locations.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		locations
				WHERE		locations.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**
	*/
	
	public function viewReservations($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		SUM(orders_product.quantity) AS quantity,
							products.*
				FROM		orders_product
				INNER JOIN	products ON products.productID = orders_product.productID
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		products.merchantID = %d
					AND		order_statuses.finished = 0
					AND		order_statuses.declined = 0
				GROUP BY	orders_product.productID
				LIMIT		%s",
			$data[0],
			$data[1]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load a certain location.
	**	data[0]	=	locationID.
	*/
	
	public function loadLocation($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		locations.*,
							(
								SELECT		SUM(products_stock.stock)
								FROM		products_stock
								WHERE		products_stock.locationID = locations.locationID
							) AS stock
				FROM		locations
				WHERE		locations.locationID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save a stock location
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values.
	*/
	
	public function saveLocation($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->deleteLocation($data);
		}
		
		if($data[1]['webshop'] == 1)
		{
			$query = sprintf(
				"	UPDATE		locations
					SET			locations.webshop = 0
					WHERE		locations.merchantID = %d",
				$data[0]
			);
			parent::query($query);
		}
		
		if(isset($data[1]['locationID']) && $data[1]['locationID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		locations
					SET			locations.name = '%s',
								locations.pos_card = %d,
								locations.webshop = %d,
								locations.date_update = NOW()
					WHERE		locations.locationID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['pos_card']),
				intval($data[1]['webshop']),
				$data[1]['locationID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		locations
					SET				locations.merchantID = %d,
									locations.pos_card = %d,
									locations.webshop = %d,
									locations.name = '%s',
									locations.date_added = NOW()",
				$data[0],
				intval($data[1]['pos_card']),
				intval($data[1]['webshop']),
				parent::real_escape_string($data[1]['name'])
			);
			parent::query($query);
		}
		
		return true;	
	}
	
	
	
	/*
	**	Remove the location from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteLocation($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		SUM(products_stock.stock) AS counter
				FROM		products_stock
				WHERE		products_stock.locationID = %d",
			$data[1]['locationID']
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		if($row['counter'] == 0)
		{
			$query = sprintf(
				"	DELETE FROM		locations
					WHERE			locations.locationID = %d",
				$data[1]['locationID']
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Get the stock of a certain location. Ofcourse
	**	based on a given productID.
	**	data[0] = 	productID;
	**	data[1] = 	locationID.
	*/
	
	public function getStock($data)
	{
		parent::_checkInputValues($data, 2);
		
		$location = "";
		
		if($data[1] > 0)
		{
			$location = sprintf(
				"	AND		products_stock.locationID = %d",
				$data[1]
			);
		}
		
		$query = sprintf(
			"	SELECT		IF(products_stock.stock IS NOT NULL, products_stock.stock, 0) AS stock,
							(
								SELECT		SUM(orders_product.quantity)
								FROM		orders_product
								INNER JOIN	orders ON orders.orderID = orders_product.orderID
								INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
								WHERE		orders_product.productID = %d
									AND		order_statuses.finished = 0
									AND 	order_statuses.declined = 0
							) AS reserved
				FROM		products
				LEFT JOIN	products_stock ON products_stock.productID = products.productID
					%s
				WHERE		products.productID = %d",
			$data[0],
			$location,
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$row['stock'] = intval($row['stock']);
		$row['reserved'] = intval($row['reserved']);
		
		return $row;
	}
	
	
	
	/*
	** data[0] =	merchantID;
	** data[1] =	productID;
	** data[2] =	locationID
	*/
	
	public function getReserved($data)
	{
		parent::_checkInputValues($data, 3);
		
		$webshopLocation = $this->webshopLocation(array($data[0]));
		
		$query = sprintf(
			"	SELECT		SUM(orders_product.quantity) AS cnt
				FROM		orders_product
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				LEFT JOIN	pos_employees ON pos_employees.employeeID = orders.employeeID
				WHERE		order_statuses.finished = 0
					AND		order_statuses.declined = 0
					AND		(
								pos_employees.locationID = %d
						OR		(
									pos_employees.locationID IS NULL
							AND		%d = %d
								)
							)
					AND		orders_product.productID = %d",
			intval($data[2]),
			intval($data[2]),
			intval($webshopLocation),
			intval($data[1])
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['cnt'] > 0 ? $row['cnt'] : 0);
	}
	
	
	
	/*
	**	Update stock levels in a BULK way. Use a foreach
	**	to loop trough them and then use the normal update
	**	function to change to stock values.
	**	data[0] =	merchantID;
	**	data[1] =	POST values.
	*/
	
	public function updateStockBulk($data)
	{
		parent::_checkInputValues($data, 2);
		
		foreach($data[1]['stock_mutation'] AS $key => $stock_mutation)
		{
			if(intval($stock_mutation) != "")
			{
				parent::_runFunction("stock", "updateStock", array(intval($data[1]['productID']), intval($data[1]['stock_location'][$key]), $stock_mutation));
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Edit the stock. We're using a productID (ofcourse) and
	**	a locationID. Minus a INT is available, the query will
	**	handle that automaticly because minus comes before plus.
	**	data[0] =	productID;
	**	data[1] =	locationID;
	**	data[2] =	mutation.
	*/
	
	public function updateStock($data)
	{
		parent::_checkInputValues($data, 3);
		
		if($data[1] == 0)
		{
			die("No location provided. Unable to update stock.");
		}
		
		$query = sprintf(
			"	SELECT		products_stock.stock
				FROM		products_stock
				WHERE		products_stock.productID = %d
					AND		products_stock.locationID = %d",
			$data[0],
			$data[1]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result) == 0)
		{
			$row['stock'] = 0;
			
			$query = sprintf(
				"	INSERT INTO		products_stock
					SET				products_stock.productID = %d,
									products_stock.locationID = %d",
				$data[0],
				$data[1]
			);
			parent::query($query);
		}
		else
		{
			$row = parent::fetch_assoc($result);
		}
		
		
		$query = sprintf(
			"	UPDATE		products_stock
				SET			products_stock.stock = %d
				WHERE		products_stock.productID = %d
					AND		products_stock.locationID = %d",
			$row['stock'] + $data[2],
			$data[0],
			$data[1]
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Return all of the stock types in a array including
	**	the description loaded from the translate.
	*/
	
	public function viewStockType()
	{
		return array(
			1 => parent::_translateReturn("stock-types", "type-1"),
			2 => parent::_translateReturn("stock-types", "type-2"),
			3 => parent::_translateReturn("stock-types", "type-3"),
			4 => parent::_translateReturn("stock-types", "type-4"),
			5 => parent::_translateReturn("stock-types", "type-5"),
			6 => parent::_translateReturn("stock-types", "type-6"),
			7 => parent::_translateReturn("stock-types", "type-7"),
		);
	}
	
	
	
	/*
	**	Give the stockType a name. In the tables this is just
	**	a integer to save storage. The names are hardcoded in here.
	**	data[0] =	stock type INTEGER.
	*/
	
	public function renameStockType($data)
	{
		switch($data[0])
		{
			case 1:
				return parent::_translateReturn("stock-types", "type-1");
			break;
			
			case 2:
				return parent::_translateReturn("stock-types", "type-2");
			break;
			
			case 3:
				return parent::_translateReturn("stock-types", "type-3");
			break;
			
			case 4:
				return parent::_translateReturn("stock-types", "type-4");
			break;
			
			case 5:
				return parent::_translateReturn("stock-types", "type-5");
			break;
			
			case 6:
				return parent::_translateReturn("stock-types", "type-6");
			break;
			
			case 7:
				return parent::_translateReturn("stock-types", "type-7");
			break;
		}
	}

	
	
	
	/*
	**
	*/
	
	public function webshopLocation($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		locations.locationID
				FROM		locations
				WHERE		locations.webshop = 1
					AND		locations.merchantID = %d",
			$data
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['locationID'];
	}
}
?>