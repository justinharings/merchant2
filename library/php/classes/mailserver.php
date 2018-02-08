<?php
class mailserver extends motherboard
{
	/*
	**	data[0] =	MerchantID;
	**	data[1] =	SMS typeID;
	**	data[2] =	Phone number;
	**	data[3] =	workorderID;
	**	data[4] =	orderID.
	*/
	
	public function sendAllSMS($data)
	{
		if($data[2] == "")
		{
			return false;
		}
		
		$customerID = 0;
			
		if($data[3] > 0)
		{
			$query2 = sprintf(
				"	SELECT		workorders.customerID
					FROM		workorders
					WHERE		workorders.workorderID = %d",
				$data[3]
			);
			$result2 = parent::query($query2);
			$row2 = parent::fetch_assoc($result2);
			
			$customerID = $row2['customerID'];
		}
		
		if($data[4] > 0)
		{
			$query2 = sprintf(
				"	SELECT		orders.customerID
					FROM		orders
					WHERE		orders.orderID = %d",
				$data[4]
			);
			$result2 = parent::query($query2);
			$row2 = parent::fetch_assoc($result2);
			
			$customerID = $row2['customerID'];
		}
		
		$language = "nl";
		
		if($customerID > 0)
		{
			$query = sprintf(
				"	SELECT		customers.country
					FROM		customers
					WHERE		customers.customerID = %d",
				$customerID
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			switch($row['country'])
			{
				default:
					$language = "EN";
				break;
				
				case "Netherlands":
				case "Belgium":
					$language = "nl";
				break;
				
				case "Germany":
					$language = "DE";
				break;
			}
		}
			
		$query = sprintf(
			"	SELECT		template_sms.*
				FROM		template_sms
				WHERE		template_sms.typeID = %d
					AND		template_sms.language_code = '%s'
					AND		template_sms.merchantID = %d",
			$data[1],
			$language,
			$data[0]
		);
		$result = parent::query($query);
		
		while($row = parent::fetch_assoc($result))
		{
			$content = $row['content'];
			$content = $this->_replace_tags($content, $customerID, $data[4], $data[3]);
			
			$merchant = $this->_runFunction("merchant", "load", array($data[0]));
			$merchant = $merchant['sms_sender'];
			
			$params = array(
			     'to' => $data[2],
				 'from' => $merchant,
				 'message' => $content,
			);
			
			$this->_sms_send($params, $data[0]);
		}
		
		return true;
	}
	
	
	
	/*
	**	data[0] =	MerchantID;
	**	data[1] =	E-mail typeID;
	**	data[2] =	E-mail address;
	**	data[3] =	workorderID;
	**	data[4] =	orderID.
	*/
	
	public function sendAllEmail($data)
	{
		$customerID = 0;
		
		if($data[3] > 0)
		{
			$query2 = sprintf(
				"	SELECT		workorders.customerID
					FROM		workorders
					WHERE		workorders.workorderID = %d",
				$data[3]
			);
			$result2 = parent::query($query2);
			$row2 = parent::fetch_assoc($result2);
			
			$customerID = $row2['customerID'];
		}
		
		if($data[4] > 0)
		{
			$query2 = sprintf(
				"	SELECT		orders.customerID
					FROM		orders
					WHERE		orders.orderID = %d",
				$data[4]
			);
			$result2 = parent::query($query2);
			$row2 = parent::fetch_assoc($result2);
			
			$customerID = $row2['customerID'];
		}
		
		$language = "nl";
		
		if($customerID > 0)
		{
			$query = sprintf(
				"	SELECT		customers.country
					FROM		customers
					WHERE		customers.customerID = %d",
				$customerID
			);
			$result = parent::query($query);
			$row = parent::fetch_assoc($result);
			
			switch($row['country'])
			{
				default:
					$language = "EN";
				break;
				
				case "Netherlands":
				case "Belgium":
					$language = "nl";
				break;
				
				case "Germany":
					$language = "DE";
				break;
			}
		}
		
		$query = sprintf(
			"	SELECT		template_email.*,
							template_email_type.send_once
				FROM		template_email
				INNER JOIN	template_email_type ON template_email_type.typeID = template_email.typeID
				WHERE		template_email.typeID = %d
					AND		(
								template_email.language_code = '%s'
						OR		template_email.receiver = 2
							)
					AND		template_email.merchantID = %d",
			$data[1],
			$language,
			$data[0]
		);
		$result = parent::query($query);
		
		while($row = parent::fetch_assoc($result))
		{			
			$content = $row['content'];
			$content = $this->_replace_tags($content, $customerID, $data[4], $data[3]);

			// Receiver
			// 1 = klant
			// 2 = gebruikers

			$params = array();
			$params[1]['subject'] = $row['subject'];
			$params[1]['sender'] = $row['sender'];
			$params[1]['content'] = $content;
			$params[1]['customerID'] = $customerID;
			$params[1]['orderID'] = $data[4];
			$params[1]['workorderID'] = $data[3];
			$params[1]['invoice'] = 0;
			
			if($row['receiver'] == 1 && $data[2] != "")
			{
				$_skip = false;
				
				if($row['send_once'] == 1)
				{
					$query_search = sprintf(
						"	SELECT		COUNT(mailserver.emailID) AS cnt
							FROM		mailserver
							WHERE		mailserver.receiver = '%s'
								AND		mailserver.subject = '%s'",
						$data[2],
						$row['subject']
					);
					$result_search = parent::query($query_search);
					$row_search = parent::fetch_assoc($result_search);
					
					if($row_search['cnt'] > 0)
					{
						$_skip = true;
					}
					
					if($row['groupID'] > 0)
					{
						$query_group = sprintf(
							"	SELECT		COUNT(orders_product.orderID) AS cnt
								FROM		orders_product
								INNER JOIN	products ON products.productID = orders_product.productID
								WHERE		orders_product.orderID = %d
									AND		products.groupID = %d",
							$params[1]['orderID'],
							$row['groupID']
						);
						$result_group = parent::query($query_group);
						$row_group = parent::fetch_assoc($result_group);
						
						if($row_group['cnt'] == 0)
						{
							$_skip = true;
						}
					}
				}
				
				if($_skip == false)
				{
					$params[1]['receiver'] = $data[2];
					//print $params[1]['receiver'] . "<br/>";
					$this->send($params);
				}
			}
			else if($row['receiver'] == 2)
			{
				$query2 = sprintf(
					"	SELECT		users.email_address
						FROM		users
						WHERE		users.merchantID = %d",
					$data[0]
				);
				$result2 = $this->query($query2);
				
				while($row2 = $this->fetch_assoc($result2))
				{
					if($row2['email_address'] != "")
					{
						$params[1]['receiver'] = $row2['email_address'];
						$this->send($params);
					}
				}
			}
		}
		
		return true;
	}
	
	
	/*
	**	data[0] =	merchantID;
	**	data[1] =	Receiver;
	**	data[2] =	Content;
	**	data[3] =	customerID;
	**	data[4] =	workorderID;
	**	data[5] =	orderID.
	*/
	
	public function sendSms($data)
	{
		$content = $data[2];
		$content = $this->_replace_tags($content, $data[3], $data[5], $data[4]);
		
		$merchant = $this->_runFunction("merchant", "load", array($data[0]));
		$merchant = $merchant['sms_sender'];
		
		$params = array(
		     'to' => $data[1],
			 'from' => $merchant,
			 'message' => $content,
			 'orderID' => $data[5],
			 'customerID' => $data[3]
		);
		
		$this->_sms_send($params, $data[0]);
	}
	
	
	
	/*
	**
	*/
	
	private function _sms_send($params, $merchantID, $token = "Z4umZAAMu9CbWgpI1IS7VVfXi2Z1AgGLgMs49Q4T") 
	{
		static $content;
	
		$to = $params['to'];
		$leading_zero = (substr($to, 0, 1) == 0 ? 1 : 0);
		
		if($leading_zero)
		{
			$to = substr($to, 1, strlen($to));
		}
		
		$to = "0031" . $to;
		
		$params['to'] = $to;
		
		$url = 'https://api.smsapi.com/sms.do';
		
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, $url );
		curl_setopt( $c, CURLOPT_POST, true );
		curl_setopt( $c, CURLOPT_POSTFIELDS, $params );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HTTPHEADER, array(
		   "Authorization: Bearer $token"
		));
		
		$content = curl_exec( $c );
		$http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		
		if($http_status != 200 && $backup == false)
		{
		    $backup = true;
		    sms_send($params, $token, $backup);
		}
		
		if($http_status == 200)
		{
			$query = sprintf(
				"	INSERT INTO		mailserver
					SET				mailserver.merchantID = %d,
									mailserver.orderID = %d,
									mailserver.customerID = %d,
									mailserver.sender = 'Harings',
									mailserver.receiver = '%s',
									mailserver.subject = 'SMS Melding',
									mailserver.content = '%s',
									mailserver.sent = 1,
									mailserver.date_added = NOW()",
				$merchantID,
				(isset($params['orderID']) ? intval($params['orderID']) : 0),
				(isset($params['customerID']) ? intval($params['customerID']) : 0),
				$params['to'],
				$params['message']
			);
			parent::query($query);
		}
		
		curl_close($c);
		
		return true;
	}
	
	
	
	/*
	**	Send a new e-mail.
	**	data[0] =	MerchantID;
	**	data[1] =	Post values.
	*/
	public function send($data)
	{
		$subject = $data[1]['subject'];
		$to = $data[1]['receiver'];
		//$to = "mail@justinharings.nl";
		$from = $data[1]['sender'];
		
		$content_email = "<!DOCTYPE html><html lang='nl'><head><title></title></head><body>" . $data[1]['content'] . "</body></html>";
		$content_email = $this->_replace_tags($content_email, $data[1]['customerID'], $data[1]['orderID'], (isset($data[1]['workorderID']) ? $data[1]['workorderID'] : 0));

		$query = sprintf(
			"	INSERT INTO		mailserver
				SET				mailserver.customerID = %d,
								mailserver.orderID = %d,
								mailserver.sender = '%s',
								mailserver.receiver = '%s',
								mailserver.subject = '%s',
								mailserver.content = '%s',
								mailserver.attachment = %d,
								mailserver.date_added = NOW()",
			$data[1]['customerID'],
			$data[1]['orderID'],
			parent::real_escape_string($from),
			parent::real_escape_string($to),
			parent::real_escape_string($subject),
			parent::real_escape_string($content_email),
			($data[1]['attachment'] != "" ? 1 : 0)
		);
		$result = parent::query($query);
		
		$emailID = 	parent::insert_id($result);
		
		
		
		require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/library/third-party/swiftmailer/swift_required.php");
		
		$headers = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($to) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$transport = Swift_SmtpTransport::newInstance()
			->setUsername('h.j.harings@gmail.com')->setPassword('CentreVille9')
			->setHost('smtp.gmail.com')
			->setPort(465)->setEncryption('ssl');
		
		$mailer = Swift_Mailer::newInstance($transport);
		
		$logger = new \Swift_Plugins_Loggers_ArrayLogger();
		$mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));
		
		$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array(strip_tags($from) => strip_tags($from)))
			->setTo(array($to))
			//->setTo(array("mail@justinharings.nl"))
			->setReplyTo(array(strip_tags($from)))
			->setBody($content_email, 'text/html');
		
		
		if($data[1]['attachment'] != "")
		{
			// Simulate get in order to let
			// the printserver setup a file.
			$_GET['type']		= $data[1]['attachment'];
			$_GET['action']		= "save";
			$_GET['orderID']	= $data[1]['orderID'];
			
			require_once("/var/www/vhosts/justinharings.nl/" . (_DEVELOPMENT_ENVIRONMENT ? "dev" : "merchant") . ".justinharings.nl/extensions/printserver/router.php");
			
			$file = $_file_name;
				
			if(file_exists($file))
			{
				$message->attach(Swift_Attachment::fromPath($file));
			}
		}
		
		
		if($mailer->send($message))
		{
			$query = sprintf(
				"	UPDATE		mailserver
					SET			mailserver.sent = 1
					WHERE		mailserver.emailID = %d",
				$emailID
			);
			parent::query($query);
		}
	}
	
	
	
	/*
	**
	*/
	
	public function _replace_tags($content, $customerID, $orderID, $workorderID)
	{
		if($customerID > 0)
		{
			$customer_info = parent::_runFunction("customers", "load", array($customerID));
			
			$content = str_replace("[customer-ID]", $customer_info['customerID'], $content);
			$content = str_replace("[customer-NAME]", $customer_info['name'], $content);
			$content = str_replace("[customer-COMPANY]", $customer_info['company'], $content);
			$content = str_replace("[customer-ADDRESS]", $customer_info['address'], $content);
			$content = str_replace("[customer-ZIP_CODE]", $customer_info['zip_code'], $content);
			$content = str_replace("[customer-CITY]", $customer_info['city'], $content);
			$content = str_replace("[customer-COUNTRY]", $customer_info['country'], $content);
			$content = str_replace("[customer-PHONE]", $customer_info['phone'], $content);
			$content = str_replace("[customer-MOBILE_PHONE]", $customer_info['mobile_phone'], $content);
			$content = str_replace("[customer-EMAIL_ADDRESS]", $customer_info['email_address'], $content);
			$content = str_replace("[customer-CUSTOMER_CODE]", $customer_info['customer_code'], $content);
		}
		
		if($orderID > 0)
		{
			$order_info = parent::_runFunction("orders", "load", array($orderID));
			
			$content = str_replace("[order-ID]", $order_info['order_reference'], $content);
			$content = str_replace("[order-GRANDTOTAL]", $order_info['grand_total'], $content);
		}
		
		if($workorderID > 0)
		{
			$workorder_info = parent::_runFunction("workorders", "loadWorkorder", array($workorderID));
			
			$content = str_replace("[workorder-ID]", $workorder_info['workorderID'], $content);
			$content = str_replace("[workorder-GRANDTOTAL]", $workorder_info['grand_total'], $content);
		}
		
		return $content;
	}
	
	
	
	/*
	**
	*/
	
	public function view($data)
	{
		parent::_checkInputValues($data, 4);
		
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								mailserver.sender LIKE ('%%%s%%')
						OR		mailserver.receiver LIKE ('%%%s%%')
						OR		mailserver.subject LIKE ('%%%s%%')
						OR		customers.name LIKE ('%%%s%%')
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		mailserver.*
				FROM		mailserver
				LEFT JOIN	customers ON customers.customerID = mailserver.customerID
				WHERE		(
								customers.merchantID = %d
					OR			mailserver.merchantID = %d
							)
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}

}
?>