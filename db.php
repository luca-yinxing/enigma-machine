<?php
	require ("config.php");
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if (mysqli_connect_error()) {
		die("Connessione al database fallita: " . mysqli_connect_error());
	}
?>
