<?php
	require_once('config.php');

	session_start();

	$dbh = connect();

	$return = array("success" => false, "menssage" => "You are not logged"); 
    
    if(isset($_SESSION['logged'])) { 
        unset($_SESSION['logged']);
        unset($_SESSION['user']);
        session_destroy();          
        $return = array("success" => true, "menssage" => "logged out successfuly");
    }
    
	exit(json_encode($return));

?>