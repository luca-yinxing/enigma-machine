 <?php
	session_start();
	include "set.php";
	require "db.php";
	
	if(isset($_GET["d"]))
	{
		unset($_SESSION["USER_ID"]);
		unset($_SESSION["USER_LVL"]);
		unset($_SESSION["USER_NAME"]);
	}

	if(isset($_POST["nome"]) && isset($_POST["pass"]) && isset($_POST["t"]))
	{
		$nome = $_POST["nome"];
		$pass = hash("sha256",$_POST["pass"]);
		$time = time();
		if($_POST["t"] == "l")
		{
			$query = $conn->prepare("SELECT ID, permessi FROM utenti WHERE nome=? AND pass=?");
			$query->bind_param("ss", $nome, $pass);
			$query->execute();
			$query->bind_result($id, $lvl);
			$query->fetch();
			$query->close();
			if(!empty($id))
			{
				$query = $conn->query("UPDATE utenti SET ultimo_accesso=".$time." WHERE ID=".$id);
				$_SESSION["USER_ID"] = $id;
				$_SESSION["USER_LVL"] = $lvl;
				$_SESSION["USER_NAME"] = $nome;
				header("Location: index.php");
			}
			else
				show_alert("Login fallito!", "alert-red");
		}
		if($_POST["t"] == "r")
		{
			if(isset($_POST["pass2"]) && isset($_POST["alias"]))
			{
				if($_POST["pass2"] == $_POST["pass"])
				{
					$query = $conn->prepare("INSERT INTO utenti (alias, nome, pass, ultimo_accesso, data_creazione) VALUES (?,?,?,?,?)");
					$query->bind_param("sssii", $_POST["alias"], $nome, $pass, $time, $time);
					if($query->execute() == TRUE)
					{
						show_alert("Il nuovo utente è stato creato!", "alert-green");
						header("Location: login.php");
					}
					else
						error_page("Error: ".$conn->error);
						
					$query->close();
				}
				else
					show_alert("Le password non corrispondono!", "alert-red");
			}
			else
				show_alert("Devi confermare la password!", "alert-red");
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
 <?php print_menu();?>
 <div class="row">
	<div class="half">
		<h2>Accedi</h2>
		<form action="login.php" method="POST">
			<div class="col-25">Nome Utente:</div><div class="col-75"><input type="text" name="nome" value="" maxlength="64"></div>
			<div class="col-25">Password:</div><div class="col-75"><input type="password" name="pass" value="" maxlength="64"></div>
			<input type="hidden" name="t" value="l">
			<button class="button-std">Accedi</button>
		</form>
	</div>
	<div class="half">
		<h2>Registrati</h2>
		<form action="login.php" method="POST">
			<div class="col-25">Nome Utente:</div><div class="col-75"><input type="text" name="nome" value="" maxlength="64"></div>
			<div class="col-25">Alias:</div><div class="col-75"><input type="text" name="alias" value="" maxlength="64"></div>
			<div class="col-25">Password:</div><div class="col-75"><input type="password" name="pass" value="" maxlength="64"></div>
			<div class="col-25">Ripeti Password:</div><div class="col-75"><input type="password" name="pass2" value="" maxlength="64"></div>
			<input type="hidden" name="t" value="r">
			<button class="button-std">Registrati</button>
		</form>
	</div>
 </div>
	<?php 
		print_alert();
		print_footer(); 
	?>
 </body>
 </html>
