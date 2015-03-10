<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 
	
	if(isset($_SESSION['logged'])){
			$return[0] = array("book_id" => false, "title" => false,"authors" => false, "description" => false, "price" => false); 
			$start = 0;
			$length = NULL;
			
			if (!$_GET['start'] == ""){
				$start = $_GET['start'];
			}
			if (!$_GET['length'] == ""){
				$length = $_GET['length'];
			}

		}if($_SESSION['user']['type'] == "admin"){

		}
	}
	exit(json_encode($return));

?>