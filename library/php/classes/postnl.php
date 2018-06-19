<?php
class postnl extends motherboard
{
	public function save($data)
	{
		$query = sprintf(
			"	DELETE FROM		postnl
				WHERE			postnl.merchantID = %d",
			$data[0]
		);
		parent::query($query);
		
		$query = sprintf(
			"	INSERT INTO		postnl
				SET				postnl.merchantID = %d,
								postnl.contactperson = '%s',
								postnl.customer_code = '%s',
								postnl.customer_code_int = '%s',
								postnl.customer_number = '%s',
								postnl.api_key = '%s'",
			$data[0],
			$data[1]['contactperson'],
			$data[1]['customer_code'],
			$data[1]['customer_code_int'],
			$data[1]['customer_number'],
			$data[1]['api_key']
		);
		parent::query($query);
		
		return true;
	}
	
	public function load($data)
	{
		$query = sprintf(
			"	SELECT		postnl.*
				FROM		postnl
				WHERE		postnl.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row;
	}
}
?>