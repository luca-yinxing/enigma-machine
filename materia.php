<?php
	session_start();
	require "db.php";
	include "set.php";
	if(!isset($_GET["i"]))
		error_page("Non ho trovato la pagina richiesta!");
	if(!is_numeric($_GET["i"]))
		error_page("Il parametro 'i' è invalido!");
		
	if(isset($_POST["rispondi"]) && isset($_POST["id"]))
	{
		$reply = "$".$_POST["id"]." ";
	}
	if(isset($_POST["elimina"]) && isset($_POST["id"]))
	{
		if($_SESSION["USER_LVL"] <= 50)
		{
			$query = $conn->query("SELECT fk_utenti AS userid FROM commenti WHERE ID=".$_POST["id"]);
			$res = $query->fetch_assoc();
			if($_SESSION["USER_ID"] != $res["userid"])
				error_page("Non hai i permessi per cancellare i commenti!");
		}
		$query = $conn->prepare("DELETE FROM commenti WHERE ID=? OR fk_genitore=?");
		$query->bind_param("ii", $_POST["id"], $_POST["id"]);
		if($query->execute() == TRUE)
			show_alert("Il commento è stato eliminato.", "alert-green");
		else
			show_alert("Impossibile cancellare il commento!", "alert-red");
		$query->close();
	}
		
	$query = $conn->query("SELECT nome, testo FROM materie WHERE ID = ".$_GET["i"]);
	$row = $query->fetch_assoc();
	
	if(isset($_GET["n"]) && $_GET["n"] > 1)
		$image = $_GET["n"];
	else
		$image = 1;
?>

<html>
<head>
<link rel="icon" id="favicon" href="face.png" />
<title><?php echo $row["nome"]; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
</head>
<body>
<?php
	if(isset($_SESSION["USER_ID"]))
		print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "edit.php?i=".$_GET["i"] , "login.php?d=1", "user.php"), array("Home", "Enigma", "Source Code", "Modifica", "Disconnetti", "Profilo"));
	else
		print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "login.php"), array("Home", "Enigma", "Source Code", "Accedi"));
?>
</br>
<h1><?php echo $row["nome"]; ?></h1>
<div class="text">
<?php echo $row["testo"]; ?>
</div>
<div class="slide-image">
<?php 
	$query = $conn->query("SELECT link FROM immagini WHERE fk_materie=".$_GET["i"]." ORDER BY ordine");
	if($query->num_rows < $image)
		$image = $query->num_rows;
		
	for($i=0; $i < $image; $i++)
		$link = $query->fetch_assoc();
	echo "<img src='".$link["link"]."'/>";

	$link_pre="materia.php?i=".$_GET["i"]."&n=".($image-1);
	$link_suc="materia.php?i=".$_GET["i"]."&n=".($image+1);
	echo "	<div class='row' align='center'>
			<div class='half'>
			<a href='".$link_pre."'><button class='button-std'>Precedente</button></a>
			</div>
			<div class='half'>
			<a href='".$link_suc."'><button class='button-std'>Successiva</button></a>
			</div>
			</div>";
?>

</div>
</br>
<?php print_alert();?>
<form action='commento.php' method='POST'>
<div class='comments'>
<h2>Commenti</h2>
<textarea class='edit' name='commento' maxlength='512' placeholder='Aggiungi un commento..'><?php echo $reply; ?></textarea>
<input style='display:none;' type='number' name='i' value='<?php echo $_GET["i"]; ?>'/>
<button class='button-std'>Invia</button>
</form>
<?php  
print_comments($_GET["i"]); 
?>
</div>
</br>
<?php print_footer(); ?>
</body>
</html>
