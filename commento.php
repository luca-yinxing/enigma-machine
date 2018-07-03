<?php

include "set.php";

if(!isset($_SESSION["USER_ID"]))
	error_page("Devi loggarti per lasciare un commento!");

session_start();
require "db.php";

if(isset($_POST["i"]) && isset($_POST["commento"]))
{
	$testo = $_POST["commento"];
	$str = strtoken($testo, "$");
	if(!is_numeric($str))
		$str = NULL;
	else
		$testo = str_replace("$".$str, "", $testo);
		
	$testo = trim($testo);
	$time = time();
	$query = $conn->prepare("INSERT INTO commenti (testo, data, fk_utenti, fk_materie, fk_genitore) VALUE (?,?,?,?,?)");
	$query->bind_param("siiii", $testo, $time, $_SESSION["USER_ID"], $_POST["i"], $str);
	$tmp = $query->execute();

	if($tmp == TRUE)
		show_alert("Il commento è stato pubblicato.", "alert-green");
	else
		show_alert("Si è verificato un errore, il tuo commento non è stato pubblicato!", "alert-red");
		
	$query->close();
}
else
	error_page("I parametri non sono validi!");

header ("Location: materia.php?i=".$_POST["i"]);
?>
