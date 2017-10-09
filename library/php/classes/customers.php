<?php
class customers extends motherboard
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
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								customers.customerID = %d
						OR		customers.name LIKE ('%%%s%%')
						OR		customers.city LIKE ('%%%s%%')
						OR		customers.zip_code LIKE ('%%%s%%')
						OR		customers.address LIKE ('%%%s%%')
						OR		customers.email_address LIKE ('%%%s%%')
						OR		customers.customer_code LIKE ('%%%s%%')
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		else
		{
			return array();
		}
		
		$query = sprintf(
			"	SELECT		customers.customerID,
							customers.name,
							customers.company,
							customers.zip_code,
							customers.city,
							customers.country,
							customers.phone,
							customers.email_address,
							customers.customer_code
				FROM		customers
				WHERE		customers.merchantID = %d
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
	**	Load a certain percentage.
	**	data[0]	=	taxesID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		*
				FROM		customers
				WHERE		customers.customerID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		orders.*,
								CONCAT(YEAR(orders.date_added), orders.orderID) AS order_reference,
								order_statuses.name AS status,
								DATE_FORMAT(order_statuses.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added
					FROM		orders
					INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
					WHERE		orders.customerID = %d
					ORDER BY	orders.date_added ASC",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['orders'] = array();
			
			if(parent::num_rows($result))
			{
				$return['orders'] = parent::fetch_array($result);
			}
		}
		
		return $return;
	}
	
	
	
	/*
	**	Load all notes from a customer.
	**	data[0]	=	Post value (customerID).
	*/
	
	public function loadNotes($data)
	{
		parent::_checkInputValues($data, 2);
		
		$order = "";
		
		if($data[1]['orderID'] > 0)
		{
			$order = sprintf(
				"	AND		(
								customers_notes.orderID = %d
						OR		customers_notes.orderID = 0
							)",
				intval($data[1]['orderID'])
			);
		}
		
		$query = sprintf(
			"	SELECT		customers_notes.*,
							DATE_FORMAT(customers_notes.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added
				FROM		customers_notes
				WHERE		customers_notes.customerID = %d
					%s
				ORDER BY	customers_notes.date_added DESC",
			intval($data[1]['customerID']),
			$order
		);
		$result = parent::query($query);
		
		$return = array();
		$num = 0;
		
		while($row = parent::fetch_assoc($result))
		{
			$return[$num]['content'] = $row['content'];
			$return[$num]['orderID'] = $row['orderID'];
			$return[$num]['date_added'] = $row['date_added'];
			
			$num++;
		}
				
		return $return;
	}
	
	
	
	/*
	**	Load all notes from a customer.
	**	data[0]	=	Post value (customerID).
	*/
	
	public function loadEmails($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		mailserver.*,
							DATE_FORMAT(mailserver.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added
				FROM		mailserver
				WHERE		mailserver.customerID = %d
					AND		mailserver.subject != 'SMS Melding'
				ORDER BY	mailserver.date_added DESC
				LIMIT		0,25",
			intval($data[1]['customerID'])
		);
		$result = parent::query($query);
		
		$return = array();
		$num = 0;
		
		while($row = parent::fetch_assoc($result))
		{
			$return[$num]['receiver'] = $row['receiver'];
			$return[$num]['subject'] = $row['subject'];
			$return[$num]['content'] = $row['content'];
			$return[$num]['date_added'] = $row['date_added'];
			
			$num++;
		}
		
		return $return;
	}
	
	
	
	/*
	**	Load all notes from a customer.
	**	data[0]	=	Post value (customerID).
	*/
	
	public function loadSms($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		mailserver.*,
							DATE_FORMAT(mailserver.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added
				FROM		mailserver
				WHERE		mailserver.customerID = %d
					AND		mailserver.subject = 'SMS Melding'
				ORDER BY	mailserver.date_added DESC
				LIMIT		0,25",
			intval($data[1]['customerID'])
		);
		$result = parent::query($query);
		
		$return = array();
		$num = 0;
		
		while($row = parent::fetch_assoc($result))
		{
			$return[$num]['receiver'] = $row['receiver'];
			$return[$num]['subject'] = $row['subject'];
			$return[$num]['content'] = $row['content'];
			$return[$num]['date_added'] = $row['date_added'];
			
			$num++;
		}
		
		return $return;
	}
	
	
	
	/*
	**	
	*/
	
	public function searchByCard($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		customers.*
				FROM		customers
				WHERE		customers.customer_code = '%s'",
			$data[0]['search']
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result) == 0)
		{
			$query = sprintf(
				"	SELECT		customers.*
					FROM		customers
					WHERE		CONCAT(customers.zip_code, ExtractNumber(customers.address)) = '%s'
					LIMIT		0,1",
				$data[0]['search']
			);
			$result = parent::query($query);
		}
		
		return parent::fetch_assoc($result);
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
		
		if(isset($data[1]['customerID']) && $data[1]['customerID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		customers
					SET			customers.name = '%s',
								customers.company = '%s',
								customers.address = '%s',
								customers.zip_code = '%s',
								customers.city = '%s',
								customers.country = '%s',
								customers.phone = '%s',
								customers.mobile_phone = '%s',
								customers.email_address = '%s',
								customers.customer_code = '%s',
								customers.date_update = NOW()
					WHERE		customers.customerID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['company']),
				parent::real_escape_string($data[1]['address']),
				parent::real_escape_string($data[1]['zip_code']),
				parent::real_escape_string($data[1]['city']),
				parent::real_escape_string($data[1]['country']),
				parent::real_escape_string($data[1]['phone']),
				parent::real_escape_string($data[1]['mobile_phone']),
				parent::real_escape_string($data[1]['email_address']),
				parent::real_escape_string($data[1]['customer_code']),
				$data[1]['customerID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		customers
					SET				customers.merchantID = %d,
									customers.name = '%s',
									customers.company = '%s',
									customers.address = '%s',
									customers.zip_code = '%s',
									customers.city = '%s',
									customers.country = '%s',
									customers.phone = '%s',
									customers.mobile_phone = '%s',
									customers.email_address = '%s',
									customers.customer_code = '%s',
									customers.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['company']),
				parent::real_escape_string($data[1]['address']),
				parent::real_escape_string($data[1]['zip_code']),
				parent::real_escape_string($data[1]['city']),
				parent::real_escape_string($data[1]['country']),
				parent::real_escape_string($data[1]['phone']),
				parent::real_escape_string($data[1]['mobile_phone']),
				parent::real_escape_string($data[1]['email_address']),
				parent::real_escape_string($data[1]['customer_code'])
			);
			$result = parent::query($query);
			
			$data[1]['customerID'] = parent::insert_id($result);
		}
		
		return $data[1]['customerID'];
	}
	
	
	
	/*
	**	Save a notition to a customer.
	*/
	
	public function saveNotes($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	INSERT INTO		customers_notes
				SET				customers_notes.customerID = %d,
								customers_notes.orderID = %d,
								customers_notes.content = '%s',
								customers_notes.date_added = NOW()",
			intval($data[1]['customerID']),
			intval($data[1]['orderID']),
			parent::real_escape_string($data[1]['note'])
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function saveCustomerCard($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	UPDATE		customers
				SET			customers.customer_code = %d,
							customers.date_update = NOW()
				WHERE		customers.customerID = %d",
			intval($data[1]['customer_code']),
			intval($data[1]['customerID'])
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
			"	DELETE FROM		customers
				WHERE			customers.customerID = %d",
			$data[1]['customerID']
		);
		parent::query($query);
		
		$query = sprintf(
			"	DELETE FROM		customers_notes
				WHERE			customers_notes.customerID = %d",
			$data[1]['customerID']
		);
		parent::query($query);
		
		return $data[1]['customerID'];
	}
}
?>