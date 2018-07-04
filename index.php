<?php
	session_start();
	include 'set.php';
	require 'db.php';
	$query = $conn->query("SELECT ID, nome, argomento, immagine FROM materie");
?>
<html>
<head>
<link rel="icon" id="favicon" href="face.png" />
<title>Tesina di LUCA GASPERINI - Classe VCi</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="styles/xsoftware.css">
</head>
<body>
<?php
	if(isset($_SESSION["USER_ID"]))
		print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "edit-index.php", "login.php?d=1", "user.php"), array("Home", "Enigma", "Source Code", "Modifica", "Disconnetti", "Profilo"));
	else
		print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "login.php"), array("Home", "Enigma", "Source Code", "Accedi"));
?>
    <h1>
     <u>Mappa concettuale <i>(su sito xsoftware.eu/esame)</i></u>  
    </h1>
    <h1>
     Luca Gasperini - Classe VCi 
    </h1>
    
<div class='gallery cf'>
<h2>Seconda Guerra Mondiale</h2>
<?php
for($i=0; $i < $query->num_rows; $i++)
{
$row = $query->fetch_assoc();
echo "
  <div class='gallery-element'>
    <a href='materia.php?i=".$row["ID"]."'><img class='gallery-image-resize' src='".$row["immagine"]."'/></a>
    <div class='sub'>".$row["nome"].":</br>".$row["argomento"]."</div>
  </div>";
  }
?>
</div>
</br></br>
<?php print_footer(); ?>
</body>
</html>
