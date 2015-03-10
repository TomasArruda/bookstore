<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "this review does not exist"); 

	$review_id = $_GET['review_id'];
	
	$query = $dbh->prepare("SELECT book_id, user, review, rating FROM reviews WHERE id = :rid");
	if($query->execute(array(":rid" => $review_id))){
		$row = $query->fetch();
		$count = $query->rowCount();
		if($count > 0){
			$return = array("success" => true, "book_id" => $row['book_id'], "user" => $row['user'], "review" => $row['review'], "rating" => $row['rating']); 
		}
	}

	exit(json_encode($return));

?>