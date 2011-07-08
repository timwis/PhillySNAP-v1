<?php

require 'lib/smsified.class.php';
require 'lib/inbound.class.php';

// General settings.
define("SERVICE_URL_BASE", "http://www.phillysnap.com/stores/search.json/");
define("LIMIT", 2);

// SMSified credentials
define("SMS_USER", "timwisniewski");
define("SMS_PASS", "f4sHJQt5");
define("SENDER_ADDRESS", "2672939387");
define("LOG_FILE", "sms.log");

$error = false;
$fun_facts = array(
	'Get $2 in extra coupons for every $5 in SNAP benefits spent at Farmers Markets via Philly Food Bucks Program at 25 participating markets. Info: 215-575-0444',
	'Double your SNAP $$$ at Reading Terminal Market (12th and Arch). Spend $5 in SNAP benefits at the Fair Food Farmstand and get a $5 Double Dollars coupon!',
	'SHARE food program accepts SNAP benefits. Get great farm packages for $15-20 + 2 hours of volunteer time. Call 215.223.2220 for more info.',
	'Get free fruits and vegetables in your community from Fresh for All by Philabundance.  Call 1.800.319.3663 for more info!',
	'If you have WIC or are a senior, you may also qualify for $20 from the Farmers Market Nutrition Program. Ask your WIC office.  Seniors, call 215.765.9040.',
	'To ask questions about SNAP or to get SNAP benefits, call the SNAP	Hotline 215-430-0556',
);


function logit($number, $address, $error) {
	$number = ltrim($number, 'tel:+1');
	$file = fopen(LOG_FILE, 'a');
	$message = "[".date('Y-m-d H:i:s')."] ".$number.": $address".($error ? " (ERROR)" : "")."\n";
	fwrite($file, $message);
	fclose($file);
}

function getLocations($address) {
	
	$address = urlencode($address);
	$ch = curl_init(SERVICE_URL_BASE.$address.'/'.LIMIT);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
	
}

$json = file_get_contents("php://input");
$smsIn = new InboundMessage($json);
$smsInMessage = $smsIn->getMessage();
$smsInPhone = $smsIn->getSenderAddress();

$results = json_decode((getLocations($smsInMessage)));
$smsOut = new SMSified(SMS_USER, SMS_PASS);

// Send Farmers Market
if(isset($results->market)) {
	$market = $results->market;
	$message = $market->name.', ';
	$message .= $market->address.', ';
	$message .= $market->hours.', ';
	$message .= "Distance: ".$market->distance." Miles\n\n";
	$smsOut->sendMessage(SENDER_ADDRESS, $smsInPhone, $message);
}

// Send Stores
if(isset($results->stores)) {
	foreach($results->stores as $location) {
		$message = $location->store_name.', ';
		$message .= $location->address.', ';
		$message .= "Distance: ".$location->distance." Miles\n\n";	
		$smsOut->sendMessage(SENDER_ADDRESS, $smsInPhone, $message);
		
	}
}

// Send fun facts
if(isset($results->market) || isset($results->stores)) {
	$key = array_rand($fun_facts);
	$smsOut->sendMessage(SENDER_ADDRESS, $smsInPhone, $fun_facts[$key]);
}
else
	$error = true; // no market or store results returned

// Send error
if(isset($results->error)) {
	$smsOut->sendMessage(SENDER_ADDRESS, $smsInPhone, $results->error);
}

// Log the incoming message
logit($smsInPhone, $smsInMessage, $error);