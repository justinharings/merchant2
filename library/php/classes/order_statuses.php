<?php
class order_statuses extends motherboard
{
	/*
	**	Create a view of the statuses.
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
				"	AND		order_statuses.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		order_statuses.statusID,
							order_statuses.name,
							order_statuses.default,
							DATE_FORMAT(order_statuses.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(order_statuses.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(order_statuses.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		order_statuses
				WHERE		order_statuses.merchantID = %d
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
	**	Load a certain order status.
	**	data[0]	=	statusID.
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
						SELECT		order_statuses_lang.name
						FROM		order_statuses_lang
						WHERE		order_statuses_lang.statusID = order_statuses.statusID
							AND		order_statuses_lang.code = '%s'
					) AS %s_name, ",
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							order_statuses.*
				FROM		order_statuses
				WHERE		order_statuses.statusID = %d",
			$languages,
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save or update a order status. If 'delete' is set
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
		
		/*
		**	Orders that are declined are ALWAYS finished. If not,
		**	status management is going to be very hard in de future.
		*/
		
		if(intval($data[1]['declined']) == 1)
		{
			$data[1]['finished'] = 1;
		}
		
		if(isset($data[1]['statusID']) && $data[1]['statusID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		order_statuses
					SET			order_statuses.name = '%s',
								order_statuses.finished = %d,
								order_statuses.declined = %d,
								order_statuses.shipment_email = %d,
								order_statuses.date_update = NOW()
					WHERE		order_statuses.statusID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['finished']),
				intval($data[1]['declined']),
				intval($data[1]['shipment_email']),
				intval($data[1]['statusID'])
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		order_statuses_lang
					WHERE			order_statuses_lang.statusID = %d",
				intval($data[1]['statusID'])
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		order_statuses
					SET				order_statuses.merchantID = %d,
									order_statuses.name = '%s',
									order_statuses.finished = %d,
									order_statuses.declined = %d,
									order_statuses.shipment_email = %d,
									order_statuses.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['finished']),
				intval($data[1]['declined']),
				intval($data[1]['shipment_email'])
			);
			parent::query($query);
			
			$data[1]['statusID'] = parent::insert_id();
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
				"	INSERT INTO		order_statuses_lang
					SET				order_statuses_lang.statusID = %d,
									order_statuses_lang.code = '%s',
									order_statuses_lang.name = '%s'",
				intval($data[1]['statusID']),
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_name'])
			);
			parent::query($query);
		}
		
		/*
		**	There is always one default status for new automatic orders.
		**	If the user is requesting this SAVE to be the default, remove
		**	all the others soo we keep one default.
		*/
		
		if(intval($data[1]['default']) == 1)
		{
			$query = sprintf(
				"	UPDATE		order_statuses
					SET			order_statuses.default = 0
					WHERE		order_statuses.merchantID = %d",
				$data[0]
			);
			parent::query($query);
			
			$query = sprintf(
				"	UPDATE		order_statuses
					SET			order_statuses.default = 1
					WHERE		order_statuses.statusID = %d",
				intval($data[1]['statusID'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the order status from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		order_statuses
				WHERE			order_statuses.statusID = %d",
			intval($data[1]['statusID'])
		);
		parent::query($query);
		
		return true;
	}
}
?>