<?php
class groups extends motherboard
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
				"	AND		groups.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		groups.groupID,
							groups.name,
							DATE_FORMAT(groups.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(groups.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(groups.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		groups
				WHERE		groups.merchantID = %d
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
		
		$query = sprintf(
			"	SELECT		*
				FROM		groups
				WHERE		groups.groupID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save or update a group. If 'delete' is set
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
		
		if(isset($data[1]['groupID']) && $data[1]['groupID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		groups
					SET			groups.name = '%s',
								groups.date_update = NOW()
					WHERE		groups.groupID = %d",
				parent::real_escape_string($data[1]['name']),
				$data[1]['groupID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		groups
					SET				groups.merchantID = %d,
									groups.name = '%s',
									groups.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name'])
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