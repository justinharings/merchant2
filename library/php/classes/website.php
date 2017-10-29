<?php
class website extends motherboard
{
	public function load($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		website_settings.*
				FROM		website_settings
				WHERE		website_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);

		if(parent::num_rows($result))
		{
			return parent::fetch_assoc($result);
		}
		
		return array();
	}
	
	
	
	public function save($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	REPLACE INTO		website_settings
				SET					website_settings.merchantID = %d,
									website_settings.note_content = '%s',
									website_settings.minimum_order_amount = '%.2f'",
			$data[0],
			$data[1]['note_content'],
			$data[1]['minimum_order_amount']
		);
		parent::query($query);
		
		return true;
	}
}
?>