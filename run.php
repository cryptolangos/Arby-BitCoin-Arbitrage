<?php
	require('lib/autoloader.php');

	while(true){
		$db = new Db();
		foreach($activeExchanges as $exchange) {
			$result = getTicker($exchange);
			$bid[$exchange] = $result['bid'];
			$ask[$exchange] = $result['ask'];
		}
		$buyingExchange = array_search(min($ask), $ask);
		$sellingExchange = array_search(max($bid), $bid);
		$buyingRate = min($ask);
		$sellingRate = max($bid);
		if((((100 / $buyingRate) * $sellingRate) - 100) > $profit) {
			echo "Sell from $sellingExchange at $sellingRate and Buy from $buyingExchange at $buyingRate\n";
			$balances = $db->row("SELECT * FROM balances ORDER BY date DESC limit 1");
			//$usdBalance = getBalance($buyingExchange, "USD");
			//$btcBalance = getBalance($sellingExchange, "BTC");
			$usdBalance = $balances['usd'];
			$btcBalance = $balances['btc'];

			$sellingTotal = number_format((float)($btcBalance * $sellingRate), 2, '.', '');
			$buyingTotal = number_format((float)($usdBalance / $buyingRate), 8, '.', '');
			$db->query("INSERT INTO transactions (type, exchange, price, amount) VALUES(:type, :exchange, :price, :amount)", array("type"=>"SELL", "exchange"=>$sellingExchange, "price"=>$sellingRate, "amount"=>$btcBalance));
			$db->query("INSERT INTO transactions (type, exchange, price, amount) VALUES(:type, :exchange, :price, :amount)", array("type"=>"BUY", "exchange"=>$buyingExchange, "price"=>$buyingRate, "amount"=>$buyingTotal));
			$db->query("INSERT INTO balances (usd,btc) VALUES(:usd, :btc)", array("usd"=>$sellingTotal, "btc"=>$buyingTotal));	
		}
		$db->CloseConnection();
		sleep($interval);
	}
?>
