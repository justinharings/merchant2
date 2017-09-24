<?php
function _chopString($string, $len)
{
	if(strlen($string) > $len)
	{
		return substr($string, 0, $len) . "...";
	}
	
	return $string;
}

function _dutchDate($date, $type)
{
	if($type == "date")
	{
		$oDate = new DateTime($date);
		$sDate = $oDate->format("d-m-Y");
		
		return $sDate;
	}
	else if($type == "date-text")
	{
		$oDate = new DateTime($date);
		$sDate = $oDate->format("d");
		$sDate .= " " . _dutchDate($oDate->format("m"), "month-text");
		$sDate .= " " . $oDate->format("Y");
		
		return $sDate;
	}
	else if($type == "time-short")
	{
		$oDate = new DateTime($date);
		$sDate = $oDate->format("H:i");
		
		return $sDate;
	}
	else if($type == "time-long")
	{
		$oDate = new DateTime($date);
		$sDate = $oDate->format("H:i:s");
		
		return $sDate;
	}
	else if($type == "month-text")
	{
		if($date == 0)
		{
			$date = 12;
		}
		
		switch($date)
		{
			case 1: return "Januari";
			break;
			
			case 2: return "Februari";
			break;
			
			case 3: return "Maart";
			break;
			
			case 4: return "April";
			break;
			
			case 5: return "Mei";
			break;
			
			case 6: return "Juni";
			break;
			
			case 7: return "Juli";
			break;
			
			case 8: return "Augustus";
			break;
			
			case 9: return "September";
			break;
			
			case 10: return "Oktober";
			break;
			
			case 11: return "November";
			break;
			
			case 12: return "December";
			break;
		}
	}
	else if($type == "month-text-short")
	{
		if($date == 0)
		{
			$date = 12;
		}
		
		switch($date)
		{
			case 1: return "Jan";
			break;
			
			case 2: return "Feb";
			break;
			
			case 3: return "Mrt";
			break;
			
			case 4: return "Apr";
			break;
			
			case 5: return "Mei";
			break;
			
			case 6: return "Jun";
			break;
			
			case 7: return "Jul";
			break;
			
			case 8: return "Aug";
			break;
			
			case 9: return "Sep";
			break;
			
			case 10: return "Okt";
			break;
			
			case 11: return "Nov";
			break;
			
			case 12: return "Dec";
			break;
		}
	}
	
	return $date;
}
?>