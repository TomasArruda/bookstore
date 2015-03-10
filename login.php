<?php
	require_once('config.php');

	session_start();

	$dbh = connect();

	$username = $_POST['username'];
	$password = $_POST['password'];

	$return = array("success" => false, "menssage" => "Was not possible to login"); 

    $queryLogin = $dbh->prepare("SELECT * FROM users WHERE username = :un");
    
    if($queryLogin->execute(array(":un" => $username))) { // Try to execute the query
        if($queryLogin->rowCount() > 0) { // Login found
            $row = $queryLogin->fetch();
            if($row['password'] == SHA1($password)) { // Log in
                

                $_SESSION['logged'] = true;
                $_SESSION['user'] = array();
                
                foreach($row as $info => $value) {
                    if($info != 'password' && !is_numeric($info)) {
                        $_SESSION['user'][$info] = $value;
                    }
                }

                $return = array("success" => true, "menssage" => "User logged in the system.");

            } else { // Password doesn't match
                $return = array("success" => false, "menssage" => "Wrong password.");
            }
        } else { // Login not found in the database
            $return = array("success" => false, "menssage" => "Invalid username.");
        }
    } 
    
	exit(json_encode($return));

?>