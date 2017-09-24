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
			"	SELECT		locations.locationID,
							locations.name,
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
	**	Load a certain location.
	**	data[0]	=	locationID.
	*/
	
	public function loadLocation($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		locations.*,
							(
								SELECT		COUNT(products_stock.productID)
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
		
		if(isset($data[1]['locationID']) && $data[1]['locationID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		locations
					SET			locations.name = '%s',
								locations.date_update = NOW()
					WHERE		locations.locationID = %d",
				parent::real_escape_string($data[1]['name']),
				$data[1]['locationID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		locations
					SET				locations.merchantID = %d,
									locations.name = '%s',
									locations.date_added = NOW()",
				$data[0],
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
			"	SELECT		COUNT(products_stock.productID) AS counter
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
			"	SELECT		products_stock.stock
				FROM		products_stock
				WHERE		products_stock.productID = %d
					%s",
			$data[0],
			$location
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$row['stock'] = intval($row['stock']);
		
		return $row;
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
}
?>