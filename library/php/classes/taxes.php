<?php
class taxes extends motherboard
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
				"	AND		taxes.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		taxes.taxesID,
							taxes.name,
							taxes.percentage,
							DATE_FORMAT(taxes.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(taxes.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(taxes.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		taxes
				WHERE		taxes.merchantID = %d
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
				FROM		taxes
				WHERE		taxes.taxesID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
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