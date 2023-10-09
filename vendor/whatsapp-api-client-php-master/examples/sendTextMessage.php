<?php
require './vendor/autoload.php';

use GreenApi\RestApi\GreenApiClient;

define("ID_INSTANCE", "7103864108");
define("API_TOKEN_INSTANCE", "9776b1ce110846a2b9b53466597cb0c0d86e7f02daaf49cc98");

$greenApi = new GreenApiClient(ID_INSTANCE, "API_TOKEN_INSTANCE");

$result = $greenApi->sending->sendMessage('11001234567@c.us', 'Message text');

print_r($result->data);
