<?php
class payment_methods extends motherboard
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
				"	AND		payment_methods.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		payment_methods.*,
							DATE_FORMAT(payment_methods.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(payment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(payment_methods.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		payment_methods
				WHERE		payment_methods.merchantID = %d
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
	**	Load a certain payment method.
	**	data[0]	=	paymentID.
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
						SELECT		payment_methods_lang.description
						FROM		payment_methods_lang
						WHERE		payment_methods_lang.paymentID = payment_methods.paymentID
							AND		payment_methods_lang.code = '%s'
					) AS %s_description, ",
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							payment_methods.*
				FROM		payment_methods
				WHERE		payment_methods.paymentID = %d",
			$languages,
			$data[0]
		);
		//print "<pre>".$query."</pre>"; exit;
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**
	*/
	
	public function loadCashID($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		payment_methods.paymentID
				FROM		payment_methods
				WHERE		payment_methods.cash = 1
					AND		payment_methods.merchantID = %d
				LIMIT		0,1",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return ($row['paymentID'] ? $row['paymentID'] : 0);
	}
	
	
	
	/*
	**	Save or update a payment method. If 'delete' is set
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
		
		if(isset($data[1]['paymentID']) && $data[1]['paymentID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		payment_methods
					SET			payment_methods.name = '%s',
								payment_methods.description = '%s',
								payment_methods.module = '%s',
								payment_methods.api_key_1 = '%s',
								payment_methods.api_key_2 = '%s',
								payment_methods.maximum_amount = %d,
								payment_methods.webshop = %d,
								payment_methods.pos = %d,
								payment_methods.cash = %d,
								payment_methods.date_update = NOW()
					WHERE		payment_methods.paymentID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['description']),
				parent::real_escape_string($data[1]['module']),
				parent::real_escape_string($data[1]['api_key_1']),
				parent::real_escape_string($data[1]['api_key_2']),
				intval($data[1]['maximum_amount']),
				intval($data[1]['webshop']),
				intval($data[1]['pos']),
				intval($data[1]['cash']),
				intval($data[1]['paymentID'])
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		payment_methods_lang
					WHERE			payment_methods_lang.paymentID = %d",
				intval($data[1]['paymentID'])
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		payment_methods
					SET				payment_methods.merchantID = %d,
									payment_methods.name = '%s',
									payment_methods.description = '%s',
									payment_methods.module = '%s',
									payment_methods.api_key_1 = '%s',
									payment_methods.api_key_2 = '%s',
									payment_methods.maximum_amount = %d,
									payment_methods.webshop = %d,
									payment_methods.pos = %d,
									payment_methods.cash = %d,
									payment_methods.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['description']),
				parent::real_escape_string($data[1]['module']),
				parent::real_escape_string($data[1]['api_key_1']),
				parent::real_escape_string($data[1]['api_key_2']),
				intval($data[1]['maximum_amount']),
				intval($data[1]['webshop']),
				intval($data[1]['pos']),
				intval($data[1]['cash'])
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
				"	INSERT INTO		payment_methods_lang
					SET				payment_methods_lang.paymentID = %d,
									payment_methods_lang.code = '%s',
									payment_methods_lang.description = '%s'",
				intval($data[1]['paymentID']),
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_description'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the payment method from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		payment_methods
				WHERE			payment_methods.paymentID = %d",
			intval($data[1]['paymentID'])
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Get a array with all the payment modules. Adding a module
	**	is as simple as adding a folder to the payment-modules folder
	**	and add a name.txt file to it.
	*/
	
	public function payment_modules()
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . "/library/third-party/payment-modules/";
		
		$return = array();
		$num = 0;
		
		if($handle = opendir($path)) 
		{
			while(false !== ($entry = readdir($handle))) 
			{
				if(file_exists($path . $entry . "/name.txt"))
				{
					$return[$num]['name'] = file_get_contents($path . $entry . "/name.txt");
					$return[$num]['folder'] = $entry;
					
					$num++;
				}
			}
		
			closedir($handle);
		}
		
		asort($return);
		
		return $return;
	}
}
?>