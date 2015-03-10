<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return[0] = array("book_id" => false, "title" => false,"authors" => false, "description" => false, "price" => false); 

	
	$title = $_GET['title'];
	$authors = $_GET['authors'];
	$start = 0;
	$length = NULL;

	if (!$_GET['start'] == ""){
		$start = $_GET['start'];
	}
	if (!$_GET['length'] == ""){
		$length = $_GET['length'];
	}

	$query = $dbh->prepare("SELECT id, title, authors, description, price FROM books WHERE title LIKE '%".$title."%' AND authors LIKE '%".$authors."%'");
	if($query->execute()){
		$row = $query->fetchAll();
		$count = $query->rowCount();
		if($length == NULL){
			$length = $count;
		}
		for($i = 0; $i < $length-$start; $i++){
			$return[$i] = array("book_id" => $row[$start+$i]['id'] , "title" => $row[$start+$i]['title'],"authors" => $row[$start+$i]['authors'], "description" => $row[$start+$i]['description'], "price" => $row[$start+$i]['price']); 
		}
	}

	exit(json_encode($return));

?>