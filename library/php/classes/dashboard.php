<?php
class dashboard extends motherboard
{
	public function _bot_detected() 
	{
		return "	
			AND		visitors.user_agent NOT LIKE ('%bot%')
			AND		visitors.user_agent NOT LIKE ('%crawl%')
			AND		visitors.user_agent NOT LIKE ('%slurp%')
			AND		visitors.user_agent NOT LIKE ('%spider%')
			AND		visitors.user_agent NOT LIKE ('%mediapartners%')";
	}
	
	
	public function profit($data)
	{
		parent::_checkInputValues($data, 4);
		
		$month = "";
		$day = "";
		
		if($data[2] != "" && intval($data[2]) > 0)
		{
			$month = "AND MONTH(orders_payment.date) = " . intval($data[2]);
		}
		
		if($data[3] != "" && intval($data[3]) > 0)
		{
			$day = "AND DAY(orders_payment.date) = " . intval($data[3]);
		}
		
		$query = sprintf(
			"	SELECT		SUM(orders_payment.amount) AS amnt
				FROM		orders_payment
				INNER JOIN	orders ON orders.orderID = orders_payment.orderID
				WHERE		orders.merchantID = %d
					AND		YEAR(orders_payment.date) = %d
					%s
					%s",	
			$data[0],
			$data[1],
			$month,
			$day
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['amnt'];
	}
	
	
	public function visitors($data)
	{
		parent::_checkInputValues($data, 5);
		
		$month = "";
		$day = "";
		
		if($data[2] != "" && intval($data[2]) > 0)
		{
			$month = "AND MONTH(visitors.date_added) = " . intval($data[2]);
		}
		
		if($data[3] != "" && intval($data[3]) > 0)
		{
			$day = "AND DAY(visitors.date_added) = " . intval($data[3]);
		}
		
		$query = sprintf(
			"	SELECT		COUNT(%s visitors.ip) AS total
				FROM		visitors
				WHERE		YEAR(visitors.date_added) = %d
					AND		visitors.merchantID = %d
					%s
					%s
					%s",
			$data[4],
			$data[1],
			$data[0],
			$month,
			$day,
			$this->_bot_detected()
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row['total'];
	}
	
	
	public function visitorCountries($data)
	{
		parent::_checkInputValues($data, 4);
		
		$query = sprintf(
			"	SELECT		DISTINCT visitors.ip
				FROM		visitors
				WHERE		visitors.merchantID = %d
					AND		YEAR(visitors.date_added) = %d
					AND 	MONTH(visitors.date_added) =  %d
					AND 	DAY(visitors.date_added) = %d
					%s",
			$data[0],
			$data[1],
			$data[2],
			$data[3],
			$this->_bot_detected()
		);
		$result = parent::query($query);
		
		$countries = array();
		
		while($row = parent::fetch_assoc($result))
		{
			$res = file_get_contents('https://www.iplocate.io/api/lookup/' . $row['ip']);
			$res = json_decode($res, true);
			
			$country = $res['country'];
			
			if(!in_array($country, $countries))
			{
				$countries[] = $country;
			}
		}
		
		return $countries;
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
			
			$value = $this->visitors(array($data[0], $year, $looper, "", $data[1]));
			
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
			$val = $this->profit(array($data[0], $data[1], $i, ""));
			
			//if($val > 0)
			{
				$return .= $val . ($i == 12 ? "" : "|");
			}
		}
		
		return $return;
	}
}