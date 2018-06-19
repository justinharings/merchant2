<?php
class paylink extends database
{
	/*
	**
	*/
	
	public function front_validateCode($data)
	{
		$orderID = base64_decode($data[0]);
		
		$query = sprintf(
			"	SELECT		orders.grand_total,
							orders.payed
				FROM		orders
				WHERE		orders.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$order = parent::fetch_assoc($result);
		
		if(($order['grand_total'] - $order['payed']) > 0)
		{
			return 1;
		}
		
		return 0;
	}
	
	
	
	/*
	**
	*/
	
	public function front_getData($data)
	{
		$orderID = base64_decode($data[0]);
		
		$query = sprintf(
			"	SELECT		orders.merchantID,
							customers.country
				FROM		orders
				INNER JOIN	customers ON customers.customerID = orders.customerID
				WHERE		orders.orderID = %d",
			$orderID
		);
		$result = parent::query($query);
		$order = parent::fetch_assoc($result);
		
		return array($order['merchantID'], $order['country']);
	}
}
?>