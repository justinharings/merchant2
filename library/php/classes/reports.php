<?php
class reports extends motherboard
{
	public function closeRegister($data)
	{
		parent::_checkInputValues($data, 2);
		
		$today = date("Y-m-d");
		
		$date  = new DateTime($today);
		
		if($data[1] == "yesterday")
		{
			$interval = new DateInterval('P1D');
			$date->sub($interval); 
		}
		
		$query = sprintf(
			"	SELECT		orders_payment.amount,
							payment_methods.name
				FROM		orders_payment
				INNER JOIN	payment_methods ON payment_methods.paymentID = orders_payment.paymentID
				WHERE		payment_methods.merchantID = %d
					AND		orders_payment.date = '%s'",
			$data[0],
			$date->format("Y-m-d")
		);
		$result = parent::query($query);
		
		$payments = array();
		
		while($row = parent::fetch_assoc($result))
		{
			if(!isset($payments[$row['name']]))
			{
				$payments[$row['name']] = $row['amount'];
			}
			else
			{
				$payments[$row['name']] = $payments[$row['name']] + $row['amount'];
			}
		}
		
		
		$query = sprintf(
			"	SELECT		orders_product.orderID,
							orders_product.quantity,
							products.groupID
				FROM		orders_product
				INNER JOIN	products ON products.productID = orders_product.productID
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		orders.merchantID = %d
					AND		DATE(orders.date_added) = '%s'
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0",
			$data[0],
			$date->format("Y-m-d")
		);
		$result = parent::query($query);
		
		$groups = array();
		
		while($row = parent::fetch_assoc($result))
		{
			$query2 = sprintf(
				"	SELECT		groups.name
					FROM		groups
					WHERE		groups.groupID = %d",
				$row['groupID']
			);
			$result2 = parent::query($query2);
			$row2 = parent::fetch_assoc($result2);
			
			if(!isset($groups[$row2['name']]))
			{
				$groups[$row2['name']] = $row['quantity'];
			}
			else
			{
				$groups[$row2['name']] = $groups[$row2['name']] + $row['quantity'];
			}
		}
		
		return array(0 => $payments, 1 => $groups);
	}
	
	
	
	/*
	** data[0] =	merchantID;
	** data[1] =	month;
	** data[2] =	year.
	*/
	
	public function viewArticleGroups($data)
	{
		parent::_checkInputValues($data, 3);
		
		$groups = $this->_runFunction("groups", "view", array($data[0], "", "groups.name", "0,9999"));
		
		$return = array();
		$num = 0;
		
		foreach($groups AS $group)
		{
			$query = sprintf(
				"	SELECT		SUM(orders_product.quantity) AS cnt,
								SUM(orders_product.price) AS amnt
					FROM		orders_product
					INNER JOIN	products ON products.productID = orders_product.productID
					INNER JOIN	orders ON orders.orderID = orders_product.orderID
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		products.groupID = %d
						AND		order_statuses.finished = 1
						AND		order_statuses.declined = 0
						AND		MONTH(orders.date_added) = %d
						AND		YEAR(orders.date_added) = %d",	
				$group['groupID'],
				$data[1],
				$data[2]
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return[$num]['group'] = $group['name'];
			$return[$num]['grand_total'] = $row['amnt'];
			$return[$num]['quantity'] = ($row['cnt'] > 0 ? $row['cnt'] : 0);
			
			$num++;
		}
		
		return $return;
	}
	
	
	
	/*
	**
	*/
	
	public function viewArticleSuppliers($data)
	{
		parent::_checkInputValues($data, 3);
		
		$brands = $this->_runFunction("brands", "view", array($data[0], "", "brands.name", "0,9999"));
		
		$return = array();
		$num = 0;
		
		foreach($brands AS $brand)
		{
			$query = sprintf(
				"	SELECT		SUM(orders_product.quantity) AS cnt,
								SUM(orders_product.price) AS amnt
					FROM		orders_product
					INNER JOIN	products ON products.productID = orders_product.productID
					INNER JOIN	orders ON orders.orderID = orders_product.orderID
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		products.brandID = %d
						AND		order_statuses.finished = 1
						AND		order_statuses.declined = 0
						AND		MONTH(orders.date_added) = %d
						AND		YEAR(orders.date_added) = %d",	
				$brand['brandID'],
				$data[1],
				$data[2]
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return[$num]['brand'] = $brand['name'];
			$return[$num]['grand_total'] = $row['amnt'];
			$return[$num]['quantity'] = ($row['cnt'] > 0 ? $row['cnt'] : 0);
			
			$num++;
		}
		
		return $return;
	}
	
	
	
	/*
	**
	*/
	
	public function viewPaymentBook($data)
	{
		parent::_checkInputValues($data, 3);
		
		$return = array();
		$num = 0;
		
		$query = sprintf(
			"	SELECT		orders_payment.*,
							DATE_FORMAT(orders_payment.date, '%%d-%%m-%%Y') AS date,
							payment_methods.name AS method,
							customers.name AS customer,
							orders.orderID
				FROM		orders_payment
				INNER JOIN	payment_methods ON payment_methods.paymentID = orders_payment.paymentID
				INNER JOIN	orders ON orders.orderID = orders_payment.orderID
				LEFT JOIN	customers ON customers.customerID = orders.customerID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		orders.merchantID = %d
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND		MONTH(orders_payment.date) = %d
					AND		YEAR(orders_payment.date) = %d
				ORDER BY	orders_payment.date",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		
		while($row = parent::fetch_assoc($result))
		{
			$return[$num]['grand_total'] = $row['amount'];
			$return[$num]['date'] = $row['date'];
			$return[$num]['method'] = $row['method'];
			$return[$num]['customer'] = $row['customer'];
			$return[$num]['orderID'] = $row['orderID'];
			
			$num++;
		}
		
		return $return;
	}
	
	
	
	/*
	**
	*/
	
	public function viewStockReport($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT 		products.*,
							groups.name AS group_name,
							(
								SELECT		SUM(products_stock.stock) AS stock
								FROM		products_stock
								WHERE		products_stock.productID = products.productID
							) AS stock,
							(
								SELECT		SUM(products_stock.stock) AS stock
								FROM		products_stock
								WHERE		products_stock.productID = products.productID
							) * products.price_purchase AS money
				FROM 		products
				INNER JOIN 	products_stock ON products_stock.productID = products.productID 
				LEFT JOIN	groups ON groups.groupID = products.groupID
				WHERE 		products_stock.stock > 0
					AND		products.merchantID = %d
					AND		products.deleted = 0",
			$data[0]
		);
		$result = parent::query($query);
			
		$return = array();
		
		while($row = parent::fetch_assoc($result))
		{
			if($row['group_name'] == "")
			{
				$row['group_name'] = "Overigen / Niet bepaald";
			}
			
			if(!is_array($return[$row['group_name']]))
			{
				$return[$row['group_name']]['grand_total'] = $row['money'];
				$return[$row['group_name']]['quantity'] = ($row['stock'] > 0 ? $row['stock'] : 0);
			}
			else
			{
				$return[$row['group_name']]['grand_total'] = (floatval($return[$row['group_name']]['grand_total']) + $row['money']);
				$return[$row['group_name']]['quantity'] = (intval($return[$row['group_name']]['quantity']) + ($row['stock'] > 0 ? $row['stock'] : 0));
			}
		}
		
		return $return;
	}
}
?>