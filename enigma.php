<?php
	include 'enigma-include.php';
	include "set.php";
	require "db.php";
	session_start();
	if(!isset($_SESSION["ID"]))
	{
		$_SESSION["ID"] = creaSessione();
	}
		if(isset($_SESSION["login"]))
	{
		$login_link = "login.php?d=1";
		$login_name = "Disconnetti";
	}
	else
	{
		$login_link = "login.php";
		$login_name = "Accedi";
	}
	if(isset($_GET["rot1"]) && isset($_GET["rot2"]) && isset($_GET["rot3"]) && isset($_GET["pos1"]) && isset($_GET["pos2"]) && isset($_GET["pos3"]) && isset($_GET["testo"]))
	{
		$_SESSION["rot"] = rotori($_GET["rot1"], $_GET["rot2"], $_GET["rot3"], $_GET["pos1"], $_GET["pos2"], $_GET["pos3"]);
		$testo = strtoupper($_GET["testo"]);
		if(isset($_GET["scambio"]))
		{
			$sca = strtoupper($_GET["scambio"]);
			$cifrato = cifra_stringa($_GET["rot1"], $_GET["rot2"], $_GET["rot3"], $_GET["pos1"], $_GET["pos2"], $_GET["pos3"], scambio($testo, $sca));
		}
		else
			$cifrato = cifra_stringa($_GET["rot1"], $_GET["rot2"], $_GET["rot3"], $_GET["pos1"], $_GET["pos2"], $_GET["pos3"], $testo);
	}
?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Enigma</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="icon" id="favicon" href="face.png" />
		<link rel="stylesheet" href="styles/xsoftware.css">
	</head>
	<body>
	<?php print_menu(); ?>
	<form action=enigma.php method="GET">
	<div class="row">
		<div class="half">
			<h2>Testo in chiaro</h2>
		<p>
		<textarea class="edit" name="testo" placeholder="CIAO"><?php echo $testo; ?></textarea></p>
		<p>
		</div>
		<div class="half">
			<h2>Testo cifrato</h2>
			<p>
			<textarea class="edit" placeholder="XZVQ"><?php echo $cifrato; ?></textarea></p>
			<p>
		</div>
	</div>
	<div style="padding: 12px;">
	<input type="text" name="scambio" placeholder="Le lettere da scambiare, es. 'CDIPAYOS'" value="<?php echo $sca; ?>">
	</br>
	</br>
	<?php
		$query = $conn->query("SELECT ID, nome FROM rotori");
		if ($query->num_rows > 0)
		{
			while($row = $query->fetch_assoc())
			{
				$id[] = $row["ID"];
				$nome[] = $row["nome"];
			}
		}
		else
		{
			echo "ERRORE! Rotori non trovati!";
		}
		
		for($k = 1; $k < 4; $k++)
		{
			echo "<div class='row'>
			<div class='column' style='width: 10%;'>
			<b>ROTORE ".$k.":</b>
			</div>
			<div class='column' style='width: 10%;'>
			<input type='number' name='pos".$k."' min='0' max='25' value='0'>
			</div>
			<div class='column' style='width: 70%;'>
			<select name='rot".$k."'>\r\n";
			
			for($i = 0; $i < count($id); $i++)
			{
				echo "<option value='".$id[$i]."'>".$nome[$i]."</option>\r\n";
			}
			echo "</select></div></div></br>";
		}
		$conn->close();
	?>
	<input class="button-std" type="submit" value="Cifra">
	</div>
	</form>
	<div style='background-color:#ffb342; width:100%'>
		<h2>Informazioni per gli Sviluppatori</h2>
		<div class="grid-container">
			<textarea class="infoarea" readonly>rot[0]:<?php echo $_SESSION["rot"][0]; ?></textarea>
			<textarea class="infoarea" readonly>rot[1]:<?php echo $_SESSION["rot"][1]; ?></textarea>
			<textarea class="infoarea" readonly>rot[2]:<?php echo $_SESSION["rot"][2]; ?></textarea>
			<textarea class="infoarea" readonly>ID:<?php echo $_SESSION["ID"]; ?></textarea>
		</div>
	</div>
	</br>
	<?php print_footer(); ?>
	</body>
</html>
