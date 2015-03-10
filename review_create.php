<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 

	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this"); 
		
		if($_SESSION['user']['type'] == "user"){
			$book_id = $_POST['book_id'];
			$user = $_POST['user'];
			$review = $_POST['review'];
			$rating = $_POST['rating'];

			$query = $dbh->prepare("SELECT * FROM reviews WHERE user = :us AND book_id = :bid");
			$query->execute(array(":us" => $user, ":bid" => $book_id));
			$usercount = $query->rowCount();
	    	if($usercount == 0){
				$query = $dbh->prepare("INSERT INTO reviews (book_id, user, review, rating) VALUES (:bid, :us, :re, :ra)");
				if($query->execute(array(":bid" => $book_id, ":us" => $user, ":re" => $review, ":ra" => $rating))){
					$query = $dbh->prepare("SELECT id FROM reviews WHERE user = :us AND book_id = :bid");
					$query->execute(array(":us" => $user,  ":bid" => $book_id));
					$row = $query->fetch();
					$return = array("success" => true, "menssage" => "New review added", "review_id" => $row['id']); 
				}else{
					$return = array("success" => false, "menssage" => "Querry problem"); 
				}
			}else{
				$return = array("success" => false, "menssage" => "the user already commented"); 
			}
		}
	}

	exit(json_encode($return));

?>