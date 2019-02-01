<?php
class reports extends motherboard
{
	function getWeekData($week, $year)
	{	
		$dto = new DateTime();

		$dto->setISODate($year, $week);
		$ret[0] = $dto->format('Y-m-d');
		$dto->modify('+6 days');
		$ret[1] = $dto->format('Y-m-d');
		
		return $ret;
	}
	
	public function saveRegister($data)
	{
		$query = sprintf(
			"	INSERT INTO		register
				SET				register.merchantID = %d,
								register.paymentID = %d,
								register.date = '%s',
								register.amount = '%.2f'",
			$data[0],
			$data[1]['paymentID'],
			parent::datevalue($data[1]['date']),
			$data[1]['amount']
		);
		parent::query($query);
		
		return true;
	}
	
	public function loadRegisterChanges($data)
	{
		$period = false;
		
		if(strpos($data[1], "week_") !== false)
		{
			$period = true;
			
			$dates = str_replace("week_", "", $data[1]);
			$dates = explode("_", $dates);
			
			$year = $dates[1];
			$week = $dates[0];
			
			$periods = $this->getWeekData($week, $year);
		}
		else if(strpos($data[1], "month_") !== false)
		{
			$period = true;
			
			$dates = str_replace("month_", "", $data[1]);
			$dates = explode("_", $dates);
			
			$year = $dates[1];
			$month = $dates[0];
			
			$periods = $this->getWeekData($week, $year);
			$periods[0] = $year . "-" . $month . "-01";
			$periods[1] = $year . "-" . $month . "-31";
		}

		
		$query = sprintf(
			"	SELECT		register.*,
							payment_methods.name AS payment_method
				FROM		register
				INNER JOIN	payment_methods ON payment_methods.paymentID = register.paymentID
				WHERE		register.merchantID = %d
					AND		%s",
			$data[0],
			($period == false ? "register.date = '" . parent::datevalue($data[1]) . "'" : "register.date BETWEEN '" . $periods[0] . "' AND '" . $periods[1] . "'")
		);
		$result = parent::query($query);
		
		return parent::fetch_array($result);
	}
	
	public function findComplete($data)
	{
		$query = sprintf(
			"	SELECT		register_close.closeID
				FROM		register_close
				WHERE		register_close.merchantID = %d
					AND		register_close.date = '%s'",
			$data[0],
			parent::datevalue($data[1])
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['closeID'];
	}
	
	public function completeRegister($data)
	{
		$query = sprintf(
			"	INSERT INTO		register_close
				SET				register_close.merchantID = %d,
								register_close.date = '%s'",
			$data[0],
			parent::datevalue($data[1]['date'])
		);
		parent::query($query);
		
		return true;
	}
	
	public function closeRegister($data)
	{
		parent::_checkInputValues($data, 2);
		
		$today = date("Y-m-d");
		$period = false;
		
		$date  = new DateTime($today);
		
		if($data[1] == "yesterday")
		{
			$interval = new DateInterval('P1D');
			$date->sub($interval); 
		}
		else if(strpos($data[1], "week_") !== false)
		{
			$period = true;
			
			$dates = str_replace("week_", "", $data[1]);
			$dates = explode("_", $dates);
			
			$year = $dates[1];
			$week = $dates[0];
			
			$periods = $this->getWeekData($week, $year);
		}
		else if(strpos($data[1], "month_") !== false)
		{
			$period = true;
			
			$dates = str_replace("month_", "", $data[1]);
			$dates = explode("_", $dates);
			
			$year = $dates[1];
			$month = $dates[0];
			
			$periods = $this->getWeekData($week, $year);
			$periods[0] = $year . "-" . $month . "-01";
			$periods[1] = $year . "-" . $month . "-31";
		}
		else if(strpos($data[1], "day_") !== false)
		{
			$period = false;
			
			$date = str_replace("day_", "", $data[1]);
			
			$date = explode("-", $date);
			
			$year = $date[2];
			$month = $date[1];
			$day = $date[0];
			
			$date  = new DateTime($year . "-" . $month . "-" . $day);
		}
		
		$query = sprintf(
			"	SELECT		orders_payment.amount,
							payment_methods.name,
							orders_payment.date
				FROM		orders_payment
				INNER JOIN	payment_methods ON payment_methods.paymentID = orders_payment.paymentID
				WHERE		payment_methods.merchantID = %d
					AND		%s",
			$data[0],
			($period == false ? "orders_payment.date = '" . $date->format("Y-m-d") . "'" : "orders_payment.date BETWEEN '" . $periods[0] . "' AND '" . $periods[1] . "'")
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
		
		$query = sprintf(
			"	SELECT		orders_payment.*,
							orders.grand_total,
							orders.payed
				FROM		orders_payment
				INNER JOIN	orders ON orders.orderID = orders_payment.orderID
				WHERE		orders.merchantID = %d
					AND		MONTH(orders_payment.date) = %d
					AND		YEAR(orders_payment.date) = %d",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		
		$return = array();
		$num = 0;
		
		while($row = parent::fetch_assoc($result))
		{
			$calc_items = array();
			$calc_num = 0;
			
			$queryProducts = sprintf(
				"	SELECT		orders_product.*,
								groups.name AS groupName
					FROM		orders_product
					INNER JOIN	products ON products.productID = orders_product.productID
					LEFT JOIN	groups ON groups.groupID = products.groupID
					WHERE		orders_product.orderID = %d",
				$row['orderID']
			);
			$resultProducts = parent::query($queryProducts);
			
			while($rowProducts = parent::fetch_assoc($resultProducts))
			{
				$price = ($rowProducts['quantity']*$rowProducts['price']);
				
				$calc_items[$calc_num]['name'] = $rowProducts['name'];
				$calc_items[$calc_num]['productID'] = $rowProducts['productID'];
				$calc_items[$calc_num]['group'] = $rowProducts['groupName'];
				$calc_items[$calc_num]['price'] = $price;
				$calc_items[$calc_num]['quantity'] = 0;
				
				$calc_num++;
			}
			
			
			
			$queryShipments = sprintf(
				"	SELECT		orders_shipment.*
					FROM		orders_shipment
					WHERE		orders_shipment.orderID = %d",
				$row['orderID']
			);
			$resultShipments = parent::query($queryShipments);
			
			while($rowShipments = parent::fetch_assoc($resultShipments))
			{
				$price = $rowShipments['price'];
				
				if($price > 0)
				{
					$calc_items[$calc_num]['name'] = "Verzendkosten";
					$calc_items[$calc_num]['productID'] = 0;
					$calc_items[$calc_num]['group'] = "Verzendkosten";
					$calc_items[$calc_num]['price'] = $price;
					$calc_items[$calc_num]['quantity'] = 0;
					
					$calc_num++;
				}
			}
			
			
			
			foreach($calc_items AS $key => $value)
			{
				$percentage = $row['grand_total'] / 100;
				$percentage = $value['price'] / $percentage;
				
				$return[$num]['name'] = $value['name'];
				$return[$num]['productID'] = $value['productID'];
				$return[$num]['group'] = $value['group'];
				$return[$num]['grand_total'] = (($row['amount']/100)*$percentage);
				$return[$num]['quantity'] = $value['quantity'];
				
				$num++;
			}
		}
		
		$groups = $this->_runFunction("groups", "view", array($data[0], "", "groups.name", "0,9999"));
		$quantity = array();
		
		foreach($groups AS $group)
		{
			$query = sprintf(
				"	SELECT		SUM(orders_product.quantity) AS cnt
					FROM		orders_product
					INNER JOIN	products ON products.productID = orders_product.productID
					INNER JOIN	orders ON orders.orderID = orders_product.orderID
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		products.groupID = %d
						AND		orders.merchantID = %d
						AND		order_statuses.finished = 1
						AND		order_statuses.declined = 0
						AND 	MONTH(orders.date_added) = %d
						AND		YEAR(orders.date_added) = %d",	
				$group['groupID'],
				$data[0],
				$data[1],
				$data[2]
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$quantity[$group['name']] = $row['cnt'];
		}
		
		
		
		$query = sprintf(
			"	SELECT		COUNT(orders_shipment.orderID) AS cnt
				FROM		orders_shipment
				INNER JOIN	orders ON orders.orderID = orders_shipment.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		orders.merchantID = %d
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND 	MONTH(orders.date_added) = %d
					AND		YEAR(orders.date_added) = %d
					AND		orders_shipment.price > 0",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$quantity["Verzendkosten"] = $row['cnt'];
		
		
		
		$had = array();
		
		foreach($return AS $key => $value)
		{
			if(!in_array($value['group'], $had))
			{
				$return[$key]['quantity'] = $quantity[$value['group']];
				$had[] = $value['group'];
			}
		}
		
		return $return;
		
		
		
		/*
		// $groups = $this->_runFunction("groups", "view", array($data[0], "", "groups.name", "0,9999"));
		
		$return = array();
		$num = 0;
		
		
		$query = sprintf(
			"	SELECT		orders_product.quantity,
							orders_product.price,
							products.groupID,
							groups.name AS group_name
				FROM		orders_product
				INNER JOIN	products ON products.productID = orders_product.productID
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				LEFT JOIN	groups ON groups.groupID = products.groupID
				WHERE		orders.merchantID = %d
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND 	MONTH(orders.date_added) = %d
					AND		YEAR(orders.date_added) = %d
				ORDER BY	products.groupID",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		
		while($row = parent::fetch_assoc($result))
		{
			$return[$num]['group'] = $row['group_name'];
			$return[$num]['grand_total'] = ($row['quantity']*$row['price']);
			$return[$num]['quantity'] = $row['quantity'];
			
			$num++;
		}
		
		$query = sprintf(
			"	SELECT		SUM(orders_shipment.price) AS total
				FROM		orders_shipment
				INNER JOIN	orders ON orders.orderID = orders_shipment.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		orders.merchantID = %d
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND 	MONTH(orders.date_added) = %d
					AND		YEAR(orders.date_added) = %d",
			$data[0],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$row = parent::fetch_assoc($result);
			
			$return[$num]['group'] = "Verzendkosten";
			$return[$num]['grand_total'] = $row['total'];
			$return[$num]['quantity'] = $row['quantity'];
		}
		
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
						AND 	MONTH(orders.date_added) = %d
						AND		YEAR(orders.date_added) = %d",	
				$group['groupID'],
				$data[1],
				$data[2]
			);
			
			//print "<pre>" . $query . "</pre>";
			
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return[$num]['group'] = $group['name'];
			$return[$num]['grand_total'] = $row['amnt'];
			$return[$num]['quantity'] = ($row['cnt'] > 0 ? $row['cnt'] : 0);
			
			$num++;
		}
		
		$query = sprintf(
			"	SELECT		SUM(orders_product.quantity) AS cnt,
							SUM(orders_product.price) AS amnt
				FROM		orders_product
				INNER JOIN	products ON products.productID = orders_product.productID
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		products.groupID = 0
					AND		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND 	MONTH(orders.date_added) = %d
					AND		YEAR(orders.date_added) = %d",	
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$return[$num]['group'] = "Overige / Niet bepaald";
		$return[$num]['grand_total'] = $row['amnt'];
		$return[$num]['quantity'] = ($row['cnt'] > 0 ? $row['cnt'] : 0);
		*/
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