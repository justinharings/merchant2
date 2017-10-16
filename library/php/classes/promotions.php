<?php
class promotions extends motherboard
{
	/*
	**	Create a view of the products.
	**	data[0]	=	merchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed;
	**	data[4]	=	Type. 1 = Catalog, 2 = cart.
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 5);
		
		$search = "";
		
		if($data[1] != "")
		{
			if($data[1] != " ")
			{
				$search = sprintf(
					"	AND		promotions.name LIKE ('%%%s%%')",
					parent::real_escape_string($data[1])
				);
			}
		}
		
		$query = sprintf(
			"	SELECT		promotions.*,
							DATE_FORMAT(promotions.date_from, '%%d-%%m-%%Y') AS date_from,
							DATE_FORMAT(promotions.date_to, '%%d-%%m-%%Y') AS date_to,
							DATE_FORMAT(promotions.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							(
								SELECT		COUNT(promotions_products.productID)
								FROM		promotions_products
								WHERE		promotions_products.promotionID = promotions.promotionID
							) AS products,
							IF(
								DATE_FORMAT(promotions.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(promotions.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		promotions
				WHERE		promotions.merchantID = %d
					AND		promotions.type = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$data[4],
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
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		promotions.*,
							DATE_FORMAT(promotions.date_from, '%%d-%%m-%%Y') AS date_from,
							DATE_FORMAT(promotions.date_to, '%%d-%%m-%%Y') AS date_to
				FROM		promotions
				WHERE		promotions.promotionID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		promotions_products.*,
								products.name,
								products.price
					FROM		promotions_products
					INNER JOIN	products ON products.productID = promotions_products.productID
					WHERE		promotions_products.promotionID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['products'] = array();
			 
			if(parent::num_rows($result))
			{
				$return['products'] = parent::fetch_array($result);
			}
			
			return $return;
		}
		
		return array();
	}
	
	
	
	/*
	**
	*/
	
	public function saveCatalog($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}
		
		if(isset($data[1]['promotionID']) && $data[1]['promotionID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		promotions
					SET			promotions.name = '%s',
								promotions.date_from = '%s',
								promotions.date_to = '%s',
								promotions.date_update = NOW()
					WHERE		promotions.promotionID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::datevalue($data[1]['date_from']),
				parent::datevalue($data[1]['date_to']),
				$data[1]['promotionID']
			);
			$result = parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		promotions
					SET				promotions.merchantID = %d,
									promotions.name = '%s',
									promotions.type = 1,
									promotions.date_from = '%s',
									promotions.date_to = '%s',
									promotions.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::datevalue($data[1]['date_from']),
				parent::datevalue($data[1]['date_to'])
			);
			$result = parent::query($query);
			
			$data[1]['promotionID'] = parent::insert_id();
		}
		
		foreach($data[1]['productID'] AS $key => $productID)
		{
			if($data[1]['discount'][$key+1] == "")
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		promotions_products
					SET				promotions_products.productID = %d,
									promotions_products.promotionID = %d,
									promotions_products.discount_type = %d,
									promotions_products.discount = %d",
				$productID,
				$data[1]['promotionID'],
				$data[1]['type'][$key+1],
				$data[1]['discount'][$key+1]
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveCart($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}
		
		if(isset($data[1]['promotionID']) && $data[1]['promotionID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		promotions
					SET			promotions.name = '%s',
								promotions.date_from = '%s',
								promotions.date_to = '%s',
								promotions.date_update = NOW()
					WHERE		promotions.promotionID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::datevalue($data[1]['date_from']),
				parent::datevalue($data[1]['date_to']),
				$data[1]['promotionID']
			);
			$result = parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		promotions
					SET				promotions.merchantID = %d,
									promotions.name = '%s',
									promotions.type = 2,
									promotions.date_from = '%s',
									promotions.date_to = '%s',
									promotions.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::datevalue($data[1]['date_from']),
				parent::datevalue($data[1]['date_to'])
			);
			$result = parent::query($query);
			
			$data[1]['promotionID'] = parent::insert_id();
		}
		
		foreach($data[1]['productID'] AS $key => $productID)
		{
			if($data[1]['discount'][$key+1] == "")
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		promotions_products
					SET				promotions_products.productID = %d,
									promotions_products.promotionID = %d,
									promotions_products.discount_type = %d,
									promotions_products.discount = %d",
				$productID,
				$data[1]['promotionID'],
				$data[1]['type'][$key+1],
				$data[1]['discount'][$key+1]
			);
			parent::query($query);
		}
		
		return true;
	}

	
	
	
	/*
	**	Remove the album from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		promotions
				WHERE			promotions.promotionID = %d",
			$data[1]['promotionID']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		promotions_products
				WHERE			promotions_products.promotionID = %d",
			$data[1]['promotionID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function deleteProduct($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		promotions_products
				WHERE			promotions_products.promotionProductID = %d",
			$data[1]['itemID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function checkPromotion($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		promotions_products.*,
							products.price
				FROM		promotions_products
				INNER JOIN	products ON products.productID = promotions_products.productID
				WHERE		promotions_products.productID = %d
				LIMIT		0,1",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$row = parent::fetch_assoc($result);
			
			if($row['discount_type'] == 1 && $row['discount'] > 0 && $row['discount'] < 100)
			{
				return $row['price'] - number_format(($row['price']/100*$row['discount']), 2);
			}
			else if($row['discount_type'] == 2 && ($row['price']-$row['discount']) > 0)
			{
				return $row['price']-$row['discount'];
			}
		}
	}
}
?>