<?php
class dashboard extends motherboard
{
	public function _bot_detected() 
	{
		// return "";
		
		return "	
			AND		visitors.user_agent NOT LIKE ('%bot%')
			AND		visitors.user_agent NOT LIKE ('%crawl%')
			AND		visitors.user_agent NOT LIKE ('%slurp%')
			AND		visitors.user_agent NOT LIKE ('%spider%')
			AND		visitors.user_agent NOT LIKE ('%mediapartners%')";
	}
	
	
	public function profit($data)
	{
		parent::_checkInputValues($data, 3);
		
		$month = "";
		$day = "";
		
		if($data[1] != "" && intval($data[1]) > 0)
		{
			$month = "AND MONTH(orders.date_added) = " . intval($data[1]);
		}
		
		if($data[2] != "" && intval($data[2]) > 0)
		{
			$day = "AND DAY(orders.date_added) = " . intval($data[2]);
		}
		
		$query = sprintf(
			"	SELECT		SUM(orders_product.price) AS amnt
				FROM		orders_product
				INNER JOIN	products ON products.productID = orders_product.productID
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		order_statuses.finished = 1
					AND		order_statuses.declined = 0
					AND		YEAR(orders.date_added) = %d
					%s
					%s",	
			$data[0],
			$month,
			$day
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['amnt'];
	}
	
	
	public function visitors($data)
	{
		parent::_checkInputValues($data, 4);
		
		$month = "";
		$day = "";
		
		if($data[1] != "" && intval($data[1]) > 0)
		{
			$month = "AND MONTH(visitors.date_added) = " . intval($data[1]);
		}
		
		if($data[2] != "" && intval($data[2]) > 0)
		{
			$day = "AND DAY(visitors.date_added) = " . intval($data[2]);
		}
		
		$query = sprintf(
			"	SELECT		COUNT(%s visitors.ip) AS total
				FROM		visitors
				WHERE		YEAR(visitors.date_added) = %d
					%s
					%s
					%s",
			$data[3],
			$data[0],
			$month,
			$day,
			$this->_bot_detected()
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['total'];
	}
	
	
	public function newVisitors($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	SELECT		COUNT(DISTINCT visitors.ip) AS total
				FROM		visitors
				WHERE		YEAR(visitors.date_added) = %d
					AND 	MONTH(visitors.date_added) =  %d
					AND 	DAY(visitors.date_added) = %d
					AND		(
						SELECT		COUNT(v.ip) AS total
						FROM		visitors v
						WHERE		v.ip = visitors.ip
							AND		DATE(v.date_added) < CURDATE()
					) = 0
					%s",
			$data[0],
			$data[1],
			$data[2],
			$this->_bot_detected()
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['total'];
	}
	
	
	public function totalVisitorsMonthlyKeys($data)
	{
		parent::_checkInputValues($data, 0);
		
		$return = "";
		$looper = date("m");
		$year = date("Y");
		
		for($i = 1; $i <= 9; $i++)
		{
			if($looper == 0)
			{
				$looper = 12;
				$year = $year - 1;
			}
			
			switch($looper)
			{
				case 1: $month = "Jan";
				break;
				
				case 2: $month = "Feb";
				break;
				
				case 3: $month = "Mrt";
				break;
				
				case 4: $month = "Apr";
				break;
				
				case 5: $month = "Mei";
				break;
				
				case 6: $month = "Jun";
				break;
				
				case 7: $month = "Jul";
				break;
				
				case 8: $month = "Aug";
				break;
				
				case 9: $month = "Sep";
				break;
				
				case 10: $month = "Okt";
				break;
				
				case 11: $month = "Nov";
				break;
				
				case 12: $month = "Dec";
				break;
			}
			
			$key = $month . " " . $year;
			$looper--;
			
			$return .= $key . ($i < 9 ? "," : "");
		}
		
		$return = explode(",", $return);
		$return = array_reverse($return);
		$return = implode(",", $return);
		
		return $return;
	}
	
	
	public function totalVisitorsMonthlyValues($data)
	{
		$return = "";
		$looper = date("m");
		$year = date("Y");
		
		for($i = 1; $i <= 9; $i++)
		{
			if($looper == 0)
			{
				$looper = 12;
				$year = $year - 1;
			}
			
			$value = $this->visitors(array($year, $looper, "", $data[0]));
			
			$looper--;
			
			$return .= $value . ($i == 9 ? "" : "|");
		}
		
		$return = explode("|", $return);
		$return = array_reverse($return);
		$return = implode("|", $return);
		
		return $return;
	}
	
	
	public function salesMonthlyKeys($data)
	{
		parent::_checkInputValues($data, 0);
		
		$return = "Jan,Feb,Mrt,Apr,Mei,Jun,Jul,Aug,Sep,Okt,Nov,Dec";
		
		return $return;
	}
	
	
	public function salesCalc($data)
	{
		$return = "";
		
		for($i = 1; $i <= 12; $i++)
		{
			$value = $this->_runFunction("reports", "viewArticleGroups", array($data[0], $i, $data[1]));
			
			$cnt = 0;
			
			foreach($value AS $key => $value)
			{
				$cnt += $value['grand_total'];
			}
			
			$value = $cnt;
			
			if($value > 0)
			{
				$return .= $value . ($i == 12 ? "" : "|");
			}
		}
		
		return $return;
	}
}