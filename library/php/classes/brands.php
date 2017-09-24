<?php
class brands extends motherboard
{
	/*
	**	Create a view of the brands.
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
				"	AND		brands.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		brands.brandID,
							brands.name,
							DATE_FORMAT(brands.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(brands.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(brands.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		brands
				WHERE		brands.merchantID = %d
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
	**	Load a certain brand.
	**	data[0]	=	brandID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		*
				FROM		brands
				WHERE		brands.brandID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save or update a brand. If 'delete' is set
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
		
		if(isset($data[1]['brandID']) && $data[1]['brandID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		brands
					SET			brands.name = '%s',
								brands.date_update = NOW()
					WHERE		brands.brandID = %d",
				parent::real_escape_string($data[1]['name']),
				$data[1]['brandID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		brands
					SET				brands.merchantID = %d,
									brands.name = '%s',
									brands.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the brand from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		brands
				WHERE			brands.brandID = %d",
			$data[1]['brandID']
		);
		parent::query($query);
		
		return true;
	}
}
?>