<?php
class visitors extends motherboard
{
	public function log_visitor($data)
	{
		parent::_checkInputValues($data, 4);
		
		$query = sprintf(
			"	INSERT INTO		visitors
				SET				visitors.merchantID = %d,
								visitors.ip = '%s',
								visitors.page = '%s',
								visitors.user_agent = '%s',
								visitors.date_added = NOW()",
			$data[0],
			$data[1],
			$data[2],
			$data[3]
		);
		parent::query($query);
		
		return true;
	}
}
?>