<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "This books does not exist"); 
	
	$book_id= $_GET['book_id'];

	$query = $dbh->prepare("SELECT id, title, authors, description, price FROM books WHERE id = :bid");
	if($query->execute(array(":bid" => $book_id))){
		$row = $query->fetch();
		$count = $query->rowCount();
		if($count >0){
			$return = array("success" => true, "Book" => array("book_id" => $row['id'] , "title" => $row['title'],"authors" => $row['authors'], "description" => $row['description'], "price" => $row['price'])); 
			$query = $dbh->prepare("SELECT id FROM reviews WHERE book_id = :bid");
			if($query->execute(array(":bid" => $book_id))){
				$row = $query->fetchAll();
				$count = $query->rowCount();
				$return["reviews"] = null;
				if($count > 0){
					for($i = 0; $i < $count; $i++){
						$return["reviews"][$i] = $row[$i]['id'];
					}
				}
			}
		}
	}

	exit(json_encode($return));

?>