<?php
function print_menu_param($links, $texts)
{
	echo "<ul class='header'>";
	for($i=0; $i < count($links); $i++)
		echo "<li><a href='".$links[$i]."'>".$texts[$i]."</a></li>";
	echo "</ul>";
}

function print_menu()
{
	if(isset($_SESSION["USER_ID"]))
		print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "login.php?d=1", "user.php"), array("Home", "Enigma", "Source Code", "Disconnetti", "Profilo"));
	else
		print_menu_param(array("index.php", "enigma.php",  "https://gitlab.com/EnigmaXS/enigma-machine", "login.php"), array("Home", "Enigma", "Source Code", "Accedi"));
}
function print_footer()
{
	echo "<div class='footer'>Luca Gasperini</br>Sito per l'Esame di Stato 2018</div>";
}

function print_comments($i)
{
	if(is_numeric($i))
	{
		require 'db.php';
		$query = $conn->query(
		"SELECT ID, testo, time, alias, userid, genitore
		FROM (
			SELECT commenti.ID, testo, FROM_UNIXTIME(data) AS time, alias, fk_utenti AS userid, commenti.ID AS genitore
			FROM commenti, utenti
			WHERE fk_utenti=utenti.id AND fk_materie = ".$i." AND fk_genitore IS NULL
			UNION
			SELECT commenti.ID, testo, FROM_UNIXTIME(data) AS time, alias, fk_utenti AS userid, fk_genitore AS genitore
			FROM commenti, utenti
			WHERE fk_utenti=utenti.id AND fk_materie = ".$i." AND fk_genitore IS NOT NULL
		) t
		ORDER BY genitore, time");
		$count = $query->num_rows;
		for($n = 0; $n < $count; $n++)
		{
			$row = $query->fetch_assoc();
			echo "<div class='row'>";
			if($row["ID"] != $row["genitore"])
				echo "<div style='width: 5%; float:left;' class='vl'></div><div class='comments-element-child'>";
			else
				echo "<div class='comments-element'>";
			echo $row["alias"]." alle ".$row["time"].
			"<div class='text'>".$row["testo"]."</div><form action='materia.php?i=".$i."' method='POST'>
				<input style='display:none;' type='number' name='id' value='".$row["ID"]."'/>";
			if($row["ID"] == $row["genitore"])
				echo "<input class='button-std' type='submit' name='rispondi' style='width: 86px' value='Rispondi'/>";
			if($row["userid"] == $_SESSION["USER_ID"] || $_SESSION["USER_LVL"] > 50)
				echo "<input class='button-std' type='submit' name='elimina' style='width: 86px' value='Elimina'/>";
			echo "</form></div></div>";
		}
	}
}

function keyarray_match($array, $word)
{
	$offset = array();
	foreach ($array as $key => $value)
	{
		if(strpos($key, $word) !== false)
			$offset[] = $key;
	}
	return $offset;
}

function strtoken($string, $value)
{
	$index = strpos($string, $value) + 1;
	for($i=$index; $i < strlen($string); $i++)
		if($string[$i] == " ")
			break;
		else
			$offset[] = $string[$i];
			
	return implode($offset);
}

function print_alert()
{
	if(isset($_SESSION["GUI_ALERT"]))
	{
		echo "<div class='".$_SESSION["GUI_ALERT_CLASS"]."'>
		<strong>Attenzione:</strong> ".$_SESSION["GUI_ALERT_MSG"]."</div>";
		unset($_SESSION["GUI_ALERT"]);
		unset($_SESSION["GUI_ALERT_CLASS"]);
		unset($_SESSION["GUI_ALERT_MSG"]);
	}
}

function show_alert($message, $class)
{
	$_SESSION["GUI_ALERT"] = 1;
	$_SESSION["GUI_ALERT_MSG"] = $message;
	$_SESSION["GUI_ALERT_CLASS"] = $class;
}

function error_page($message)
{
	$_SESSION["ERROR_MESSAGE"] = " ".$message;
	header("Location: error.php");
}

?>
