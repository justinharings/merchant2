<?php
class workorders extends motherboard
{
	/*
	**	Create a view of the brands.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadSettings($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		workorders_settings.*
				FROM		workorders_settings
				WHERE		workorders_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);

		if(parent::num_rows($result))
		{
			return parent::fetch_assoc($result);
		}
		
		return array();
	}
	
	
	
	/*
	**	Update workorder settings.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveSettings($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		workorders_settings.merchantID
				FROM		workorders_settings
				WHERE		workorders_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$query = sprintf(
				"	UPDATE		workorders_settings
					SET			workorders_settings.receipt_content = '%s',
								workorders_settings.radio = %d,
								workorders_settings.unique_identifier = %d
					WHERE		workorders_settings.merchantID = %d",
				parent::real_escape_string($data[1]['receipt_content']),
				intval($data[1]['radio']),
				intval($data[1]['unique_identifier']),
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		workorders_settings
					SET				workorders_settings.merchantID = %d,
									workorders_settings.receipt_content = '%s',
									workorders_settings.radio = %d,
									workorders_settings.unique_identifier = %d",
				$data[0],
				parent::real_escape_string($data[1]['receipt_content']),
				intval($data[1]['radio']),
				intval($data[1]['unique_identifier'])
			);
			parent::query($query);
		}
		
		return true;
	}
}
?>