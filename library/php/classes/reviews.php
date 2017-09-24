<?php
class reviews extends motherboard
{
	/*
	**	Create a view of the groups.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Order by value;
	**	data[2]	=	Maximum rows viewed;
	**	data[3] =	Approved.
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 4);
		
		$query = sprintf(
			"	SELECT		reviews.reviewID,
							reviews.name,
							reviews.stars,
							reviews.approved,
							products.name AS product,
							DATE_FORMAT(reviews.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(reviews.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(reviews.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		reviews
				INNER JOIN	products ON products.productID = reviews.productID
				WHERE		reviews.merchantID = %d
					AND		reviews.approved %s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$data[3],
			$data[1],
			$data[2]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load a certain review.
	**	data[0]	=	reviewID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		*
				FROM		reviews
				WHERE		reviews.reviewID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save or update a review. If 'delete' is set
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
		
		if(isset($data[1]['reviewID']) && $data[1]['reviewID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		reviews
					SET			reviews.name = '%s',
								reviews.stars = %d,
								reviews.description = '%s',
								reviews.approved = %d,
								reviews.date_update = NOW()
					WHERE		reviews.reviewID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['stars']),
				parent::real_escape_string($data[1]['description']),
				intval($data[1]['approved']),
				$data[1]['reviewID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		reviews
					SET				reviews.merchantID = %d,
									reviews.name = '%s',
									reviews.stars = %d,
									reviews.description = '%s',
									reviews.approved = 0,
									reviews.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['stars']),
				parent::real_escape_string($data[1]['description'])
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
			"	DELETE FROM		reviews
				WHERE			reviews.reviewID = %d",
			$data[1]['reviewID']
		);
		parent::query($query);
		
		return true;
	}
}
?>