<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 

	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this");

		$review_id = $_GET['review_id'];

		$query = $dbh->prepare("SELECT user FROM reviews WHERE id = :rid");
		$query->execute(array(":rid" => $review_id));
		$row = $query->fetch();
		$count = $query->rowCount();

		if($count > 0){
			if($_SESSION['user']['type'] == "admin" || $_SESSION['user']['username'] == $row['user']){
				$query = $dbh->prepare("DELETE FROM reviews WHERE id = :rid");
				if($query->execute(array(":rid" => $review_id))){
					$return = array("success" => true, "menssage" => "review deleted"); 
				}
			}
		}else{
			$return = array("success" => false, "menssage" => "this review does not exists"); 
		}
	}

	exit(json_encode($return));

?>