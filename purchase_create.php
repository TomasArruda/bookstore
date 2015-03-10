<?php
	require_once('config.php');

	require_once PAYPAL_PHP_SDK . '/vendor/autoload.php';
	use PayPal\Api\Address;
	use PayPal\Api\Amount;
	use PayPal\Api\Payer;
	use PayPal\Api\Payment;
	use PayPal\Auth\OAuthTokenCredential;
	use PayPal\Api\FundingInstrument;
	use PayPal\Api\RedirectUrls;
	use PayPal\Api\Transaction;
	use PayPal\Rest\ApiContext;

	session_start();

	$configManager = \PPConfigManager::getInstance();
	$cred = new OAuthTokenCredential(
	$configManager->get('acct1.ClientId'),
	$configManager->get('acct1.ClientSecret'));

	function getBaseUrl() {
		$protocol = 'http';
		if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
			$protocol .= 's';
			$protocol_port = $_SERVER['SERVER_PORT'];
		} else {
			$protocol_port = 80;
		}

		$host = $_SERVER['HTTP_HOST'];
		$port = $_SERVER['SERVER_PORT'];
		$request = $_SERVER['PHP_SELF'];
		return dirname($protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request);
	}

	$dbh = connect();

	$return = array("success" => false, "menssage" => "you are not logged"); 
	
	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this"); 
		
		if($_SESSION['user']['type'] == "user"){
			$book_id = $_GET['book_id'];
			$user = $_GET['user'];

			$query = $dbh->prepare("SELECT purchaseID, book_id, user FROM purchases WHERE book_id = :bid AND user = :us");
			$query->execute(array(":bid" => $book_id, ":us" => $user));
			$row = $query->fetch();
			$count = $query->rowCount();

			if($count == 0){
				$query = $dbh->prepare("SELECT price FROM books WHERE id = :id");
				if($query->execute(array(":id" => $book_id))){
					$row = $query->fetch();
					$count = $query->rowCount();

					if($count > 0){
						$payer = new Payer();
						$payer->setPayment_method("paypal");

						$amount = new Amount();
						$amount->setCurrency("USD");
						$amount->setTotal($row['price']);

						$transaction = new Transaction();
						$transaction->setAmount($amount);
						$transaction->setDescription("Buying book");

						$baseUrl = getBaseUrl();
						$redirectUrls = new RedirectUrls();
						$redirectUrls->setReturn_url("$baseUrl/approve.php");
						$redirectUrls->setCancel_url("$baseUrl/purchase_create.php");

						$payment = new Payment();
						$payment->setIntent("sale");
						$payment->setPayer($payer);
						$payment->setRedirect_urls($redirectUrls);
						$payment->setTransactions(array($transaction));

						$apiContext = new ApiContext($cred, 'Request' . time());

						try {
							$payment->create($apiContext);
						} catch (\PPConnectionException $ex) {
							echo "Exception: " . $ex->getMessage() . PHP_EOL;
							var_dump($ex->getData());	
							exit(1);
						}

						$query = $dbh->prepare("INSERT INTO purchases (purchaseID, user, book_id, times, flag) VALUES (:pid, :us, :bid, :ti, :fl)");
						if(!$query->execute(array(":pid" => $payment->getId(), ":us" => $user, ":bid" => $book_id, ":ti" => 0, ":fl" => 0))){
							$return = array("success" => false, "menssage" => "querry erro"); 
						}

						foreach($payment->getLinks() as $link) {
							if($link->getRel() == 'approval_url') {
								$redirectUrl = $link->getHref();
							}
						}
						if(isset($redirectUrl)) {
							header("Location: $redirectUrl");
							exit();
						}
						
					}else{
						$return = array("success" => false, "menssage" => "this book does not exists");
					}
				}else{
					$return = array("success" => false, "menssage" => "querry erro"); 
				}
			}else{
				$payment = Payment::get($row['purchaseID']);

				if($payment->getState() == "created"){
					foreach($payment->getLinks() as $link) {
						if($link->getRel() == 'approval_url') {
							$redirectUrl = $link->getHref();
						}
					}
					if(isset($redirectUrl)) {
						header("Location: $redirectUrl");
						exit();
					}

				}else if($payment->getState() == "approved"){
					$return = array("success" => false, "menssage" => "payment already accepted"); 
				}
			}
		}
	}
	exit(json_encode($return));
?>