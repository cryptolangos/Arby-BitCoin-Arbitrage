<?php 
	$activeExchanges = array();
        foreach($exchanges as $key => $value) {
                if($value) {
                        array_push($activeExchanges, $key);
			require($root.'/exchanges/'.$key.'.php');
                }
        }

?>
