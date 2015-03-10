<?php
	require_once('config.php');
	session_start();

	$dbh = connect();
	
	$return = array("success" => false, "menssage" => "you are not logged"); 

	if(isset($_SESSION['logged'])){
		$return = array("success" => false, "menssage" => "you have no authorization to do this"); 
		
		if($_SESSION['user']['type'] == "admin"){
			$title = $_POST['title'];
			$authors = $_POST['authors'];
			$description = $_POST['description'];
			$price = $_POST['price'];
			if(is_uploaded_file($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['content']['tmp_name'])){
				$image = file_get_contents($_FILES['image']['tmp_name']);
				$content = file_get_contents($_FILES['content']['tmp_name']);
				
				$query = $dbh->prepare("INSERT INTO books ( title, authors, description, price, image, content) VALUES (:ti, :au, :de, :pr, :im, :co)");
				if($query->execute(array(":ti" => $title, ":au" => $authors, ":de" => $description, ":pr" => $price, ":im" => $image, ":co" => $content ))){
					$return = array("success" => true, "menssage" => "New book added to stock"); 
				}else{
					$return = array("success" => false, "menssage" => "Querry problem"); 
				}
			}else{
				$return = array("success" => false, "menssage" => "no file attached"); 
			}
		
		}
	}

	exit(json_encode($return));

?>