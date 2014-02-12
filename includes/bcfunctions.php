<?php

// bcfunctions.php - functions for bitcoin related things

// save current user balance to session so we dont make multiple calls
function saveCurrentBalance($bc, $curaddr) {
	$_SESSION['userbalance'] = number_format($bc->getbalance($_SESSION['username'], 6),8);
}

// this function takes a jsonRPCClient object, and a bitcoin address as input and if the address given 
// is empty, it does nothing, and generates a new address if it has been used
function refreshAddressIfStale($bc, $curaddr) {
	$curaddressBalance = number_format($bc->getreceivedbyaddress($curaddr),8);
	if($curaddressBalance == 0) {
		$sendaddr = $curaddr;
	} else {
		$sendaddr = $bc->getnewaddress($_SESSION['username']);
	}
	return $sendaddr;
}

?>
