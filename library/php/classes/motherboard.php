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
	
	public function __construct($language = "")
	{
		if($language == "")
		{
			$language = _LANGUAGE_PACK;
		}
		
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
		
		if(!isset($_SERVER['REQUEST_URI']) || defined("_DATABASE_FOLDER"))
		{
			$_SERVER['REQUEST_URI'] = "php/posts";
		}
		
		if(strpos($_SERVER['REQUEST_URI'], "php/posts") === false)
		{
			if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/library/languages/" . strtolower($language) . ".xml"))
			{
				$this->language_xml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/library/languages/" . strtolower($language) . ".xml");
			}
			else
			{
				if(defined("_DEVELOPMENT_ENVIRONMENT") && _DEVELOPMENT_ENVIRONMENT == true)
				{
					die("Language pack <em>" . strtolower($language) . ".xml</em> not found.");
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
		$merchantID = (isset($_SESSION['merchantID']) ? $_SESSION['merchantID'] : _MERCHANT_ID);
		
		$query = sprintf(
			"	SELECT		languages.*
				FROM		languages
				WHERE		languages.merchantID = %d",
			$merchantID
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
	**
	*/
	
	public function _countryCodes($country)
	{
		$countries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
		
		return array_search($country, $countries);
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
	
	
	
	/*
	**
	*/
	
	public function replaceCurrency($currency)
	{
		$query = sprintf(
			"	SELECT		currencies.target
				FROM		currencies
				WHERE		currencies.currency = '%s'",
			strtoupper($currency)
		);
		$result = $this->query($query);
		$row = $this->fetch_assoc($result);
		
		return $row['target'];
	}
}
?>