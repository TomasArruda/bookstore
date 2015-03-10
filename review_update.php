<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 

	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this"); 
		
		$book_id = $_POST['book_id'];
		$user = $_POST['user'];
		$review = $_POST['review'];
		$rating = $_POST['rating'];

		if($_SESSION['user']['username'] == $user){
			$query = $dbh->prepare("UPDATE reviews SET review =:re, rating = :ra WHERE book_id = :bid AND user = :us");
			if($query->execute(array(":re" => $review, ":ra" => $rating, ":bid" => $book_id, ":us" => $user))){
				$return = array("success" => true, "menssage" => "updated"); 
			}else{
				$return = array("success" => false, "menssage" => "Querry problem"); 
			}
		}
	}

	exit(json_encode($return));

?>