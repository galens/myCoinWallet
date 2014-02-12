<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>MyCoinWallet - Deposit</title>
		<link rel="stylesheet" href="css/styles.css"  type="text/css" />
	</head>
	<body>
		<div id="main">
			<div id="top"><div style='float:left;position:relative;top:25px;'><h2>MyCoinWallet</h2></div><div class="logomargin"><img src='images/logo-mockup2.png' /></div></div>
			<div id="wrapper">
				<div id="content">
					<div class="innermargin">
						<h1>MyCoinWallet Deposit</h1>
						<br />
						To make a deposit, please send bitcoin to the address below.
						<?php
						$_SESSION['userid'] = 1;			// this is a substitute for a proper login system
						$_SESSION['username'] = 'user1';
						require_once('includes/config.php');
						require_once('includes/jsonRPCClient.php');
						require_once('includes/bcfunctions.php');
						
						$bitcoin = new jsonRPCClient('https://' . USER . ':' . PASS . '@' . SERVER . ':' . PORT .'/',false);
						
						// check for session address
						if(isset($_SESSION['sendaddress'])) {
							$sendaddress = refreshAddressIfStale($bitcoin,$_SESSION['sendaddress']); // session exists, check if its been used before
							$_SESSION['sendaddress'] = $sendaddress;
						} else {
							// if address already exists in wallet (or new unfortunately), check the balance and set as main receivable address if zero
							$curaddress = $bitcoin->getaccountaddress($_SESSION['username']);
							$sendaddress = refreshAddressIfStale($bitcoin,$curaddress);
							$_SESSION['sendaddress'] = $sendaddress;
						}

						// save current balance
						saveCurrentBalance($bitcoin, $_SESSION['sendaddress']);
						echo "<b>" . $_SESSION['sendaddress'] . "</b>";
						
						?>
					</div>
				</div>
			</div>
			<div id="menu">
				<div class="menumargin">
					<a href='index.php'>Home</a>
					<a href='account.php'>Account</a>
					<a href='deposit.php'>Deposit</a>
					<a href='withdraw.php'>Withdraw</a>
					<a href='contact.php'>Contact</a>
					<a href='#'>Logout</a>
				</div>
			</div>
			<div id="footer"><a href="index.php">Home</a> | <a href="account.php">Account</a> | <a href="deposit.php">Deposit</a> | <a href="withdraw.php">Withdraw</a> | <a href="contact.php">Contact</a> | <a href="#">Logout</a> | </div>
		</div>
	</body>
</html>
