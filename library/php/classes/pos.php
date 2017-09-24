<?php
class pos extends motherboard
{
	/*
	**	Load the POS printer settings.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadPrinterSettings($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		pos_printers.*
				FROM		pos_printers
				WHERE		pos_printers.merchantID = %d",
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
	**	Load the POS general settings.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadGeneralSettings($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		pos_settings.*
				FROM		pos_settings
				WHERE		pos_settings.merchantID = %d",
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
	**	Load the POS general settings.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadEmployeeSettings($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		pos_employees.*,
							locations.name AS location,
							DATE_FORMAT(pos_employees.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added
				FROM		pos_employees
				INNER JOIN	locations ON locations.locationID = pos_employees.locationID
				WHERE		pos_employees.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			return parent::fetch_array($result);
		}
		
		return array();
	}
	
	
	
	/*
	**	Update POS printer settings.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function savePrinterSettings($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		pos_printers.merchantID
				FROM		pos_printers
				WHERE		pos_printers.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$query = sprintf(
				"	UPDATE		pos_printers
					SET			pos_printers.auto_receipt = %d,
								pos_printers.auto_invoice = %d,
								pos_printers.auto_picklist = %d,
								pos_printers.google_cloud_api_key = '%s',
								pos_printers.google_cloud_secret_key = '%s',
								pos_printers.google_cloud_printer_id = '%s'
					WHERE		pos_printers.merchantID = %d",
				intval($data[1]['auto_receipt']),
				intval($data[1]['auto_invoice']),
				intval($data[1]['auto_picklist']),
				parent::real_escape_string($data[1]['google_cloud_api_key']),
				parent::real_escape_string($data[1]['google_cloud_secret_key']),
				parent::real_escape_string($data[1]['google_cloud_printer_id']),
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		pos_printers
					SET				pos_printers.merchantID = %d,
									pos_printers.auto_receipt = %d,
									pos_printers.auto_invoice = %d,
									pos_printers.auto_picklist = %d,
									pos_printers.google_cloud_api_key = '%s',
									pos_printers.google_cloud_secret_key = '%s',
									pos_printers.google_cloud_printer_id = '%s'",
				$data[0],
				intval($data[1]['auto_receipt']),
				intval($data[1]['auto_invoice']),
				intval($data[1]['auto_picklist']),
				parent::real_escape_string($data[1]['google_cloud_api_key']),
				parent::real_escape_string($data[1]['google_cloud_secret_key']),
				parent::real_escape_string($data[1]['google_cloud_printer_id'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Update POS general settings.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveGeneralSettings($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		pos_settings.merchantID
				FROM		pos_settings
				WHERE		pos_settings.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$query = sprintf(
				"	UPDATE		pos_settings
					SET			pos_settings.shipmentID = %d,
								pos_settings.statusID = %d,
								pos_settings.shipment_required = %d
					WHERE		pos_settings.merchantID = %d",
				intval($data[1]['shipmentID']),
				intval($data[1]['statusID']),
				intval($data[1]['shipment_required']),
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		pos_settings
					SET				pos_settings.merchantID = %d,
									pos_settings.shipmentID = %d,
									pos_settings.statusID = %d,
									pos_settings.shipment_required = %d",
				$data[0],
				intval($data[1]['shipmentID']),
				intval($data[1]['statusID']),
				intval($data[1]['shipment_required'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Update POS general settings.
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values;
	**	data[2]	=	File values.
	*/
	
	public function saveEmployeeSettings($data)
	{
		parent::_checkInputValues($data, 3);
		
		$data[2]['profile_image'] = parent::_reArrayFiles($data[2]['profile_image']);
		
		foreach($data[1]['name'] AS $key => $name)
		{
			if($name == "")
			{
				continue;
			}
			
			$query = sprintf(
				"	INSERT INTO		pos_employees
					SET				pos_employees.merchantID = %d,
									pos_employees.name = '%s',
									pos_employees.locationID = '%d',
									pos_employees.date_added = NOW()",
				$data[0],
				parent::real_escape_string($name),
				$data[1]['location'][$key]
			);
			$result = parent::query($query);
			
			$employeeID = parent::insert_id($result);
			
			/*
			**	Update the profile picture. This part is done by a upload function
			**	on the main motherboard. Ofcourse we need to give some data.
			*/
			
			if($data[2]['profile_image'][$key]['tmp_name'] != "")
			{
				$path = $_SERVER['DOCUMENT_ROOT'] . "/library/media/employee_pictures/" . $employeeID;
				
				$options = array(
					"width" => "400",
					"height" => "400", 
					"extension" => "png"
				);
				
				parent::_uploadFile($data[2]['profile_image'][$key], $path, $options);
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove POS employee.
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values.
	*/
	
	public function deleteEmployeeSettings($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		pos_employees
				WHERE			pos_employees.employeeID = %d",
			$data[1]['employeeID']
		);
		$result = parent::query($query);
		
		$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/employee_pictures/" . intval($data[1]['employeeID']) . ".png";
		
		if(file_exists($image))
		{
			unlink($image);
		}
		
		return true;
	}
}
?>