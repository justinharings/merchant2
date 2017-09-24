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
			"	SELECT		shipment_methods.shipmentID,
							shipment_methods.name,
							shipment_methods.courier,
							shipment_methods.price,
							DATE_FORMAT(shipment_methods.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(shipment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(shipment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
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
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code']
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
		
		return parent::fetch_assoc($result);
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
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_name']),
				parent::floatvalue($data[1][$value['code'] . '_price'])
			);
			parent::query($query);
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
			"	DELETE FROM		groups
				WHERE			groups.groupID = %d",
			$data[1]['groupID']
		);
		parent::query($query);
		
		return true;
	}
}
?>