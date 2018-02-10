<?php
class shipment_methods extends motherboard
{
	/*
	**	Create a view of the groups.
	**	data[0]	=	MerchantID;
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
				"	AND		shipment_methods.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		shipment_methods.*,
							DATE_FORMAT(shipment_methods.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(shipment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(shipment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update,
							(
								SELECT		COUNT(orders.orderID)
								FROM		orders
								INNER JOIN	orders_shipment ON orders_shipment.orderID = orders.orderID
								INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
								WHERE		orders_shipment.shipmentID = shipment_methods.shipmentID
									AND		order_statuses.finished = 0
									AND 	order_statuses.declined = 0
							) AS used
				FROM		shipment_methods
				WHERE		shipment_methods.merchantID = %d
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
	**	Load a certain group.
	**	data[0]	=	groupID.
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
						SELECT		shipment_methods_lang.name
						FROM		shipment_methods_lang
						WHERE		shipment_methods_lang.shipmentID = shipment_methods.shipmentID
							AND		shipment_methods_lang.code = '%s'
					) AS %s_name, 
					(
						SELECT		shipment_methods_lang.price
						FROM		shipment_methods_lang
						WHERE		shipment_methods_lang.shipmentID = shipment_methods.shipmentID
							AND		shipment_methods_lang.code = '%s'
					) AS %s_price, ",
				strtoupper($value['code']),
				strtoupper($value['code']),
				strtoupper($value['code']),
				strtoupper($value['code'])
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							shipment_methods.*
				FROM		shipment_methods
				WHERE		shipment_methods.shipmentID = %d",
			$languages,
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		shipment_methods_fee.*
					FROM		shipment_methods_fee
					WHERE		shipment_methods_fee.shipmentID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['fees'] = array();
			
			if(parent::num_rows($result))
			{
				$return['fees'] = parent::fetch_array($result);
			}
		}
		
		return $return;
	}
	
	
	
	/*
	**	Save or update a shipment method. If 'delete' is set
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
		
		if(isset($data[1]['shipmentID']) && $data[1]['shipmentID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		shipment_methods
					SET			shipment_methods.taxesID = %d,
								shipment_methods.name = '%s',
								shipment_methods.courier = '%s',
								shipment_methods.price = '%.2f',
								shipment_methods.maximum = %d,
								shipment_methods.free_choice = %d,
								shipment_methods.combine = %d,
								shipment_methods.pay_once = %d,
								shipment_methods.date_update = NOW()
					WHERE		shipment_methods.shipmentID = %d",
				intval($data[1]['taxesID']),
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['courier']),
				parent::floatvalue($data[1]['price']),
				intval($data[1]['maximum']),
				intval($data[1]['free_choice']),
				intval($data[1]['combine']),
				intval($data[1]['pay_once']),
				$data[1]['shipmentID']
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		shipment_methods_lang
					WHERE			shipment_methods_lang.shipmentID = %d",
				intval($data[1]['shipmentID'])
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		shipment_methods
					SET				shipment_methods.merchantID = %d,
									shipment_methods.taxesID = %d,
									shipment_methods.name = '%s',
									shipment_methods.courier = '%s',
									shipment_methods.price = '%.2f',
									shipment_methods.maximum = %d,
									shipment_methods.free_choice = %d,
									shipment_methods.combine = %d,
									shipment_methods.pay_once = %d,
									shipment_methods.date_added = NOW()",
				intval($data[0]),
				intval($data[1]['taxesID']),
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['courier']),
				parent::floatvalue($data[1]['price']),
				intval($data[1]['maximum']),
				intval($data[1]['free_choice']),
				intval($data[1]['combine']),
				intval($data[1]['pay_once'])
			);
			parent::query($query);
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
				"	INSERT INTO		shipment_methods_lang
					SET				shipment_methods_lang.shipmentID = %d,
									shipment_methods_lang.code = '%s',
									shipment_methods_lang.name = '%s',
									shipment_methods_lang.price = '%.2f'",
				intval($data[1]['shipmentID']),
				strtoupper($value['code']),
				parent::real_escape_string($data[1][$value['code'] . '_name']),
				parent::floatvalue($data[1][$value['code'] . '_price'])
			);
			parent::query($query);
		}
		
		
		/*
		**
		*/
		
		foreach($data[1]['export_fee_country'] AS $key => $country)
		{
			if($data[1]['export_fee_price'][$key] > 0)
			{
				$query = sprintf(
					"	INSERT INTO		shipment_methods_fee
						SET				shipment_methods_fee.shipmentID = %d,
										shipment_methods_fee.country = '%s',
										shipment_methods_fee.fee = '%.2f'",
					intval($data[1]['shipmentID']),
					$country,
					$data[1]['export_fee_price'][$key]
				);
				parent::query($query);
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the group from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		shipment_methods
				WHERE			shipment_methods.shipmentID = %d",
			$data[1]['shipmentID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	/*
	**
	*/
	
	public function deleteFee($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		shipment_methods_fee
				WHERE			shipment_methods_fee.feeID = %d",
			$data[1]['feeID']
		);
		parent::query($query);
		
		return true;
	}
}
?>