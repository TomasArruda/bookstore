<?php
	require_once('config.php');

	session_start();

	$dbh = connect();

	$return = header("HTTP/1.0 403 Forbidden");; 
	
	if(isset($_SESSION['logged'])){
		if($_SESSION['user']['type'] == "user"){
			$book_id = $_GET['book_id'];
			$user = $_GET['user'];

			$query = $dbh->prepare("SELECT times, flag FROM purchases WHERE book_id = :bid AND user = :us");
			$query->execute(array(":bid" => $book_id, ":us" => $user));
			$row = $query->fetch();
			$count = $query->rowCount();

			if($count > 0 && $row['flag'] == 1){
				if($row['times'] == 100){
					$times = $row['times']+1;
					$query = $dbh->prepare("SELECT content FROM books WHERE id = :bid");
					if($query->execute(array(":bid" => $book_id))){
						$row = $query->fetch();
						$data = $row['content'];

						$query = $dbh->prepare("UPDATE purchases SET times = :ti WHERE book_id = :bid AND user = :us");
						$query->execute(array(":ti" => $times, ":bid" => $book_id, ":us" => $user));

						//$return = '<iframe src= "data:application/pdf;base64,'.base64_encode($data).'" />';
					}
				}
			}
		}
	}
	exit($return);
?>