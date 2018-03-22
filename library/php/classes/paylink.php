<?php
class paylink extends motherboard
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
				"	AND		paylink.description LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		paylink.*,
							DATE_FORMAT(paylink.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(orders.orderID IS NULL,
								'-',
								CONCAT(YEAR(orders.date_added), orders.orderID)
							) AS order_reference,
							payment_methods.name AS payment_module
				FROM		paylink
				LEFT JOIN	orders ON orders.orderID = paylink.orderID
				INNER JOIN	payment_methods ON payment_methods.paymentID = paylink.paymentID
				WHERE		paylink.merchantID = %d
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
				FROM		paylink
				WHERE		paylink.paylinkID = %d",
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
		
		if(isset($data[1]['paylinkID']) && $data[1]['paylinkID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		paylink
					SET			paylink.description = '%s',
								paylink.orderID = %d,
								paylink.paymentID = %d,
								paylink.amount = '%.2f',
								paylink.date_update = NOW()
					WHERE		paylink.paylinkID = %d",
				parent::real_escape_string($data[1]['description']),
				intval($data[1]['orderID']),
				intval($data[1]['paymentID']),
				parent::floatvalue($data[1]['amount']),
				$data[1]['paylinkID']
			);
			parent::query($query);
		}
		else
		{
			$key = md5(date("d-m-Y") . intval($data[1]['orderID']) . rand(0,10));
			
			$query = sprintf(
				"	INSERT INTO		paylink
					SET				paylink.merchantID = %d,
									paylink.description = '%s',
									paylink.orderID = %d,
									paylink.paymentID = %d,
									paylink.amount = '%.2f',
									paylink.key = '%s',
									paylink.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['description']),
				intval($data[1]['orderID']),
				intval($data[1]['paymentID']),
				parent::floatvalue($data[1]['amount']),
				$key
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**
	*/
	
	public function front_validateCode($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		paylink.paylinkID
				FROM		paylink
				WHERE		paylink.key = '%s'
					AND		paylink.completed = 0",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['paylinkID'] > 0 ? true : false);
	}
	
	
	
	/*
	**
	*/
	
	public function front_loadPaylink($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		paylink.*
				FROM		paylink
				WHERE		paylink.key = '%s'",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['paylinkID'] > 0 ? $row : false);
	}
}
?>