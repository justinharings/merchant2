<?php
class cms extends motherboard
{
	/*
	**	Load the CMS SMS view.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed;
	**	data[4] =	Type of template.
	*/
	
	public function viewSms($data)
	{
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		template_sms.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		if($data[4] > 0)
		{
			$search .= sprintf(
				"	AND		template_sms.typeID = %s",
				$data[4]
			);
		}
		
		$query = sprintf(
			"	SELECT		template_sms.smsID,
							template_sms.name,
							template_sms.typeID,
							template_sms_type.description AS type,
							DATE_FORMAT(template_sms.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(template_sms.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(template_sms.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		template_sms
				INNER JOIN	template_sms_type ON template_sms_type.typeID = template_sms.typeID
				WHERE		template_sms.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load the CMS Emails view.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	**	data[4] =	Type of template.
	*/
	
	public function viewEmail($data)
	{
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		template_email.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		if($data[4] > 0)
		{
			$search .= sprintf(
				"	AND		template_email.typeID = %s",
				$data[4]
			);
		}
		
		$query = sprintf(
			"	SELECT		template_email.emailID,
							template_email.name,
							template_email.typeID,
							template_email_type.description AS type,
							DATE_FORMAT(template_email.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(template_email.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(template_email.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		template_email
				INNER JOIN	template_email_type ON template_email_type.typeID = template_email.typeID
				WHERE		template_email.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load the CMS albums view.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	*/
	
	public function viewAlbum($data)
	{
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		albums.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		albums.albumID,
							albums.name,
							albums.tags,
							(
								SELECT		COUNT(albums_items.itemID)
								FROM		albums_items
								WHERE		albums_items.albumID = albums.albumID
							) AS pictures,
							DATE_FORMAT(albums.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(albums.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(albums.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		albums
				WHERE		albums.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load the CMS banners view.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	*/
	
	public function viewBanner($data)
	{
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		(
								banners.name LIKE ('%%%s%%')
						OR		banners.tag LIKE ('%%%s%%')
							)",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		banners.bannerID,
							banners.name,
							banners.tag,
							banners.url,
							DATE_FORMAT(banners.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(banners.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(banners.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		banners
				WHERE		banners.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Load the CMS content view.
	**	data[0]	=	MerchantID;
	**	data[1]	=	Search value;
	**	data[2]	=	Order by value;
	**	data[3]	=	Maximum rows viewed.
	*/
	
	public function viewContent($data)
	{
		$search = "";
		
		if($data[1] != "")
		{
			$search = sprintf(
				"	AND		content.name LIKE ('%%%s%%')",
				parent::real_escape_string($data[1]),
				parent::real_escape_string($data[1])
			);
		}
		
		$query = sprintf(
			"	SELECT		content.contentID,
							content.name,
							content.seo_url,
							DATE_FORMAT(content.date_added, '%%d-%%m-%%Y @ %%k:%%i') AS date_added,
							IF(
								DATE_FORMAT(content.date_update, '%%d-%%m-%%Y @ %%k:%%i') = '00-00-0000 @ 0:00',
								'n.v.t.',
								DATE_FORMAT(content.date_update, '%%d-%%m-%%Y @ %%k:%%i')
							) AS date_update
				FROM		content
				WHERE		content.merchantID = %d
					%s
				ORDER BY	%s
				LIMIT		%s",
			$data[0],
			$search,
			$data[2],
			$data[3]
		);
		$result = parent::query($query);
		
		return $result;
	}

	
	
	
	/*
	**	Load the CMS Invoice Texts.
	**	data[0]	=	MerchantID;
	*/
	
	public function loadInvoiceText($data)
	{
		parent::_checkInputValues($data, 1);
		
		$_lang = parent::_allLanguages();
		$languages = "";
		
		foreach($_lang AS $value)
		{
			$languages .= sprintf(
				"	(
						SELECT		invoice_text_lang.invoice_text
						FROM		invoice_text_lang
						WHERE		invoice_text_lang.merchantID = invoice_text.merchantID
							AND		invoice_text_lang.code = '%s'
					) AS %s_invoice_text,
					(
						SELECT		invoice_text_lang.invoice_extra
						FROM		invoice_text_lang
						WHERE		invoice_text_lang.merchantID = invoice_text.merchantID
							AND		invoice_text_lang.code = '%s'
					) AS %s_invoice_extra,
					(
						SELECT		invoice_text_lang.receipt_text
						FROM		invoice_text_lang
						WHERE		invoice_text_lang.merchantID = invoice_text.merchantID
							AND		invoice_text_lang.code = '%s'
					) AS %s_receipt_text, ",
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code'],
				$value['code']
			);
		}
		
		$query = sprintf(
			"	SELECT		%s
							invoice_text.*
				FROM		invoice_text
				WHERE		invoice_text.merchantID = %d",
			$languages,
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
	**	Load the CMS SMS templates
	**	data[0]	=	smsID;
	*/
	
	public function loadSms($data)
	{
		parent::_checkInputValues($data, 1);
		
		
		$query = sprintf(
			"	SELECT		template_sms.*
				FROM		template_sms
				WHERE		template_sms.smsID = %d",
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
	**	Load the CMS SMS templates
	**	data[0]	=	emailID;
	*/
	
	public function loadEmail($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		template_email.*
				FROM		template_email
				WHERE		template_email.emailID = %d",
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
	**
	*/
	
	public function loadEmailTemplate($templateID)
	{
		$templateID = $templateID[0];
		
		$query = sprintf(
			"	SELECT		template_email.*
				FROM		template_email
				WHERE		template_email.emailID = %d",
			$templateID
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**
	*/
	
	public function loadSmsTemplate($templateID)
	{
		$templateID = $templateID[0];
		
		$query = sprintf(
			"	SELECT		template_sms.*
				FROM		template_sms
				WHERE		template_sms.smsId = %d",
			$templateID
		);
		$result = parent::query($query);
		
		return parent::fetch_assoc($result);
	}
	
	
	
	/*
	**	Load the CMS Albums
	**	data[0]	=	albumID;
	*/
	
	public function loadAlbum($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		albums.*
				FROM		albums
				WHERE		albums.albumID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		
		if(parent::num_rows($result))
		{
			$return = parent::fetch_assoc($result);
			
			$query = sprintf(
				"	SELECT		albums_items.*
					FROM		albums_items
					WHERE		albums_items.albumID = %d",
				$data[0]
			);
			$result = parent::query($query);
			
			$return['images'] = array();
			
			if(parent::num_rows($result))
			{
				$return['images'] = parent::fetch_array($result);
			}
			
			return $return;
		}
		
		return array();
	}
	
	
	
	/*
	**	Load the CMS Banner
	**	data[0]	=	bannerID;
	*/
	
	public function loadBanner($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		banners.*
				FROM		banners
				WHERE		banners.bannerID = %d",
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
	**	Load the CMS content pages
	**	data[0]	=	contentID;
	*/
	
	public function loadContent($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		content.*
				FROM		content
				WHERE		content.contentID = %d",
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
	**	Update CMS Invoice Texts.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveInvoiceText($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	SELECT		invoice_text.merchantID
				FROM		invoice_text
				WHERE		invoice_text.merchantID = %d",
			$data[0]
		);
		$result = parent::query($query);
		
		if(parent::num_rows($result))
		{
			$query = sprintf(
				"	UPDATE		invoice_text
					SET			invoice_text.invoice_text = '%s',
								invoice_text.receipt_text = '%s',
								invoice_text.invoice_extra = '%s'
					WHERE		invoice_text.merchantID = %d",
				parent::real_escape_string($data[1]['invoice_text']),
				parent::real_escape_string($data[1]['receipt_text']),
				parent::real_escape_string($data[1]['invoice_extra']),
				$data[0]
			);
			parent::query($query);
			
			$query = sprintf(
				"	DELETE FROM		invoice_text_lang
					WHERE			invoice_text_lang.merchantID = %d",
				$data[0]
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		invoice_text
					SET				invoice_text.merchantID = %d,
									invoice_text.invoice_text = '%s',
									invoice_text.receipt_text = '%s',
									invoice_text.invoice_extra = '%s'",
				$data[0],
				parent::real_escape_string($data[1]['invoice_text']),
				parent::real_escape_string($data[1]['receipt_text']),
				parent::real_escape_string($data[1]['invoice_extra'])
			);
			parent::query($query);
		}
		
		
		
		/*
		**	Store fields with multilanguage support.
		**	The available languages are also stored in the database
		**	and manage through the motherboard.
		*/
		
		$_lang = parent::_allLanguages();
		
		foreach($_lang AS $value)
		{
			$query = sprintf(
				"	INSERT INTO		invoice_text_lang
					SET				invoice_text_lang.merchantID = %d,
									invoice_text_lang.code = '%s',
									invoice_text_lang.invoice_text = '%s',
									invoice_text_lang.receipt_text = '%s',
									invoice_text_lang.invoice_extra = '%s'",
				$data[0],
				$value['code'],
				parent::real_escape_string($data[1][$value['code'] . '_invoice_text']),
				parent::real_escape_string($data[1][$value['code'] . '_receipt_text']),
				parent::real_escape_string($data[1][$value['code'] . '_invoice_extra'])
			);
			parent::query($query);
		}
		
		
		
		return true;
	}
	
	
	
	/*
	**	Manage the CMS SMS templates.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveSms($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->deleteSms($data);
		}
		
		if(isset($data[1]['smsID']) && $data[1]['smsID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		template_sms
					SET			template_sms.name = '%s',
								template_sms.typeID = %d,
								template_sms.content = '%s',
								template_sms.language_code = '%s',
								template_sms.date_update = NOW()
					WHERE		template_sms.smsID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['typeID']),
				parent::real_escape_string($data[1]['content']),
				parent::real_escape_string($data[1]['language_code']),
				$data[1]['smsID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		template_sms
					SET				template_sms.merchantID = %d,
									template_sms.name = '%s',
									template_sms.typeID = %d,
									template_sms.content = '%s',
									template_sms.language_code = '%s',
									template_sms.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['typeID']),
				parent::real_escape_string($data[1]['content']),
				parent::real_escape_string($data[1]['language_code'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Manage the CMS email templates.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveEmail($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->deleteEmail($data);
		}
		
		if(isset($data[1]['emailID']) && $data[1]['emailID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		template_email
					SET			template_email.name = '%s',
								template_email.typeID = %d,
								template_email.sender = '%s',
								template_email.receiver = %d,
								template_email.subject = '%s',
								template_email.content = '%s',
								template_email.language_code = '%s',
								template_email.date_update = NOW()
					WHERE		template_email.emailID = %d",
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['typeID']),
				parent::real_escape_string($data[1]['sender']),
				intval($data[1]['receiver']),
				parent::real_escape_string($data[1]['subject']),
				parent::real_escape_string($data[1]['content']),
				parent::real_escape_string($data[1]['language_code']),
				$data[1]['emailID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		template_email
					SET				template_email.merchantID = %d,
									template_email.name = '%s',
									template_email.typeID = %d,
									template_email.sender = '%s',
									template_email.receiver = %d,
									template_email.subject = '%s',
									template_email.content = '%s',
									template_email.language_code = '%s',
									template_email.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				intval($data[1]['typeID']),
				parent::real_escape_string($data[1]['sender']),
				intval($data[1]['receiver']),
				parent::real_escape_string($data[1]['subject']),
				parent::real_escape_string($data[1]['content']),
				parent::real_escape_string($data[1]['language_code']),
				$data[1]['emailID']
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Manage the CMS album information.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values;
	**	data[0]	=	File values.
	*/
	
	public function saveAlbum($data)
	{
		parent::_checkInputValues($data, 3);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->deleteAlbum($data);
		}
		
		if(isset($data[1]['albumID']) && $data[1]['albumID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		albums
					SET			albums.name = '%s',
								albums.description = '%s',
								albums.tags = '%s',
								albums.date_update = NOW()
					WHERE		albums.albumID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['description']),
				parent::real_escape_string($data[1]['album_tags']),
				intval($data[1]['albumID'])
			);
			$result = parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		albums
					SET				albums.merchantID = %d,
									albums.name = '%s',
									albums.description = '%s',
									albums.tags = '%s',
									albums.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['description']),
				parent::real_escape_string($data[1]['album_tags'])
			);
			$result = parent::query($query);
			
			$data[1]['albumID'] = parent::insert_id($result);
		}
		
		$data[2]['image'] = parent::_reArrayFiles($data[2]['image']);
		
		foreach($data[1]['tags'] AS $key => $tags)
		{
			if($tags == "")
			{
				continue;
			}
			
			$thumb = $data[1]['thumb'][$key];
			
			if($thumb == 1)
			{
				$query = sprintf(
					"	UPDATE		albums_items
						SET			albums_items.thumb = 0
						WHERE		albums_items.albumID = %d",
					$data[1]['albumID']
				);
				$result = parent::query($query);
			}
			
			$query = sprintf(
				"	INSERT INTO		albums_items
					SET				albums_items.albumID = %d,
									albums_items.tags = '%s',
									albums_items.thumb = %d",
				intval($data[1]['albumID']),
				parent::real_escape_string($tags),
				intval($thumb)
			);
			$result = parent::query($query);
			
			$itemID = parent::insert_id($result);
			
			
			/*
			**	Upload the picture. This part is done by a upload function
			**	on the main motherboard. Ofcourse we need to give some data.
			*/
			
			if($data[2]['image'][$key]['tmp_name'] != "")
			{
				$path = $_SERVER['DOCUMENT_ROOT'] . "/library/media/albums/" . $itemID;
				
				$options = array(
					"extension" => "png"
				);
				
				parent::_uploadFile($data[2]['image'][$key], $path, $options);
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Manage the CMS SMS templates.
	**	data[0]	=	merchantID;
	**	data[0]	=	Post values.
	*/
	
	public function saveBanner($data)
	{
		parent::_checkInputValues($data, 3);
		
		if(isset($data[1]['delete']) && $data[1]['delete'] != 0)
		{
			return $this->deleteBanner($data);
		}
		
		if(isset($data[1]['bannerID']) && $data[1]['bannerID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		banners
					SET			banners.name = '%s',
								banners.tag = '%s',
								banners.url = '%s',
								banners.language_code = '%s',
								banners.date_update = NOW()
					WHERE		banners.bannerID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['tag']),
				parent::real_escape_string($data[1]['url']),
				parent::real_escape_string($data[1]['language_code']),
				$data[1]['bannerID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		banners
					SET				banners.merchantID = %d,
									banners.name = '%s',
									banners.tag = '%s',
									banners.url = '%s',
									banners.language_code = '%s',
									banners.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['tag']),
				parent::real_escape_string($data[1]['url']),
				parent::real_escape_string($data[1]['language_code'])
			);
			parent::query($query);
			
			$data[1]['bannerID'] = parent::insert_id($result);
		}
		
		
		
		/*
		**	Upload the picture. This part is done by a upload function
		**	on the main motherboard. Ofcourse we need to give some data.
		*/
		
		if($data[2]['image']['tmp_name'] != "")
		{
			$path = $_SERVER['DOCUMENT_ROOT'] . "/library/media/banners/" . intval($data[1]['bannerID']);
			
			parent::_uploadFile($data[2]['image'], $path, $options);
		}
		
		return true;
	}
	
	
	
	/*
	**	Manage the CMS Content.
	**	data[0]	=	merchantID;
	**	data[1]	=	Post values.
	*/
	
	public function saveContent($data)
	{
		parent::_checkInputValues($data, 2);
		
		if(isset($data[1]['contentID']) && $data[1]['contentID'] != 0)
		{
			$query = sprintf(
				"	UPDATE		content
					SET			content.name = '%s',
								content.seo_url = '%s',
								content.seo_keywords = '%s',
								content.seo_description = '%s',
								content.content = '%s',
								content.language_code = '%s',
								content.date_update = NOW()
					WHERE		content.contentID = %d",
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['seo_url']),
				parent::real_escape_string($data[1]['seo_keywords']),
				parent::real_escape_string($data[1]['seo_description']),
				parent::real_escape_string($data[1]['content']),
				strtolower(parent::real_escape_string($data[1]['language_code'])),
				$data[1]['contentID']
			);
			parent::query($query);
		}
		else
		{
			$query = sprintf(
				"	INSERT INTO		content
					SET				content.merchantID = %d,
									content.name = '%s',
									content.seo_url = '%s',
									content.seo_keywords = '%s',
									content.seo_description = '%s',
									content.content = '%s',
									content.language_code = '%s',
									content.date_added = NOW()",
				$data[0],
				parent::real_escape_string($data[1]['name']),
				parent::real_escape_string($data[1]['seo_url']),
				parent::real_escape_string($data[1]['seo_keywords']),
				parent::real_escape_string($data[1]['seo_description']),
				parent::real_escape_string($data[1]['content']),
				parent::real_escape_string($data[1]['language_code'])
			);
			parent::query($query);
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the SMS template from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteSms($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		template_sms
				WHERE			template_sms.smsID = %d",
			$data[1]['smsID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove the email template from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteEmail($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		template_email
				WHERE			template_email.emailID = %d",
			$data[1]['emailID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Remove the album from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteAlbum($data)
	{
		parent::_checkInputValues($data, 3);
		
		$items = $this->loadAlbum(array($data[1]['albumID']));
		
		$query = sprintf(
			"	DELETE FROM		albums
				WHERE			albums.albumID = %d",
			$data[1]['albumID']
		);
		parent::query($query);
		
		foreach($items['images'] AS $value)
		{
			$query = sprintf(
				"	DELETE FROM		albums_items
					WHERE			albums_items.albumID = %d",
				$value['itemID']
			);
			parent::query($query);
			
			$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/albums/" . intval($value['itemID']) . ".png";
			
			if(file_exists($image))
			{
				unlink($image);
			}
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the album item from the database.
	**	Called by a POST, runned by a GET.
	*/
	
	public function deleteAlbumItem($data)
	{
		parent::_checkInputValues($data, 2);
		
		$query = sprintf(
			"	DELETE FROM		albums_items
				WHERE			albums_items.itemID = %d",
			$data[1]['itemID']
		);
		parent::query($query);
		
		$image = $_SERVER['DOCUMENT_ROOT'] . "/library/media/albums/" . intval($data[1]['itemID']) . ".png";
		
		if(file_exists($image))
		{
			unlink($image);
		}
		
		return true;
	}
	
	
	
	/*
	**	Remove the banner from the database.
	**	Called by the save function when delete is set.
	*/
	
	public function deleteBanner($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	DELETE FROM		banners
				WHERE			banners.bannerID = %d",
			$data[1]['bannerID']
		);
		parent::query($query);
		
		return true;
	}
	
	
	
	/*
	**	Get the template types from the database.
	**	Useally to view in select boxes. All the template
	**	tables are build the same soo it's easier to load.
	**	data[0] = template table.
	*/
	
	public function getTemplateTypes($data)
	{
		parent::_checkInputValues($data, 1);
		
		$query = sprintf(
			"	SELECT		%s.*
				FROM		%s
				ORDER BY	%s.description",
			$data[0],
			$data[0],
			$data[0]
		);
		$result = parent::query($query);
		
		$array = parent::fetch_array($result);
		$return = array();
		
		foreach($array AS $value)
		{
			$return[$value['typeID']] = $value['description'];
		}
		
		return $return;
	}
	
	
	
	/*
	** data[0] =	merchantID,
	** data[1] =	language,
	** data[2] =	group,
	** data[3] =	language;
	*/
	
	public function front_loadBanner($data)
	{
		parent::_checkInputValues($data, 4);
		
		$query = sprintf(
			"	SELECT		banners.*
				FROM		banners
				WHERE		banners.merchantID = %d
					AND		banners.language_code = '%s'
					AND		banners.tag = '%s'
				ORDER BY	banners.name",
			$data[0],
			$data[1],
			$data[2]
		);
		
		$result = parent::query($query);
		
		$return = array();
		
		while($row = parent::fetch_assoc($result))
		{
			$check = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/media/banners/" . $row['bannerID'];
			
			if(file_exists($check . ".jpg"))
			{
				$check = ".jpg";
			}
			else if(file_exists($check . ".png"))
			{
				$check = ".png";
			}
			else if(file_exists($check . ".gif"))
			{
				$check = ".gif";
			}
			
			$row['image'] = "https://merchant.justinharings.nl/library/media/banners/" . $row['bannerID'] . $check;
			
			if(strpos($row['url'], "http") === false)
			{
				$row['url'] = "/" . $data[3] . $row['url'];
			}
			
			$return[] = $row;
		}
		
		return $return;
	}
	
	
	
	/*
	**
	*/
	
	public function front_loadContent($data)
	{
		parent::_checkInputValues($data, 3);
		
		$query = sprintf(
			"	SELECT		content.*
				FROM		content
				WHERE		content.seo_url = '%s'
					AND		content.language_code = '%s'
					AND		content.merchantID = %d",
			parent::real_escape_string($data[2]),
			strtolower($data[1]),
			$data[0]
		);
		$result = parent::query($query);
		$row = parent::fetch_assoc($result);
		
		return $row;
	}
}
?>