<?php
class orders extends motherboard
{
	/*
	**	Create a view of the percentage.
	**	data[0]	=	merchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 5);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								orders.orderID = %d
						OR		customers.name LIKE ('%%%s%%')
						OR		orders_invoice_rules.value LIKE ('%%%s%%')
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$type = "";
		
		if($data[4] > 0)
		{
			switch($data[4])
			{
				case 0:
					$type = "";
				break;
				
				case 1:
					$type = " AND	order_statuses.finished = 0 AND order_statuses.declined = 0";
				break;
				
				case 2:
					$type = " AND	order_statuses.finished = 1 AND order_statuses.declined = 0";
				break;
				
				case 3:
					$type = " AND	order_statuses.finished = 1 AND order_statuses.declined = 1";
				break;
				
				case 4:
					$type = "	AND		order_statuses.finished = 1 AND order_statuses.declined = 0
								AND		orders.payed != orders.grand_total";
				break;
			}
		}
		
		$query = sprintf(
			"	SELECT		orders.*,
							CONCAT(YEAR(orders.date_added), orders.orderID) AS order_reference,
							order_statuses.name AS status,
							IF(customers.name IS NULL, 'Kassa verkoop', customers.name) AS customer_name,
							DATE_FORMAT(orders.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(orders.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(orders.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		orders
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				%s
				LEFT JOIN	customers ON customers.customerID = orders.customerID
				WHERE		orders.merchantID = %d
					%s
					%s
				GROUP BY	orders.orderID
				ORDER BY	%s
				LIMIT		%s",
			($search != "" ? "INNER JOIN orders_invoice_rules ON orders_invoice_rules.orderID = orders.orderID" : ""),
			$data[0],
			$type,
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load a certain percentage.
	**	data[0]	=	orderID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		orders.*,
							CONCAT(YEAR(orders.date_added), orders.orderID) AS order_reference,
							DATE_FORMAT(orders.date_added, '%%d-%%m-%%Y om %%k:%%i uur') AS date_added,
							IF(locations.locationID IS NOT NULL, locations.name, 'Webwinkel bestelling') AS location
				FROM		orders
				LEFT JOIN	pos_employees ON pos_employees.employeeID = orders.employeeID
				LEFT JOIN	locations ON locations.locationID = pos_employees.locationID
				WHERE		orders.orderID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
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
				"	SELECT		customers.*
					FROM		customers
					INNER JOIN	orders ON orders.customerID = customers.customerID
					WHERE		orders.orderID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['customer'] = array();
			
			if(parent::num_rows($result))
			{
				$row = parent::fetch_assoc($result);
				
				$row['street'] = "";
				$row['housenumber'] = "";
				
				if(preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $row['address'], $matches))
				{
					$row['street'] = $matches['address'];
					$row['housenumber'] = $matches['number'];
				}
				
				$return['customer'] = $row;
			}
			
			
			$query = sprintf(
				"	SELECT		%s
								orders_product.*,
								LPAD(products.article_code, 5, 0) AS article_code,
								products.barcode,
								products.visibility,
								products.deleted,
								products.name AS original_name
					FROM		orders_product
					INNER JOIN	products ON products.productID = orders_product.productID
					WHERE		orders_product.orderID = %d",
				$languages,
				$data[0]
			);
			$result = parent::query($query);
			
			$return['products'] = array();
			
			if(parent::num_rows($result))
			{
				$return['products'] = parent::fetch_array($result);
			}
			
			
			$query = sprintf(
				"	SELECT		orders_shipment.*,
								shipment_methods.name AS method,
								shipment_methods.price AS normal_price
					FROM		orders_shipment
					INNER JOIN	shipment_methods ON shipment_methods.shipmentID = orders_shipment.shipmentID
					WHERE		orders_shipment.orderID = %d
					ORDER BY	orders_shipment.orderShipmentID DESC",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['shipments'] = array();
			$last_barcode = "";
			
			if(parent::num_rows($result))
			{
				$return['shipments'] = parent::fetch_array($result);
				
				while($sRow = parent::fetch_assoc($result))
				{
					if($sRow['courier'] == "PostNL")
					{
						$last_barcode = "https://jouw.postnl.nl/#!/track-en-trace/" . $sRow['track_code'] . "/NL/" . $return['customer']['zip_code'];
					}
					
					break;
				}
			}
			
			$return['last_barcode'] = $last_barcode;
			
			$query = sprintf(
				"	SELECT		orders_invoice_rules.*
					FROM		orders_invoice_rules
					WHERE		orders_invoice_rules.orderID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['invoice_rules'] = array();
			
			if(parent::num_rows($result))
			{
				while($row = parent::fetch_assoc($result))
				{
				    $return['invoice_rules'][] = $row;
				}
			}
			
			
			$query = sprintf(
				"	SELECT		orders_payment.*,
								payment_methods.name AS method,
								DATE_FORMAT(orders_payment.date, '%%d-%%m-%%Y') AS date
					FROM		orders_payment
					INNER JOIN	payment_methods ON payment_methods.paymentID = orders_payment.paymentID
					WHERE		orders_payment.orderID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['payments'] = array();
			
			if(parent::num_rows($result))
			{
				$return['payments'] = parent::fetch_array($result);
			}
			
			
			$query = sprintf(
				"	SELECT		COUNT(orders.orderID) AS cnt
					FROM		orders
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		order_statuses.declined = 0
						AND		orders.orderID != %d
						AND		orders.customerID = %d
						AND		orders.merchantID = %d",
				$data[0],
				$return['customer']['customerID'],
				$return['merchantID']
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return['customer']['count_orders'] = $row['cnt'];
			
			
			$query = sprintf(
				"	SELECT		SUM(orders.grand_total) AS cnt
					FROM		orders
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		order_statuses.declined = 0
						AND		orders.orderID != %d
						AND		orders.customerID = %d
						AND		orders.merchantID = %d",
				$data[0],
				$return['customer']['customerID'],
				$return['merchantID']
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return['customer']['total_orders'] = $row['cnt'];
			
			return $return;
		}
		
		return array();
	}
	
	
	
	/*
	**
	*/
	
	private function calcVatTotal($orderID)
	{
		$query = sprintf(
			"	SELECT		orders_product.quantity,
							orders_product.price,
							orders_product.taxrate
				FROM		orders_product
				WHERE		orders_product.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		
		$vat_total = 0;
		
		while($row = parent::fetch_assoc($result))
		{
			$calc = $row['price'] / (1+($row['taxrate']/100));
			$calc = number_format(($row['price'] - $calc), 2);
			
			$vat_total += ($row['quantity'] * $calc);
		}
		
		
		$query = sprintf(
			"	SELECT		orders_shipment.price,
							taxes.percentage
				FROM		orders_shipment
				INNER JOIN	shipment_methods ON shipment_methods.shipmentID = orders_shipment.shipmentID
				INNER JOIN	taxes ON taxes.taxesID = shipment_methods.taxesID
				WHERE		orders_shipment.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		
		while($row = parent::fetch_assoc($result))
		{
			$calc = $row['price'] / (1+($row['percentage']/100));
			$vat_total += number_format(($row['price'] - $calc), 2);
		}
		
		return $vat_total;
	}
	
	
	
	/*
	**
	*/
	
	private function calcTotal($orderID)
	{
		$query = sprintf(
			"	SELECT		SUM(orders_product.quantity*orders_product.price) AS price
				FROM		orders_product
				WHERE		orders_product.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$products = $row['price'];
		
		$query = sprintf(
			"	SELECT		SUM(orders_shipment.price) AS price
				FROM		orders_shipment
				WHERE		orders_shipment.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$shipment = $row['price'];
		
		return ($products+$shipment);
	}
	
	
	
	/*
	**
	*/
	
	private function calcRemainingTotal($orderID)
	{
		$query = sprintf(
			"	SELECT		SUM(orders_product.quantity*orders_product.price) AS price
				FROM		orders_product
				WHERE		orders_product.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$products = $row['price'];
		
		$query = sprintf(
			"	SELECT		SUM(orders_shipment.price) AS price
				FROM		orders_shipment
				WHERE		orders_shipment.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$shipment = $row['price'];
		
		$query = sprintf(
			"	SELECT		orders.payed
				FROM		orders
				WHERE		orders.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return (($products+$shipment) - $row['payed']);
	}
	
	
	
	/*
	**
	*/
	
	private function calcPayed($orderID)
	{
		$query = sprintf(
			"	SELECT		SUM(orders_payment.amount) AS amount
				FROM		orders_payment
				WHERE		orders_payment.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['amount'];
	}
	
	
	
	/*
	**	Save or update a percentage. If 'delete' is set
	**	in the post values, continue to the delete function.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function save($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}

		if(isset($data[1]['orderID']) && $data[1]['orderID'] != 0)
		{
			$query = sprintf(
				"	DELETE FROM		orders_product
					WHERE			orders_product.orderID = %d",
				$data[1]['orderID']
			);
			parent::query($query);
			
			foreach($data[1]['orderProductID'] AS $key => $orderProductID)
			{
				$mKey = $key;
				
				$productID = $data[1]['productID'][$mKey];
				$taxrate = $data[1]['taxrate'][$mKey];
				$name = $data[1]['name'][$mKey];
				$quantity = $data[1]['quantity'][$mKey];
				$price = $data[1]['price'][$mKey];
				
				if($name == "")
				{
					continue;
				}
				
				$query = sprintf(
					"	INSERT INTO		orders_product
						SET				orders_product.orderID = %d,
										orders_product.productID = %d,
										orders_product.name = '%s',
										orders_product.price = '%.2f',
										orders_product.taxrate = '%.2f',
										orders_product.quantity = %d",
					$data[1]['orderID'],
					$productID,
					parent::real_escape_string($name),
					$price,
					$taxrate,
					$quantity
				);
				parent::query($query);
			}
			
			
			$current = $this->load(array($data[1]['orderID']));
			
			$merchantID = $data[0];
			$orderID = $data[1]['orderID'];
			$currentStatus = $current['statusID'];
			$newStatus = $data[1]['statusID'];
			$employeeID = $current['employeeID'];
			
			$this->handleStock(array($merchantID, $orderID, $currentStatus, $newStatus, $employeeID));
			
			
			
			$query = sprintf(
				"	DELETE FROM		orders_shipment
					WHERE			orders_shipment.orderID = %d",
				$data[1]['orderID']
			);
			parent::query($query);
			
			foreach($data[1]['shipmentID'] AS $key => $shipmentID)
			{
				$courier = $data[1]['courier'][$key];
				$track_code = $data[1]['track_code'][$key];
				$ship_price = $data[1]['ship_price'][$key];
				
				if($shipmentID < 1)
				{
					continue;
				}
				
				$query = sprintf(
					"	INSERT INTO		orders_shipment
						SET				orders_shipment.orderID = %d,
										orders_shipment.shipmentID = %d,
										orders_shipment.courier = '%s',
										orders_shipment.track_code = '%s',
										orders_shipment.price = '%.2f'",
					$data[1]['orderID'],
					$shipmentID,
					$courier,
					$track_code,
					$ship_price
				);
				parent::query($query);
			}
			
			$query = sprintf(
				"	DELETE FROM		orders_payment
					WHERE			orders_payment.orderID = %d",
				$data[1]['orderID']
			);
			parent::query($query);
			
			foreach($data[1]['paymentID'] AS $key => $paymentID)
			{
				$amount = $data[1]['amount'][$key];
				$date = $data[1]['date'][$key];
				
				if($paymentID < 1)
				{
					continue;
				}
				
				$query = sprintf(
					"	INSERT INTO		orders_payment
						SET				orders_payment.orderID = %d,
										orders_payment.paymentID = %d,
										orders_payment.amount = '%.2f',
										orders_payment.date = '%s'",
					$data[1]['orderID'],
					$paymentID,
					$amount,
					parent::datevalue($date)
				);
				parent::query($query);
			}
			
			$query = sprintf(
				"	DELETE FROM		orders_invoice_rules
					WHERE			orders_invoice_rules.orderID = %d",
				$data[1]['orderID']
			);
			parent::query($query);
			
			for($i = 1; $i <= 4; $i++)
			{
				$query = sprintf(
					"	INSERT INTO		orders_invoice_rules
						SET				orders_invoice_rules.orderID = %d,
										orders_invoice_rules.key = '%s',
										orders_invoice_rules.value = '%s'",
					$data[1]['orderID'],
					parent::real_escape_string($data[1]['key_' . $i]),
					parent::real_escape_string($data[1]['value_' . $i])
				);
				parent::query($query);
			}
			
			$query = sprintf(
				"	UPDATE		orders
					SET			orders.grand_total = '%.2f',
								orders.vat_total = '%.2f',
								orders.payed = '%.2f',
								orders.statusID = %d,
								orders.date_update = NOW()
					WHERE		orders.orderID = %d",
				$this->calcTotal($data[1]['orderID']),
				$this->calcVatTotal($data[1]['orderID']),
				$this->calcPayed($data[1]['orderID']),
				$data[1]['statusID'],
				$data[1]['orderID']
			);
			parent::query($query);
			
			
			$status = $this->_runFunction("order_statuses", "load", array($data[1]['statusID']));
			
			if($status['shipment_email'] && ($data[1]['statusID'] != $currentStatus))
			{
				$customerData = $this->_runFunction("customers", "load", array($current['customerID']));
				
				$array = array();
				$array[] = $data[0];
				$array[] = 7;
				$array[] = ($customerData['email_address'] != "" ? $customerData['email_address'] : "");
				$array[] = 0;
				$array[] = $data[1]['orderID'];
				
				$this->_runFunction("mailserver", "sendAllEmail", $array);
			}
			
			
			if($data[1]['omboeken'] == 1)
			{
				$this->bookToday(array(intval($data[1]['orderID'])));
			}
		}
		
		return true;
	}
	
	
	
	public function bookToday($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	UPDATE		orders
				SET			orders.date_added = NOW()
				WHERE		orders.orderID = %d",
			$data[0]
		);
		parent::query($query);
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
	**	
	*/
	
	public function deleteProduct($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		orders_product.orderID
				FROM		orders_product
				WHERE		orders_product.orderProductID = %d",
			$data[1]['itemID']
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$query = sprintf(
			"	DELETE FROM		orders_product
				WHERE			orders_product.orderProductID = %d",
			$data[1]['itemID']
		);
		parent::query($query);
		
		$query = sprintf(
				"	UPDATE		orders
					SET			orders.grand_total ='%.2f',
								orders.date_update = NOW()
					WHERE		orders.orderID = %d",
				$this->calcTotal($row['orderID']),
				$row['orderID']
			);
			parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	
	*/
	
	public function deleteShipment($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		orders_shipment.orderID
				FROM		orders_shipment
				WHERE		orders_shipment.orderShipmentID = %d",
			$data[1]['itemID']
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$query = sprintf(
			"	DELETE FROM		orders_shipment
				WHERE			orders_shipment.orderShipmentID = %d",
			$data[1]['itemID']
		);
		parent::query($query);
		
		$query = sprintf(
				"	UPDATE		orders
					SET			orders.grand_total ='%.2f',
								orders.date_update = NOW()
					WHERE		orders.orderID = %d",
				$this->calcTotal($row['orderID']),
				$row['orderID']
			);
			parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	
	*/
	
	public function deletePayment($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		orders_payment.orderID
				FROM		orders_payment
				WHERE		orders_payment.orderPaymentID = %d",
			$data[1]['itemID']
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$query = sprintf(
			"	DELETE FROM		orders_payment
				WHERE			orders_payment.orderPaymentID = %d",
			$data[1]['itemID']
		);
		parent::query($query);
		
		$query = sprintf(
			"	UPDATE		orders
				SET			orders.payed ='%.2f',
							orders.date_update = NOW()
				WHERE		orders.orderID = %d",
			$this->calcPayed($row['orderID']),
			$row['orderID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	data[0] =	merchantID;
	**	data[1] =	orderID;
	**	data[2] =	Current status ID;
	**	data[3] =	New status ID;
	**	data[4] =	employeeID.
	*/
	
	private function handleStock($data)
	{
		parent::_checkInputValues($data, 5);
		
		
		$order = $this->load(array($data[1]));
		
		$current = $this->_runFunction("order_statuses", "load", array($data[2]));
		$new = $this->_runFunction("order_statuses", "load", array($data[3]));
		
		
		$locationID = 0;
		
		if($data[4] > 0)
		{
			$employee = $this->_runFunction("pos", "loadEmployee", array($data[4]));
			$locationID = $employee['locationID'];
		}
		else
		{
			$locationID = $this->_runFunction("stock", "webshopLocation", array($data[0]));
		}
		
		//print "Start update...<br/><br/>";
		//print "Current: Finished(" . $current['finished'] . "), Declined(" . $current['declined'] . ")<br/>";
		//print "New: Finished(" . $new['finished'] . "), Declined(" . $new['declined'] . ")<br/><br/>";
		
		
		foreach($order['products'] AS $product)
		{
			if	(
					$data[2] == 0
					&& $new['finished'] == 1
					&& $new['declined'] == 0
				)
			{
				// Element 1
				//print "Entered element 1.<Br/>";
				//print "New order. Finished immediately.<br/><br/>";
				
				$array = array();
				$array[] = $product['productID'];
				$array[] = $locationID;
				$array[] = ("-" . $product['quantity']);
				
				$this->_runFunction("stock", "updateStock", $array);
			}
			else if
				(
					$current['finished'] == 0
					&& $current['declined'] == 0
					&& $new['finished'] == 1
					&& $new['declined'] == 0
				)
			{
				// Element 2
				//print "Entered element 2.<Br/>";
				//print "Excisting order. Not finished, not declined. Going to finished.<br/><br/>";
				
				$array = array();
				$array[] = $product['productID'];
				$array[] = $locationID;
				$array[] = ("-" . $product['quantity']);
				
				$this->_runFunction("stock", "updateStock", $array);
			}
			else if
				(
					$current['finished'] == 1
					&& $current['declined'] == 0
					&& $new['finished'] == 0
					&& $new['declined'] == 0
				)
			{
				// Element 3
				//print "Entered element 3.<Br/>";
				//print "Excisting order. Already finished, not declined. Going to open.<br/><br/>";
				
				$array = array();
				$array[] = $product['productID'];
				$array[] = $locationID;
				$array[] = $product['quantity'];
				
				$this->_runFunction("stock", "updateStock", $array);
			}
			else if
				(
					$current['finished'] == 1
					&& $current['declined'] == 0
					&& $new['finished'] == 1
					&& $new['declined'] == 1
				)
			{
				// Element 4
				//print "Entered element 4.<Br/>";
				//print "Excisting order. Already finished, not declined. Going to declined.<br/><br/>";
				
				$array = array();
				$array[] = $product['productID'];
				$array[] = $locationID;
				$array[] = $product['quantity'];
				
				$this->_runFunction("stock", "updateStock", $array);
			}
		}
	}
	
	
	
	/*
	** data[0] =	orderID;
	** data[1] =	grand_total.
	*/
	
	public function registerPayment($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	UPDATE		orders_payment
				SET			orders_payment.amount = '%.2f'
				WHERE		orders_payment.orderID = %d
					AND		orders_payment.amount = 0",
			$data[1],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$query = sprintf(
			"	UPDATE		orders
				SET			orders.grand_total = '%.2f',
							orders.vat_total = '%.2f',
							orders.payed = '%.2f',
							orders.date_update = NOW()
				WHERE		orders.orderID = %d",
			$this->calcTotal($data[0]),
			$this->calcVatTotal($data[0]),
			$this->calcPayed($data[0]),
			$data[0]
		);
		parent::query($query);
		
		$order = $this->_runFunction("orders", "load", array($data[0]));
		$customer = $this->_runFunction("customers", "load", array($order['customerID']));
		
		// Send e-mails
		$array = array();
		$array[] = $order['merchantID'];
		$array[] = 2;
		$array[] = ($customer['email_address'] != "" ? $customer['email_address'] : "");
		$array[] = 0;
		$array[] = $data[0];
		
		$this->_runFunction("mailserver", "sendAllEmail", $array);
	}
	
	
	
	/*
	**
	*/
	
	public function getNewArticleCode($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		MAX(CONVERT(products.article_code, UNSIGNED INTEGER)) AS article_code
				FROM		products
				WHERE		products.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['article_code']+1);
	}
	
	
	
	/*
	**	data[0] =	merchantID;
	**	data[1] =	products;
	**	data[2] =	customerID;
	**	data[3] =	payments;
	**	data[4] =	statusID;
	**	data[5] =	employeeID;
	**	data[6] =	shipmentID;
	**	data[7] =	orderID;
	**	data[8] =	invoice rules.
	*/
	
	public function runOrder($data)
	{
		parent::_checkInputValues($data, 9);
		
		$currentOrder = "";
		$_order_suffix = "";
				
		
		if(count($data[1]) == 0 && $data[7] == 0)
		{
			return false;
		}
		
		
		if($data[7] > 0 && strpos($data[7], "-") !== false)
		{
			$expl = explode("-", $data[7]);
			$data[7] = $expl[0];
			
			$_order_suffix = $expl[1];
		}
		
		
		if(is_array($data[2]))
		{
			if($data[7] == 0)
			{
				$data[2] = $this->_runFunction("customers", "save", array($data[0], $data[2]));
			}
			else
			{
				$currentOrder = $this->load(array($data[7]));
				
				$data[2]['customerID'] = $currentOrder['customer']['customerID'];
				$data[2] = $this->_runFunction("customers", "save", array($data[0], $data[2]));
			}
			
			$customerData = $this->_runFunction("customers", "load", array($data[2]));
			$country = $customerData['country'];
		}
		
		
		if($data[7] == 0)
		{
			$query = sprintf(
				"	INSERT INTO		orders
					SET				orders.merchantID = %d,
									orders.customerID = %d,
									orders.statusID = %d,
									orders.employeeID = %d,
									orders.date_added = NOW()",
				$data[0],
				$data[2],
				$data[4],
				$data[5]
			);
			$result = parent::query($query);
			$orderID = parent::insert_id($result);
		}
		else
		{
			$query = sprintf(
				"	UPDATE		orders
					SET			orders.merchantID = %d,
								orders.customerID = %d,
								orders.statusID = %d,
								orders.employeeID = %d,
								orders.date_update = NOW()
					WHERE		orders.orderID = %d",
				$data[0],
				$data[2],
				$data[4],
				$data[5],
				$data[7]
			);
			$result = parent::query($query);
			$orderID = $data[7];
		}
		
		
		
		if($data[7] > 0 && count($data[1]) > 0)
		{
			$query = sprintf(
				"	DELETE FROM		orders_product
					WHERE			orders_product.orderID = %d",
				$orderID
			);
			parent::query($query);
		}
		
		
		$vatTotal = 0;
		
		foreach($data[1] AS $product)
		{
			$productData = $this->_runFunction("products", "load", array(intval($product['productID'])));
			$taxrate = $productData['taxrate'];
			
			$query = sprintf(
				"	INSERT INTO		orders_product
					SET				orders_product.orderID = %d,
									orders_product.productID = %d,
									orders_product.name = '%s',
									orders_product.price = '%.2f',
									orders_product.taxrate = '%.2f',
									orders_product.quantity = %d",
				$orderID,
				intval($product['productID']),
				parent::real_escape_string($product['name']),
				parent::floatvalue($product['price']),
				parent::floatvalue($taxrate),
				intval($product['quantity'])
			);
			parent::query($query);
			
			if($productData['used_product'])
			{
				$workorder = array();
				$workorder[0] = $data[0];
				$workorder[1]['used_product'] = 1;
				$workorder[1]['used_product_price'] = abs(parent::floatvalue($product['price']));
				$workorder[1]['customerID'] = 0;
				$workorder[1]['status'] = 0;
				$workorder[1]['priority'] = 0;
				$workorder[1]['expiration_date'] = date('d-m-Y');
				$workorder[1]['key_number'] = 0;
				$workorder[1]['phone_number'] = '';
				$workorder[1]['workorder'] = parent::real_escape_string($product['name']) . ", ingeruild voor &euro;&nbsp;" . number_format($product['price'], 2, ',', '.');
				$workorder[1]['note'] = 'Gebruikt product ingeschoten vanaf order #' . $orderID;
				
				$this->_runFunction("workorders", "saveWorkorder", $workorder);
			}
		}
		
		
		$merchantID = $data[0];
		$currentStatus = ($currentOrder == "" ? 0 : $currentOrder['statusID']);
		$newStatus = $data[4];
		$employeeID = $data[5];
		
		$this->handleStock(array($merchantID, $orderID, $currentStatus, $newStatus, $employeeID));
		
		
		
		if($data[7] > 0 && !isset($_POST['paylink']))
		{
			$query = sprintf(
				"	DELETE FROM		orders_payment
					WHERE			orders_payment.orderID = %d
						AND			orders_payment.amount = 0",
				$orderID
			);
			parent::query($query);
		}
		
		
		
		$_load_module = "";
		$_payed = 0;
		
		foreach($data[3] AS $payment)
		{
			$query = sprintf(
				"	INSERT INTO		orders_payment
					SET				orders_payment.orderID = %d,
									orders_payment.paymentID = %d,
									orders_payment.date = NOW(),
									orders_payment.amount = '%.2f'",
				$orderID,
				intval($payment['paymentID']),
				parent::floatvalue($payment['amount'])
			);
			parent::query($query);
			
			$_payed += $payment['amount'];
			
			$paymentMethod = $this->_runFunction("payment_methods", "load", array($payment['paymentID']));
			
			if($paymentMethod['module'] != "" && $payment['amount'] == 0)
			{
				$_load_module = $paymentMethod['module'];
				$_module_keys = array($paymentMethod['api_key_1'], $paymentMethod['api_key_2']);
			}
		}
		
		
		
		if($data[7] > 0 && !isset($_POST['paylink']))
		{
			$query = sprintf(
				"	DELETE FROM		orders_shipment
					WHERE			orders_shipment.orderID = %d",
				$orderID
			);
			parent::query($query);
		}
		
		
		if($data[6] > 0)
		{
			if(!is_array($data[6]))
			{
				$data[6] = array(0 => $data[6]);
			}
			
			foreach($data[6] AS $shipmentID)
			{
				$shipmentData = $this->_runFunction("shipment_methods", "load", array(intval($shipmentID)));
				
				$courier = $shipmentData['courier'];
				$price = $shipmentData['price'];
				
				$fee = 0;
				
				if($country != "Netherlands")
				{
					foreach($shipmentData['fees'] AS $fValue)
					{
						if($fValue['country'] == $country)
						{
							$fee = floatval($fValue['fee']);
						}
						
						if($fValue['country'] == "Overige landen" && $fee == 0)
						{
							$fee = floatval($fValue['fee']);
						}
					}
				}
				
				$query = sprintf(
					"	INSERT INTO		orders_shipment
						SET				orders_shipment.orderID = %d,
										orders_shipment.shipmentID = %d,
										orders_shipment.courier = '%s',
										orders_shipment.price = '%.2f',
										orders_shipment.track_code = ''",
					$orderID,
					$shipmentID,
					$courier,
					($price + $fee)
				);
				parent::query($query);
			}
		}
		
		
		$query = sprintf(
			"	UPDATE		orders
				SET			orders.grand_total = '%.2f',
							orders.vat_total = '%.2f',
							orders.payed = '%.2f'
				WHERE		orders.orderID = %d",
			$this->calcTotal($orderID),
			$this->calcVatTotal($orderID),
			$this->calcPayed($orderID),
			$orderID
		);
		parent::query($query);
		
		
		if($data[7] > 0)
		{
			$query = sprintf(
				"	DELETE FROM		orders_invoice_rules
					WHERE			orders_invoice_rules.orderID = %d",
				$orderID
			);
			parent::query($query);
		}
		
		
		if(is_array($data[8]))
		{
			for($i = 1; $i <= 4; $i++)
			{
				$query = sprintf(
					"	INSERT INTO		orders_invoice_rules
						SET				orders_invoice_rules.orderID = %d,
										orders_invoice_rules.key = '%s',
										orders_invoice_rules.value = '%s'",
					$orderID,
					parent::real_escape_string($data[8]['key_' . $i]),
					parent::real_escape_string($data[8]['value_' . $i])
				);
				parent::query($query);
			}
		}
		
		
		
		$settings = $this->_runFunction("pos", "loadGeneralSettings", array($data[0]));
		
		// Send communication.
		
		if($data[7] == 0)
		{
			if($data[5] == 0 || $settings['send_emails'] == 1)
			{
				if($data[2] > 0)
				{
					$customerData = $this->_runFunction("customers", "load", array(intval($data[2])));
					
					if($customerData['mobile_phone'])
					{
						// Send order SMS
						$array = array();
						$array[] = $data[0];
						$array[] = 3;
						$array[] = $customerData['mobile_phone'];
						$array[] = 0;
						$array[] = $orderID;
						
						$this->_runFunction("mailserver", "sendAllSMS", $array);
						
						if(count($data[3]) > 0)
						{
							// Send payment SMS
							$array = array();
							$array[] = $data[0];
							$array[] = 4;
							$array[] = $customerData['mobile_phone'];
							$array[] = 0;
							$array[] = $orderID;
							
							$this->_runFunction("mailserver", "sendAllSMS", $array);
						}
					}
				}
					
				// Send e-mails
				$array = array();
				$array[] = $data[0];
				$array[] = 1;
				$array[] = ($data[2] > 0 ? $customerData['email_address'] : "");
				$array[] = 0;
				$array[] = $orderID;
				
				$this->_runFunction("mailserver", "sendAllEmail", $array);
				
				if(count($data[3]) > 0 && $_payed > 0)
				{
					// Send e-mails
					$array = array();
					$array[] = $data[0];
					$array[] = 2;
					$array[] = ($data[2] > 0 ? $customerData['email_address'] : "");
					$array[] = 0;
					$array[] = $orderID;
					
					$this->_runFunction("mailserver", "sendAllEmail", $array);
				}
			}
		}
		

		if($_load_module != "")
		{
			require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/extensions/payments/router.php");
			exit;
		}
		
		
		
		return $orderID;
	}
}
?>