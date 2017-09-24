<?php
class authorization extends motherboard
{
	/*
	**	Validate if the visitor has logged in. If not,
	**	return a false. Mostely used for defining if
	**	the login screen must be showed or not.
	*/
	
	public function validateLogin()
	{
		if(isset($_SESSION['auth_token']))
		{
			return true;
		}
		
		return false;
	}
	
	
	
	/*
	**	The function requires a username and password.
	**	If the combination is valid, return a array
	**	with usefull user data. If not, return false.
	*/
	
	public function validateData($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		users.*
				FROM		users
				WHERE		users.email_address = '%s'",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		$return = array();

		if($row['userID'] > 0 && crypt($data[1], $row['password']) == $row['password'])
		{
			$return['userID'] = $row['userID'];
			$return['merchantID'] = $row['merchantID'];
			$return['first_name'] = $row['first_name'];
			$return['email_address'] = $row['email_address'];
			$return['start_page'] = $row['start_page'];
			$return['administrator'] = $row['admin'];
			$return['language_pack'] = $row['language_pack'];
			$return['auth_token'] = md5(base64_encode("The horse love is gone") . $row['email_address']);
			
			return $return;
		}
		
		return false;
	}
	
	
	
	/*
	**	Hash the password, always the same way.. This
	**	is the only function that understands the password
	**	input and it can not decode it.
	*/
	
	public function hashPassword($password)
	{
		if(is_array($password))
		{
			$password = $password[0];
		}
		
		$cost = 10;
		
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		
		$hash = crypt($password, $salt);
		
		return $hash;
	}
	
	
	
	/*
	**	Check if the user given has permission to see a
	**	certain part of the system. Use the code for check.
	**
	**	Data 3 = Optional:
	**	When the permission is not set, show a 503 error.
	*/
	
	public function userPermission($data)
	{		
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	SELECT		users_permissions.userID
				FROM		users_permissions
				WHERE		users_permissions.userID = %d
					AND		users_permissions.code = '%s'",
			$data[0],
			$data[1]
		);
		$result = parent::query($query);
	
		if(parent::num_rows($result))
		{
			return true;
		}
		
		if($data[2] == true)
		{
			require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/errors/503.php");
			exit;
		}
		
		return false;
	}
}