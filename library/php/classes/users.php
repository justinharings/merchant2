<?php
class users extends motherboard
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
				"	AND		users.first_name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		users.userID,
							users.first_name,
							users.email_address,
							DATE_FORMAT(users.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(users.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(users.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		users
				WHERE		users.merchantID = %d
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
	**	data[0]	=	userID.
	*/
	
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		*
				FROM		users
				WHERE		users.userID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Save or update a user. If 'delete' is set
	**	in the post values, continue to the delete function.
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values.
	**	data[2]	=	Files values.
	*/
	
	public function save($data)
	{
		parent::_checkInputValues($data, 3);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->delete($data);
		}
		
		if(isset($data[1]['userID']) && $data[1]['userID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		users
					SET			users.first_name = '%s',
								users.email_address = '%s',
								users.start_page = '%s',
								users.language_pack = '%s',
								users.date_update = NOW()
					WHERE		users.userID = %d",
				parent::real_escape_string($data[1]['first_name']),
				parent::real_escape_string($data[1]['email_address']),
				parent::real_escape_string($data[1]['start_page']),
				parent::real_escape_string($data[1]['language_pack']),
				$data[1]['userID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		users
					SET				users.merchantID = %d,
									users.first_name = '%s',
									users.email_address = '%s',
									users.start_page = '%s',
									users.language_pack = '%s',
									users.date_added = NOW()",
				intval($data[0]),
				parent::real_escape_string($data[1]['first_name']),
				parent::real_escape_string($data[1]['email_address']),
				parent::real_escape_string($data[1]['start_page']),
				parent::real_escape_string($data[1]['language_pack'])
			);
			parent::query($query);
			
			$data[1]['userID'] = parent::insert_id();
		}
		
		
		/*
		**	If the option for ADMIN is set, update the record
		**	to a ADMIN record. We do not include this into the
		**	normal update statement because the checkbox may
		**	be disabled, we do not won't changes to happen then.
		*/
		
		if(isset($data[1]['admin']))
		{
			$query = sprintf(
				"	UPDATE		users
					SET			users.admin = %d
					WHERE		users.userID = %d",
				intval($data[1]['admin']),
				$data[1]['userID']
			);
			parent::query($query);
		}
		
		
		/*
		**	When the password is requested, update the record with
		**	the given password (hashed, ofcourse).
		*/
		
		if($data[1]['password'] != "" && $data[1]['password_repeat'] != "")
		{
			$query = sprintf(
				"	UPDATE		users
					SET			users.password = '%s'
					WHERE		users.userID = %d",
				parent::_runFunction("authorization", "hashPassword", array($data[1]['password'])),
				$data[1]['userID']
			);
			parent::query($query);
		}
		
		
		/*
		**	Update the profile picture. This part is done by a upload function
		**	on the main motherboard. Ofcourse we need to give some data.
		*/
		
		if($data[2]['profile_picture']['tmp_name'] != "")
		{
			$path = $_SERVER['DOCUMENT_ROOT'] . "/library/media/profile_pictures/" . intval($data[1]['userID']);
			
			$options = array(
				"width" => "400",
				"height" => "400"
			);
			
			parent::_uploadFile($data[2]['profile_picture'], $path, $options);
		}
		
		
		/*
		**	Change the auths, if given. If not, we lease them as they were.
		**	This loops through the options.
		*/
		
		if(isset($data[1]['authorization']) && is_array($data[1]['authorization']))
		{
			$query = sprintf(
				"	DELETE FROM		users_permissions
					WHERE			users_permissions.userID = %d",
				intval($data[1]['userID'])
			);
			parent::query($query);
			
			foreach($data[1]['authorization'] AS $code)
			{
				$query = sprintf(
					"	INSERT INTO		users_permissions
						SET				users_permissions.userID = %d,
										users_permissions.code = '%s'",
					intval($data[1]['userID']),
					$code
				);
				parent::query($query);
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the user from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function delete($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	DELETE FROM		users
				WHERE			users.userID = %d",
			$data[1]['userID']
		);
		parent::query($query);
		
		$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/profile_pictures/" . intval($data[1]['userID']) . ".png";
		
		if(file_exists($image))
		{
			unlink($image);
		}
		
		return true;
	}
	
	
	
	/*
	**	Return the profile image of a user. If the image does
	**	not exists (or is not set), return the no-picture file.
	**	This shows a default profile image.
	*/
	
	public function returnProfileImage($data)
	{
		parent::_checkInputValues($data, 1);
		
		$image = "no-picture.png";
					
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/library/media/profile_pictures/" . $data[0] . ".png"))
		{
			$image = $data[0];
		}
		
		return "/library/media/profile_pictures/" . $image . ".png";
	}
}