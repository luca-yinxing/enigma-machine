<?php
	if(file_exists("config.php"))
		require ("config.php");
	else
		header("Location: install.php");
		
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if (mysqli_connect_error()) {
		die("Connessione al database fallita: " . mysqli_connect_error());
	}
	if(is_resource($conn)) 
	{ 
		$conn->query($conn, "SET NAMES 'utf8'"); 
		$conn->query($conn, "SET CHARACTER SET 'utf8'"); 
	} 
?>
