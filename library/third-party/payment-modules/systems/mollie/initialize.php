<?php

require_once dirname(__FILE__) . "/src/Mollie/API/Autoloader.php";
require_once dirname(__FILE__) . "/database.php";

/*
 * Initialize the Mollie API library with your API key.
 *
 * See: https://www.mollie.nl/beheer/account/profielen/
 */

 
$mollie = new Mollie_API_Client;
$mollie->setApiKey($_api_key_1);