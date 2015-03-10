<?php
	require_once('config.php');

	require_once PAYPAL_PHP_SDK . '/vendor/autoload.php';
	use PayPal\Auth\OAuthTokenCredential;
	use PayPal\Api\ExecutePayment;
	use PayPal\Api\Payment;
	use PayPal\Api\PaymentExecution;
	use PayPal\Rest\ApiContext;

	session_start();

	$configManager = \PPConfigManager::getInstance();
	$cred = new OAuthTokenCredential(
		$configManager->get('acct1.ClientId'),
		$configManager->get('acct1.ClientSecret'));

	$dbh = connect();

	$return = array("success" => false, "menssage" => "you are not logged"); 
	
	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this"); 
		
		if($_SESSION['user']['type'] == "user"){
			$book_id = $_POST['book_id'];
			$user = $_POST['user'];
			$token = $_POST['token'];

			$query = $dbh->prepare("SELECT purchaseID, book_id, user FROM purchases WHERE book_id = :bid AND user = :us");
			$query->execute(array(":bid" => $book_id, ":us" => $user));
			$count = $query->rowCount();

			if($count > 0){

				$query = $dbh->prepare("DELETE FROM purchases WHERE book_id = :bid AND user = :us");
				$query->execute(array(":bid" => $book_id, ":us" => $user));
				$return = array("success" => true, "menssage" => "purchase deleted!"); 
			}else{
				$return = array("success" => false, "menssage" => "this purchase does not exists"); 
			}
		}
	}
	exit(json_encode($return));
?>