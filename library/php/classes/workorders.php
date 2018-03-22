<?php
class workorders extends motherboard
{
	/*
	**
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								workorders.workorderID = %d
						OR		workorders.key_number = %d
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		workorders.*,
							DATE_FORMAT(workorders.expiration_date, '%%d-%%m-%%Y') AS expiration_date,
							workorders.expiration_date AS expiration_date_core,
							LPAD(workorders.key_number, 3, 0) AS key_number,
							customers.name AS customer_name
				FROM		workorders
				LEFT JOIN	customers ON customers.customerID = workorders.customerID
				WHERE		workorders.merchantID = %d
					AND		workorders.removed = 0
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
	
	public function viewDocumentation($data)
	{
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								documentation.documentID = %d
						OR		documentation.name LIKE  ('%%%s%%')
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		documentation.*,
							DATE_FORMAT(documentation.date_added, '%%d-%%m-%%Y') AS date_added,
							DATE_FORMAT(documentation.date_update, '%%d-%%m-%%Y') AS date_update,
							pos_employees.name AS employee_name
				FROM		documentation
				INNER JOIN	pos_employees ON pos_employees.employeeID = documentation.employeeID
				WHERE		documentation.merchantID = %d
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
	**	Create a view of the brands.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadSettings($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		workorders_settings.*
				FROM		workorders_settings
				WHERE		workorders_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);

		if(parent::num_rows($result))
		{
			return parent::fetch_assoc($result);
		}
		
		return array();
	}
	
	
	
	/*
	**
	*/
	
	public function loadDocumentation($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		documentation.*
				FROM		documentation
				WHERE		documentation.documentID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**
	*/
	
	public function loadWorkorderCard($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		workorders_card.*
				FROM		workorders_card
				WHERE		workorders_card.workorderID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_array($result);
	}
	
	
	
	/*
	**
	*/
	
	public function loadWorkorder($data)
	{
		$query = sprintf(
			"	SELECT		workorders.*,
							DATE_FORMAT(workorders.expiration_date, '%%d-%%m-%%Y') AS expiration_date,
							pos_employees.name AS mechanic
				FROM		workorders
				LEFT JOIN	pos_employees ON pos_employees.employeeID = workorders.employeeID
				WHERE		workorders.workorderID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Update workorder settings.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveSettings($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		workorders_settings.merchantID
				FROM		workorders_settings
				WHERE		workorders_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$query = sprintf(
				"	UPDATE		workorders_settings
					SET			workorders_settings.receipt_content = '%s',
								workorders_settings.radio = %d,
								workorders_settings.unique_identifier = %d
					WHERE		workorders_settings.merchantID = %d",
				parent::real_escape_string($data[1]['receipt_content']),
				intval($data[1]['radio']),
				intval($data[1]['unique_identifier']),
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		workorders_settings
					SET				workorders_settings.merchantID = %d,
									workorders_settings.receipt_content = '%s',
									workorders_settings.radio = %d,
									workorders_settings.unique_identifier = %d",
				$data[0],
				parent::real_escape_string($data[1]['receipt_content']),
				intval($data[1]['radio']),
				intval($data[1]['unique_identifier'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorderNote($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	UPDATE		workorders
				SET			workorders.note = '%s'
				WHERE		workorders.workorderID = %d",
			$data[1]['note'],
			$data[1]['workorderID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorderStatus($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	UPDATE		workorders
				SET			workorders.status = %d
				WHERE		workorders.workorderID = %d",
			$data[1]['status'],
			$data[1]['workorderID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorderCard($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	DELETE FROM		workorders_card
				WHERE			workorders_card.workorderID = %d",
			$data[1]['workorderID']
		);
		parent::query($query);
		
		$grand_total = 0;
		
		for($i = 1; $i <= 10; $i++)
		{
			if($data[1]['value_' . $i] != "")
			{
				$query = sprintf(
					"	INSERT INTO		workorders_card
						SET				workorders_card.workorderID = %d,
										workorders_card.description = '%s',
										workorders_card.price = '%.2f'",
					$data[1]['workorderID'],
					$data[1]['value_' . $i],
					parent::floatvalue($data[1]['price_' . $i])
				);
				parent::query($query);
				
				$grand_total += parent::floatvalue($data[1]['price_' . $i]);
			}
		}
		
		$query = sprintf(
			"	UPDATE		workorders
				SET			workorders.grand_total = '%.2f',
							workorders.card_saved = 1,
							workorders.employeeID = %d
				WHERE		workorders.workorderID = %d",
			$grand_total,
			$data[2],
			$data[1]['workorderID']
		);
		parent::query($query);
	}
	
	
	
	/*
	**
	*/
	
	public function saveDocumentation($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['documentID']) && $data[1]['documentID'] > 0)
		{
			$query = sprintf(
				"	UPDATE		documentation
					SET			documentation.name = '%s',
								documentation.content = '%s',
								documentation.date_update = NOW()
					WHERE		documentation.documentID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['content']),
				$data[1]['documentID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		documentation
					SET				documentation.merchantID = %d,
									documentation.employeeID = %d,
									documentation.name = '%s',
									documentation.content = '%s',
									documentation.date_added = NOW()",
				$data[0],
				$data[1]['employeeID'],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['content']),
				$data[1]['documentID']
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorderBattery($data)
	{
		parent::_checkInputValues($data, 2);
		
		$workorder = $this->loadWorkorder(array($data[1]['workorderID']));
		
		$query = sprintf(
			"	SELECT		batteries.*
				FROM		batteries
				WHERE		batteries.barcode = '%s'",
			$data[1]['barcode']
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result) > 0)
		{
			$query = sprintf(
				"	UPDATE		batteries
					SET			batteries.customerID = %d,
								batteries.ampere = '%.2f'
					WHERE		batteries.barcode = '%s'",
				$workorder['customerID'],
				$data[1]['ampere'],
				$data[1]['barcode']
			);
			parent::query($query);
		}
		else if($workorder['customerID'] > 0)
		{
			$query = sprintf(
				"	INSERT INTO		batteries
					SET				batteries.customerID = %d,
									batteries.ampere = '%.2f',
									batteries.barcode = '%s'",
				$workorder['customerID'],
				$data[1]['ampere'],
				$data[1]['barcode']
			);
			parent::query($query);
		}
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorderBatteryTest($data)
	{
		parent::_checkInputValues($data, 3);
		
		if(isset($data[1]['batteryID']))
		{
			if($data[1]['removed'] == 1)
			{
				$query = sprintf(
					"	UPDATE		batteries
						SET			batteries.removed = 1
						WHERE		batteries.batteryID = %d",
					$data[1]['batteryID']
				);
				parent::query($query);
			}
			
			$query = sprintf(
				"	INSERT INTO		batteries_test
					SET				batteries_test.batteryID = %d,
									batteries_test.employeeID = %d,
									batteries_test.ampere = '%.2f',
									batteries_test.timer = %d,
									batteries_test.date_added = NOW()",
				$data[1]['batteryID'],
				$data[2],
				$data[1]['ampere'],
				$data[1]['timer']
			);
			parent::query($query);
		}
	}
	
	
	
	/*
	**
	*/
	
	public function saveWorkorder($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['workorderID']) && $data[1]['workorderID'] > 0)
		{
			$query = sprintf(
				"	UPDATE		workorders
					SET			workorders.customerID = %d,
								workorders.status = %d,
								workorders.priority = %d,
								workorders.removed = 0,
								workorders.expiration_date = '%s',
								workorders.key_number = %d,
								workorders.phone_number = '%s',
								workorders.workorder = '%s',
								workorders.note = '%s',
								workorders.date_added = NOW()
					WHERE		workorders.workorderID = %d",
				$data[1]['customerID'],
				$data[1]['status'],
				$data[1]['priority'],
				parent::datevalue($data[1]['expiration_date']),
				$data[1]['key_number'],
				parent::real_escape_string($data[1]['phone_number']),
				parent::real_escape_string($data[1]['workorder']),
				parent::real_escape_string($data[1]['note']),
				$data[1]['workorderID']
			);
			parent::query($query);
			
			$workorderID = $data[1]['workorderID'];
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		workorders
					SET				workorders.merchantID = %d,
									workorders.customerID = %d,
									workorders.status = %d,
									workorders.priority = %d,
									workorders.removed = 0,
									workorders.expiration_date = '%s',
									workorders.key_number = %d,
									workorders.phone_number = '%s',
									workorders.workorder = '%s',
									workorders.note = '%s',
									workorders.date_added = NOW()",
				$data[0],
				$data[1]['customerID'],
				$data[1]['status'],
				$data[1]['priority'],
				parent::datevalue($data[1]['expiration_date']),
				$data[1]['key_number'],
				parent::real_escape_string($data[1]['phone_number']),
				parent::real_escape_string($data[1]['workorder']),
				parent::real_escape_string($data[1]['note'])
			);
			parent::query($query);
			
			$workorderID = parent::insert_id();
		}
		
		if($data[1]['customerID'] > 0)
		{
			$customer = $this->_runFunction("customers", "load", array($data[1]['customerID']));
			
			if($customer['mobile_phone'] == "" && $data[1]['phone_number'] != "")
			{
				$query = sprintf(
					"	UPDATE		customers
						SET			customers.mobile_phone = '%s'
						WHERE		customers.customerID = %d",
					$data[1]['phone_number'],
					$data[1]['customerID']
				);
				parent::query($query);
			}
		}
		
		return $workorderID;
	}
	
	
	
	/*
	**
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		if($data[1] > 0)
		{
			$query = sprintf(
				"	UPDATE		workorders
					SET			workorders.removed = 1,
								workorders.status = 1
					WHERE		workorders.workorderID = %d",
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	DELETE FROM		workorders
					WHERE			workorders.workorderID = %d",
				$data[0]
			);
			parent::query($query);
		}
		
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function checkKeyNumber($data)
	{
		if($data[1]['current'] == $data[1]['check'])
		{
			return 0;
		}
		
		$query = sprintf(
			"	SELECT		COUNT(workorders.workorderID) AS cnt
				FROM		workorders
				WHERE		workorders.key_number = %d
					AND		workorders.removed = 0
					AND		workorders.merchantID = %d",
			$data[1]['check'],
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['cnt'] > 0 ? 1 : 0);
	}
	
	
	
	/*
	**
	*/
	
	public function defaultProductCodes($data)
	{
		parent::_checkInputValues($data, 1);
		
		$return = array();
		
		$query = sprintf(
			"	SELECT		products.productID
				FROM		products
				WHERE		products.workorders_products = 1
					AND		products.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$return['products'] = $row['productID'];
		
		$query = sprintf(
			"	SELECT		products.productID
				FROM		products
				WHERE		products.workorders_manhours = 1
					AND		products.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$return['manhours'] = $row['productID'];
		
		return $return;
	}
}
?>