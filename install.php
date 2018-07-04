<?php 
	session_start();
	include "set.php";
	
	if(file_exists("config.php") && $_SESSION["USER_LVL"] != 100)
		header("Location: index.php");
		
	if($_POST["user"]["pass"] != $_POST["user"]["pass2"])
	{
		show_alert("Le password non corrispondono!", "alert-red");
		die();
	}
	
	if(isset($_POST["db"]["host"]) && isset($_POST["db"]["user"]) && isset($_POST["db"]["name"])
	&& isset($_POST["user"]["name"]) && isset($_POST["user"]["alias"]) && isset($_POST["user"]["pass"]) && isset($_POST["user"]["pass2"]))
	{
		$pass = hash("sha256", $_POST["user"]["pass"]);
		
		$conn = new mysqli($_POST["db"]["host"], $_POST["db"]["user"], $_POST["db"]["pass"], $_POST["db"]["name"]);

		if (mysqli_connect_error()) {
			show_alert(mysqli_connect_error(), "alert-red");
			die("Connessione al database fallita: " . mysqli_connect_error());
		}
		
		$sql = "CREATE TABLE cifra (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				posizione1 INT,
				posizione2 INT,
				posizione3 INT,
				chiaro TEXT,
				cifrato TEXT,
				fk_rotori1 INT,
				fk_rotori2 INT,
				fk_rotori3 INT,
				fk_sessione INT);
				
				CREATE TABLE commenti (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				testo VARCHAR(512),
				data INT,
				fk_utenti INT,
				fk_materie INT,
				fk_genitore INT);
				
				CREATE TABLE immagini (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				link TEXT,
				ordine INT,
				fk_materie INT);
				
				CREATE TABLE materie (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				nome VARCHAR(64),
				argomento VARCHAR(64),
				immagine TEXT,
				testo TEXT);
				
				CREATE TABLE rotori (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				valore CHAR(26),
				nome TEXT);
				
				CREATE TABLE sessione (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				data TIMESTAMP);
				
				CREATE TABLE utenti (
				ID INT AUTO_INCREMENT PRIMARY KEY,
				alias VARCHAR(64),
				pass CHAR(64),
				nome VARCHAR(64),
				permessi INT,
				ultimo_accesso INT,
				data_creazione INT);";

		if ($conn->multi_query($sql) === TRUE)
			show_alert("Il database è stato configurato correttamente.", "alert-green");
		else 
			show_alert($conn->error, "alert-red");
			
	do {
        $conn->next_result();
    }
    while( $conn->more_results() ); 
		
		$sql = "INSERT INTO utenti (alias, pass, nome, permessi, ultimo_accesso, data_creazione) 
				VALUES ('".$_POST["user"]["alias"]."','".$pass."','".$_POST["user"]["name"]."',100,".time().",".time().");";
		if($conn->query($sql) === TRUE)
			show_alert("Il database è stato configurato correttamente.", "alert-green");
		else 
			show_alert($conn->error, "alert-red");
			
		$conn->close();
		$cfg = fopen("config.php", "w") or die("Unable to open file!");
		$php = "<?php\n
				define('DB_HOST', '".$_POST["db"]["host"]."');\n
				define('DB_USER', '".$_POST["db"]["user"]."');\n
				define('DB_PASS', '".$_POST["db"]["pass"]."');\n
				define('DB_NAME', '".$_POST["db"]["name"]."');\n
				?>";
		fwrite($cfg, $php);
		fclose($cfg);

	}
	else if(isset($_POST["db"]["host"]) || isset($_POST["db"]["user"]) || isset($_POST["db"]["pass"]) || isset($_POST["db"]["name"])
	|| isset($_POST["user"]["name"]) || isset($_POST["user"]["alias"]) || isset($_POST["user"]["pass"]) || isset($_POST["user"]["pass2"]))
		show_alert("Non hai inserito tutti i parametri!", "alert-red");
?>
<html>
 <head>
 <title>Installazione</title>
 <link rel="icon" id="favicon" href="face.png" />
 <link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
 <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
 </head>
 <body>
 <div class="center-form">
 <?php print_alert(); ?>
	<h2>Installazione</h2>
	<form action="install.php" method="POST">
		<div class="col-25">IP Database:</div><div class="col-75"><input type="text" name="db[host]" value=""></div>
		<div class="col-25">Utente Database:</div><div class="col-75"><input type="text" name="db[user]" value=""></div>
		<div class="col-25">Password Database:</div><div class="col-75"><input type="password" name="db[pass]" value=""></div>
		<div class="col-25">Nome Database:</div><div class="col-75"><input type="text" name="db[name]" value=""></div>
		<div class="col-25">Nome Admin:</div><div class="col-75"><input type="text" name="user[name]" value=""></div>
		<div class="col-25">Alias:</div><div class="col-75"><input type="text" name="user[alias]" value=""></div>
		<div class="col-25">Password:</div><div class="col-75"><input type="password" name="user[pass]" value=""></div>
		<div class="col-25">Ripeti Password:</div><div class="col-75"><input type="password" name="user[pass2]" value=""></div>
		<button class="button-std">Installa</button>
	</form>
 </div>
	<?php 
		print_footer(); 
	?>
 </body>
</html>
