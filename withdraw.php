<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>MyCoinWallet - Withdraw</title>
		<link rel="stylesheet" href="css/styles.css"  type="text/css" />
	</head>
	<body>
		<div id="main">
			<div id="top"><div style='float:left;position:relative;top:25px;'><h2>MyCoinWallet</h2></div><div class="logomargin"><img src='images/logo-mockup2.png' /></div></div>
			<div id="wrapper">
				<div id="content">
					<div class="innermargin">
						<h1>MyCoinWallet Withdraw</h1>
						<br />
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
							
							$userBalance = $_SESSION['userbalance'];
							
							// check for post request
							if(isset($_POST['sendaddress'])) {
								if(isset($_POST['sendamount'])) {
									$postSendAddress = $_POST['sendaddress'];
									$postSendAmount = $_POST['sendamount'];
									//echo $postSendAddress;
									//echo $postSendAmount;
									
									if($postSendAmount > $_SESSION['userbalance']) { // they tried to send more money than they have this is possible as accounts can go negative
										echo "<font color='red'><b>You may not send more money than you have!</b></font>";
									} elseif($postSendAmount < 0) {	// they tried to send a negative number
										echo "<font color='red'><b>Try to be more positive.</b></font>";
									} else {	// probably should be more error checking here, todo or something
										$transid = $bitcoin->sendfrom($_SESSION['username'], $postSendAddress, floatval($postSendAmount), 6); // require minimum 6 confirmations of credit
										if(isset($transid)) {
											echo "<font color='red'><b>Funds successfully sent.</b></font><br />";
											echo "Transaction Id: ". $transid . "<br />";
										}
									}
								}
							}
							
							// save current balance
							saveCurrentBalance($bitcoin, $_SESSION['sendaddress']);
							
							$userBalance = $_SESSION['userbalance'];
							
							echo "Current Balance: ". $userBalance ."<br />";
							
							// echo send form
							echo "You may currently ";
							if($userBalance > 0) {
								echo "send funds.<form id=\"sendfund\" name=\"sendfund\" method=\"post\" action=\"withdraw.php\">
								Address <input name=\"sendaddress\" type=\"text\" id=\"textfield\" value=\"\" size=\"50\" /><br />
								Amount &nbsp;<input name=\"sendamount\" type=\"text\" id=\"textfield\" value=\"\" size=\"10\" />
								<input type=\"submit\" name=\"button\" id=\"button\" value=\"Send\" /></form>";
							} else {
								echo "not send funds. If you have sent funds, please wait for at least six confirmations.  Thank you.<br />";
							}
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
