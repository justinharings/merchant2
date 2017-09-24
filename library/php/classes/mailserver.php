<?php
class mailserver extends motherboard
{
	/*
	**	Send a new e-mail.
	**	data[0] =	MerchantID;
	**	data[1] =	Post values.
	*/
	public function send($data)
	{
		$subject = $data[1]['subject'];
		$email = $data[1]['content'];
		$to = $data[1]['receiver'];
		$from = $data[1]['sender'];

		$query = sprintf(
			"	INSERT INTO		mailserver
				SET				mailserver.customerID = %d,
								mailserver.sender = '%s',
								mailserver.receiver = '%s',
								mailserver.subject = '%s',
								mailserver.content = '%s',
								mailserver.date_added = NOW()",
			$data[1]['customerID'],
			parent::real_escape_string($data[1]['sender']),
			parent::real_escape_string($data[1]['receiver']),
			parent::real_escape_string($data[1]['subject']),
			parent::real_escape_string($data[1]['content'])
		);
		$result = parent::query($query);
		
		$emailID = 	parent::insert_id($result);
		
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		$headers[] = "From: Realhosting Servicedesk <{$from}>";
		$headers[] = "Reply-To: Realhosting Servicedesk <{$from}>";
		//$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();

		if(mail($to, $subject, $email, implode("\r\n", $headers), "-f".$from ))
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
}
?>