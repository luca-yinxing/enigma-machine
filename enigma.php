<?php include 'enigma-include.php'; ?>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Enigma</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="icon" id="favicon" href="face.png" />
		<link rel="stylesheet" href="w3.css">
		<link rel="stylesheet" href="xsoftware.css">
	</head>
	<body>
	<form action=enigma.php method="GET">
	<div class="w3-row-padding">
		<div class="w3-container w3-half"> 
		<div class="w3-container w3-orange">
			<h2>Testo in chiaro</h2>
		</div>
			<p>
			<input class="w3-input w3-border w3-animate-input" name="testo" type="text" style="height:30%" placeholder="One" value="<?php if(isset($_GET["testo"])) { echo htmlentities(strtoupper($_GET["testo"])); }?>"></p>
			<p>
		</div>
		<div class="w3-container w3-half">
		<div class="w3-container w3-orange">
			<h2>Testo cifrato</h2>
		</div>
			<p>
			<input class="w3-input w3-border w3-animate-input" type="text" style="height:30%" placeholder="Three" value="<?php posizione_rotori($_GET["rot1"], $_GET["rot2"], $_GET["rot3"]); if(isset($_GET["testo"])) { echo htmlentities(cifra_stringa($_GET["testo"])); } ?>"></p>
			<p>
		</div>
		Rotore 1:<input type="number" name="rot1" min="0" max="25" value="<?php echo $GLOBALS["pos"][0] ?>">
		Rotore 2:<input type="number" name="rot2" min="0" max="25" value="<?php echo $GLOBALS["pos"][1] ?>">
		Rotore 3:<input type="number" name="rot3" min="0" max="25" value="<?php echo $GLOBALS["pos"][2] ?>">
		<select class="w3-select" name="option">
			<option value="" disabled selected>Rotori</option>
			<option value="1">A</option>
			<option value="2">B</option>
			<option value="3">C</option>
		</select>
		<input class="w3-btn w3-orange" type="submit" value="Cifra">
	</div>
	</form>
	<footer>
	<form action=enigma.php method="GET">
	<div class="w3-container w3-orange" style="width:100%">
		<h2>Informazioni per gli Sviluppatori</h2>
		<div class="grid-container">
			<textarea class="w3-container" readonly>pos:<?php echo $GLOBALS["pos"][0]."-".$GLOBALS["pos"][1]."-".$GLOBALS["pos"][2]; ?></textarea>
			<textarea class="w3-container" readonly>rot[0]:<?php mostra_rotore(0); ?></textarea>
			<textarea class="w3-container" readonly>rot[1]:<?php mostra_rotore(1); ?></textarea>
			<textarea class="w3-container" readonly>rot[2]:<?php mostra_rotore(2); ?></textarea>
		</div>
		<input class="w3-btn w3-orange" name="gen" type="submit" value="Genera Rotori">
		<?php
		if(isset($_GET['gen']))
		{
			genRotore();
		}
	?>
	</div>
	</form>
	</footer>
	</body>
</html>
