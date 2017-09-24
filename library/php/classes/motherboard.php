<?php
require_once(__DIR__ . "/database.php");
	
class motherboard extends database
{
	protected $language_xml = null;
	protected $thirdPartyApps = array();
	
	
	
	/*
	**	Construct the motherboard class. The language pack is
	**	loaded by the motherboard for quick access and
	**	translations as well by other classes as the site itself.
	**	If development mode is not found, check the session. If
	**	the session is not found neither then turn it off.
	*/
	
	public function __construct()
	{
		parent::__construct();
		
		if(!defined("_DEVELOPMENT_ENVIRONMENT"))
		{
			if(!isset($_SESSION['_DEVELOPMENT_ENVIRONMENT']))
			{
				define("_DEVELOPMENT_ENVIRONMENT", false);
			}
			else
			{
				define("_DEVELOPMENT_ENVIRONMENT", $_SESSION['_DEVELOPMENT_ENVIRONMENT']);
			}
		}
		
		if(strpos($_SERVER['REQUEST_URI'], "php/posts") === false)
		{
			if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/library/languages/" . strtolower(_LANGUAGE_PACK) . ".xml"))
			{
				$this->language_xml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/library/languages/" . strtolower(_LANGUAGE_PACK) . ".xml");
			}
			else
			{
				if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
				{
					die("Language pack <em>" . strtolower(_LANGUAGE_PACK) . ".xml</em> not found.");
				}
				else
				{
					$this->_throwUserError();
				}
			}
		}
	}
	
	
	
	/*
	**	Create and show the user-friendly error page.
	**	If the error page is not found, show the apache
	**	default HTTP error.
	*/
	
	public function _throwUserError($type = "")
	{
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/modules/errors/general.php"))
		{
			require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/errors/general.php");
			exit;
		}
		else
		{
			header("HTTP/1.1 500 Internal Server Error");
			die();
		}
	}
	
	
	
	/*
	**	Return a single translated word used for
	**	display on the webpage.
	*/
	
	public function _translateReturn($group, $word, $words = array())
	{
		$xml = simplexml_load_string($this->language_xml);
		
		return vsprintf(
			$xml->$group->$word,
			$words
		);
	}
	
	
	
	/*
	**	Return a array of all the languages that Merchant
	**	supports. Languages are stored in a database table.
	*/
	
	public function _allLanguages()
	{
		$query = sprintf(
			"	SELECT		languages.*
				FROM		languages"
		);
		$result = parent::query($query);
		
		return $result;
	}
	
	
	
	/*
	**	Return a array with all the worldwide countries.
	**	This array is a download from the internet.
	*/
	
	public function _allCountries()
	{
		return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
	}
	
	
	
	/*
	**	Call a second-class and run a function within it.
	**	If the class or the function doesn't exists, an
	**	error is given by the motherboard.
	*/
	
	public function _runFunction($className, $function, $values = array())
	{
		if(file_exists(__DIR__ . "/" . $className . ".php"))
		{
			require_once(__DIR__ . "/" . $className . ".php");
			
			$class = new $className();
			
			if(!method_exists($class, $function))
			{
				if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
				{
					die("Function <em>" . $function . "</em> does not exists within the given class <em>" . $className . "</em>");
				}
				else
				{
					$this->_throwUserError();
				}
			}
			
			return $class->$function($values);
		}
		else
		{
			if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
			{
				die("Class <em>" . $class . "</em> is not found, the file doesn't exists.");
			}
			else
			{
				$this->_throwUserError();
			}
		}
	}
	
	
	
	/*
	**	Put the files into a normal file array after upload.
	**	Normally it would put name, tmp_name etc togheter, we
	**	split it back up for easier usage.
	*/
	
	function _reArrayFiles($file_post) 
	{
		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);
		
		for($i=0; $i<$file_count; $i++) 
		{
			foreach($file_keys as $key) 
			{
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}
	
		return $file_ary;
	}
	
	
	
	/*
	**	General function to upload a file from anywhere in the APP.
	**	Also able to do some checks for options, like width or extension.
	*/
	
	public function _uploadFile($file, $path, $options = array())
	{
		$debug = false;
		
		$max_width = 0;
		$max_height = 0;
		$filter_extension = "";
		
		if(isset($options['width']))
		{
			$max_width = $options['width'];
		}
		
		if(isset($options['height']))
		{
			$max_height = $options['height'];
		}
		
		if(isset($options['extension']))
		{
			$filter_extension = $options['extension'];
		}
		
		$file_name 	= $file['name'];
		$file_size 	= $file['size'];
		$file_tmp 	= $file['tmp_name'];
		$file_type 	= $file['type'];
		$file_ext	= strtolower(end(explode('.',$file['name'])));
		
		$image_info = getimagesize($file_tmp);
		
		$image_width = $image_info[0];
		$image_height = $image_info[1];
		
		if($max_width > 0 && $image_width > $max_width)
		{
			$errors[] = "Image width is too large.";
		}
		
		if($max_height > 0 && $image_height > $max_height)
		{
			$errors[] = "Image height is too large.";
		}
		
		if($filter_extension != "" && (strtolower($file_ext) != strtolower($filter_extension)))
		{
			$errors[] = "The extension is not allowed.";
		}
		
		if(empty($errors) == true)
		{
			if(move_uploaded_file($file_tmp, $path . "." . $file_ext))
			{
				if($debug)
				{
					print "done! " . $path . "." . $file_ext . "<br/>";
				}
			}
			else
			{
				if($debug)
				{
					if(!is_dir($path))
					{
						print "No directory!<br/>" . $path; exit;
					}
					
					if(!is_writable($path))
					{
						print "Not writeable!<br/>" . $path; exit;
					}
				}
			}
			
			return true;
		}
		else
		{
			if($debug)
			{
				print "<pre>" . print_r($errors) . "</pre>"; exit;
				return false;
			}
		}
		
		if($debug)
		{
			exit;
		}
	}
	
	
	
	/*
	**	Load third-party apps by using the autoloader. If
	**	there is no autoloader found, give an error.
	**	After loading the app, add it to a array. Check,
	**	before loading, if the app isn't loaded before.
	*/
	
	public function _requireThirdParty($folder)
	{
		$apps = $this->thirdPartyApps;
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/library/third-party/" . $folder . "/autoload.php"))
		{
			if(!in_array($folder, $apps))
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . "/library/third-party/" . $folder . "/autoload.php");
				
				$apps[] = $folder;
				$this->thirdPartyApps = $apps;
			}
			else
			{
				if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
				{
					die("Third-party software package <em>" . $folder . "</em> is called to load twice.");
				}
				else
				{
					$this->_throwUserError();
				}
			}
		}
		else
		{
			if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
			{
				die("Third-party software package <em>" . $folder . "</em> is not found or the autoloader is not installed.");
			}
			else
			{
				$this->_throwUserError();
			}
		}
	}
	
	
	
	/*
	**	This function can be called by a child-class 
	**	to check if the values given to a function
	**	are complete. If not, throw exception.
	*/
	
	public function _checkInputValues($values, $count)
	{
		$correct = true;
		
		if(!is_array($values))
		{
			$correct = false;
		}
		
		if($count != count($values))
		{
			$correct = false;
		}
		
		if($correct == false)
		{
			if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
			{
				die("Class error. Wrong strings given.<br/>" . print_r($values, true));
			}
			else
			{
				$this->_throwUserError();
			}
		}
	}
}
?>