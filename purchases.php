<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 
	
	if(isset($_SESSION['logged'])){
		$user = $_GET['user'];
		$book_id = $_GET['book_id'];
		$start = 0;
		$length = NULL;
		
		if (!$_GET['start'] == ""){
			$start = $_GET['start'];
		}
		if (!$_GET['length'] == ""){
			$length = $_GET['length'];
		}
		if($_SESSION['user']['type'] == "user"){
			$return = array("success" => false, "menssage" => "you have no authorization");
			if($_SESSION['user']['username'] == $user){
				$query = $dbh->prepare("SELECT book_id, user FROM purchases WHERE user = :us");
				if($query->execute(":us" => $user)){
					$row = $query->fetchAll();
					$count = $query->rowCount();
					if($length == NULL){
						$length = $count;
					}
					$return = NULL;
					for($i = 0; $i < $length-$start; $i++){
						$return[$i] = array("book_id" => $row[$start+$i]['book_id'] , "user" => $row[$start+$i]['user']); 
					}
				}
			}
		}else if($_SESSION['user']['type'] == "admin"){
			$query = $dbh->prepare("SELECT book_id, user FROM purchases WHERE user LIKE '%".$user."%' OR book_id = ".$book_id.);
			if($query->execute(":us" => $user)){
				$row = $query->fetchAll();
				$count = $query->rowCount();
				if($length == NULL){
					$length = $count;
				}
				$return = NULL;
				for($i = 0; $i < $length-$start; $i++){
					$return[$i] = array("book_id" => $row[$start+$i]['book_id'] , "user" => $row[$start+$i]['user']); 
				}
			}
		}
	}
	exit(json_encode($return));

?>