<?php

	$root = '/root/wip/arby'; // Working location
	$profit = '5'; // %
	$interval = ((60 * 60) * 1);
	// Enable Exchanges
	$exchanges = array(
		"kraken" => False,
		"bitstamp" => True,
		"btce" => True,
		"campbx" => True,
	);

	// Exchange Specific Config
	
	// Kraken
	$kraken['key'] = "";
	$kraken['secret'] = "";
	// BitStamp
	$bitstamp['user'] = "";
	$bitstamp['key'] = "";
	$bitstamp['secret'] = "";
	// CampBX
	$campbx['username'] = "";
	$campbx['password'] = "";
	// BTCE
	$btce['key'] = "";
	$btce['secret'] = "";

	// Database Settings
	$database['host'] = "";
	$database['name'] = "";
	$database['user'] = "";
	$database['pass'] = "";
?>
