<?php
	require_once('config.php');
	session_start();

	$dbh = connect();

	$username = $_POST['username'];
	$password = SHA1(strip_tags($_POST['password']));
	$email = $_POST['email'];

	$return = array("success" => false, "menssage" => "Was not possible to select an user."); ;

	$query = $dbh->prepare("SELECT username FROM users WHERE username = :us");
	if($query->execute(array(":us" => $username))) {
	    $usercount = $query->rowCount();
	    if($usercount == 0){
			$query = $dbh->prepare("INSERT INTO users ( username, password, email, type) VALUES (:us, :pa, :em, :ty)");
			$query->execute(array(":us" => $username, ":pa" => $password, ":em" => $email, ":ty" => "user"));
			$return = array("success" => true, "menssage" => "User was create successfully."); 
		}else{
			$return = array("success" => false, "menssage" => "The username already exists."); 
		}
	}
	
	exit(json_encode($return));

?>