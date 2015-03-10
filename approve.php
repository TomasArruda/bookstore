<?PHP
	require_once('config.php');

	session_start();

	exit(json_encode(array("success" => true, "menssage" => "payment approved")));
?>