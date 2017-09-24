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
				"	AND		orders.orderID LIKE ('%%%s%%')",
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
							customers.name AS customer_name,
							DATE_FORMAT(orders.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(orders.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(orders.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		orders
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				INNER JOIN	customers ON customers.customerID = orders.customerID
				WHERE		orders.merchantID = %d
					%s
					%s
				ORDER BY	%s
				LIMIT		%s",
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
							DATE_FORMAT(orders.date_added, '%%d-%%m-%%Y om %%k:%%i uur') AS date_added
				FROM		orders
				WHERE		orders.orderID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		orders_product.*
					FROM		orders_product
					WHERE		orders_product.orderID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['products'] = array();
			
			if(parent::num_rows($result))
			{
				$return['products'] = parent::fetch_array($result);
			}
			
			
			$query = sprintf(
				"	SELECT		orders_shipment.*
					FROM		orders_shipment
					WHERE		orders_shipment.orderID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['shipments'] = array();
			
			if(parent::num_rows($result))
			{
				$return['shipments'] = parent::fetch_array($result);
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
				
				$return['customer'] = $row;
			}
			
			
			$query = sprintf(
				"	SELECT		COUNT(orders.orderID) AS cnt
					FROM		orders
					WHERE		orders.orderID != %d
						AND		orders.customerID = %d",
				$data[0],
				$return['customer']['customerID']
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return['customer']['count_orders'] = $row['cnt'];
			
			
			$query = sprintf(
				"	SELECT		SUM(orders.grand_total) AS cnt
					FROM		orders
					WHERE		orders.orderID != %d
						AND		orders.customerID = %d",
				$data[0],
				$return['customer']['customerID']
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			$return['customer']['total_orders'] = $row['cnt'];
			
			return $return;
		}
		
		return array();
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
		
		if(isset($data[1]['taxesID']) && $data[1]['taxesID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		taxes
					SET			taxes.name = '%s',
								taxes.percentage = '%.2f',
								taxes.date_update = NOW()
					WHERE		taxes.taxesID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::floatvalue($data[1]['percentage']),
				$data[1]['taxesID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		taxes
					SET				taxes.merchantID = %d,
									taxes.name = '%s',
									taxes.percentage = '%.2f',
									taxes.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::floatvalue($data[1]['percentage'])
			);
			parent::query($query);
		}
		
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
}
?>