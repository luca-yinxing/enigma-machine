 <?php
	session_start();
	include "set.php";
	require "db.php";
	
	if(!isset($_SESSION["USER_ID"]))
		error_page("Devi loggarti per accedere!");
		
	$query = $conn->prepare("SELECT alias, nome, pass, permessi, FROM_UNIXTIME(ultimo_accesso), FROM_UNIXTIME(data_creazione) FROM utenti WHERE ID=?");
	$query->bind_param("i", $_SESSION["USER_ID"]);
	$query->execute();
	$query->bind_result($alias, $nome, $pass, $permessi, $ultimo_accesso, $data_creazione);
	$query->fetch();
	$query->close();
	
	$conn->query("START TRANSACTION");
	
	if(isset($_POST["alias"]) && $_POST["alias"] != $alias)
	{
		$query = $conn->prepare("UPDATE utenti SET alias=? WHERE ID=?");
		$query->bind_param("si", $_POST["alias"], $_SESSION["USER_ID"]);
		$tmp = $query->execute();
		$query->close();
	}
	
	if(isset($_POST["nome"]) && $_POST["nome"] != $nome)
	{
		$query = $conn->prepare("UPDATE utenti SET nome=? WHERE ID=?");
		$query->bind_param("si", $_POST["nome"], $_SESSION["USER_ID"]);
		$tmp = $query->execute();
		$query->close();
	}
	if(isset($_POST["permessi"]) && $_POST["permessi"] != $permessi)
	{
		$query = $conn->prepare("UPDATE utenti SET permessi=? WHERE ID=?");
		$query->bind_param("ii", $_POST["permessi"], $_SESSION["USER_ID"]);
		$tmp = $query->execute();
		$query->close();
	}
	if(isset($_POST["id"]) && $_POST["id"] != $_SESSION["USER_ID"])
	{
		$query = $conn->prepare("UPDATE utenti SET ID=? WHERE ID=?");
		$query->bind_param("ii", $_POST["id"], $_SESSION["USER_ID"]);
		$tmp = $query->execute();
		$query->close();
	}
	
	if(isset($_POST["pass"]) && !empty($_POST["pass"]))
	{
		$hash = hash("sha256",$_POST["pass"]);
		if($hash != $pass)
		{
			$query = $conn->prepare("UPDATE utenti SET pass=? WHERE ID=?");
			$query->bind_param("si", $hash, $_SESSION["USER_ID"]);
			$tmp = $query->execute();
			$query->close();
		}
	}
	if(isset($tmp))
	{
		if($tmp == TRUE)
		{
			$conn->query("COMMIT");
			show_alert("Il tuo utente è stato modificato, ora inserisci le nuove credenziali.", "alert-green");
			header ("Location: login.php?d=1");
		}
		else
		{
			$conn->query("ROLLBACK");
			show_alert("Si è verificato un errore in una query, nessuna modifica è stata effettuata!", "alert-red");
			header ("Location: user.php");
		}
	}
?>
 <html>
 <head>
 <title>Login</title>
 <link rel="icon" id="favicon" href="face.png" />
 <link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
 <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
 </head>
 <body>
 <?php print_menu(array("index.php", "enigma.php", "login.php?d=1"), array("Home", "Enigma", "Disconnetti"));?>
 <h1>Profilo utente di <?php echo $alias; ?></h1>
		<h2>Modifica</h2>
		<form action="user.php" method="POST">
			<div class="col-25">Alias:</div><div class="col-75"><input type="text" name="alias" value="<?php echo $alias; ?>" maxlength="64"></div>
			<div class="col-25">Nome Utente:</div><div class="col-75"><input type="text" name="nome" value="<?php echo $nome; ?>" maxlength="64"></div>
			<div class="col-25">Password:</div><div class="col-75"><input type="password" name="pass" value="" maxlength="64"></div>
			<div class="col-25">Privilegi:</div><div class="col-75"><input type="number" name="permessi" value="<?php echo $permessi; ?>" readonly></div>
			<div class="col-25">ID Utente:</div><div class="col-75"><input type="number" name="id" value="<?php echo $_SESSION["USER_ID"]; ?>" readonly></div>
			<button class="button-std ">Invia</button>
		</form>
		<h2>Accessi</h2>
		</br>
		<div style="padding: 20px"><b>Ultimo Accesso: <?php echo $ultimo_accesso; ?></br>
		Data Creazione: <?php echo $data_creazione; ?></b>
		</div>
 <?php 
	if($tmp != TRUE)
		print_alert();
		
	print_footer();
 ?>
 </body>
 </html>
