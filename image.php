<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	$return = "HTTP/1.0 404 Not Found";
	
	
	$id = $_GET['book_id'];

	$query = $dbh->prepare("SELECT image FROM books WHERE id = :id");
	if($query->execute(array(":id" => $id))){
		$row = $query->fetch();
		$count = $query->rowCount();
		if($count > 0 ){
			
			$return = imagecreatefromstring($row['image']);
			ob_start(); 
			imagejpeg($return, null, 80);
			$data = ob_get_contents();
			ob_end_clean();
			$return = '<img src="data:image/jpg;base64,' .  base64_encode($data)  . '" />';
		}

	}

	exit($return);

?>