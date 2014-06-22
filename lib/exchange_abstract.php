<?php
	function getTicker($exchange) {
		switch($exchange) {
			case "bitstamp":
				global $bs;
				$result = $bs->ticker();
				$return['bid'] = $result['bid'];
				$return['ask'] = $result['ask'];
				return $return;
				break;
			case "campbx":
				global $campbx;
				$result = $campbx->xticker();
				$return['bid'] = $result['Best Bid'];
				$return['ask'] = $result['Best Ask'];
				return $return;
				break;
			case "btce":
				global $btce;
				$result = $btce->getTicker();
				$return['bid'] = $result['buy'];
				$return['ask'] = $result['sell'];
				return $return;
				break;
			case "kraken":
				global $kraken;
				$result = $kraken->QueryPublic('Ticker', array('pair' => 'XBTUSD'));
				$return['bid'] = $result['result']['XXBTZUSD']['b'][0];
				$return['ask'] = $result['result']['XXBTZUSD']['a'][0];
				return $return;
				break;
		}
	}
	function getBalance($exchange, $currency) {
		switch($exchange) {
			case "bitstamp":
				global $bs;
				$result = $bs->balance();
				$return['usd'] = $result['usd_balance'];
				$return['btc'] = $result['btc_balance'];
				break;
			case "btce":
				global $btce;
				$result = $btce->getInfo();
				$return['usd'] = $result['funds']['usd'];
				$return['btc'] = $result['funds']['btc'];
				break;
		}
		switch($currency) {
			case "USD":
				return $return['usd'];
				break;
			case "BTC":
				return $return['btc'];
				break;
		}
	}
?>
