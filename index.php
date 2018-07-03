<?php
	session_start();
	include "set.php";
?>
<html>
<head>
<link rel="icon" id="favicon" href="face.png" />
<title>Tesina di LUCA GASPERINI - Classe VCi</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="styles/xsoftware.css">
</head>
<body>
<?php print_menu(); ?>
    <h1>
     <u>Mappa concettuale <i>(su sito xsoftware.eu/esame)</i></u>  
    </h1>
    <h1>
     Luca Gasperini - Classe VCi 
    </h1>
    
    

<div class="gallery cf">
<h2>Seconda Guerra Mondiale</h2>
  <div class="gallery-element">
    <a href="materia.php?i=2"><img class="gallery-image-resize" src="img/giuseppe-ungaretti.jpg"/></a>
    <div class="sub">Italiano:</br>Giuseppe Ungaretti</div>
  </div>
  <div class="gallery-element">
    <a href="materia.php?i=1"><img class="gallery-image-resize" src="img/soldati-italiani.jpg"/></a>
    <div class="sub">Storia:</br>Italia in guerra</div>
  </div>
  <div class="gallery-element">
    <a href="materia.php?i=5"><img class="gallery-image-resize" src="img/alan-turing.jpg"/></a>
    <div class="sub">Inglese:</br>Alan Turing</div>
  </div>
    <div class="gallery-element">
    <a href="materia.php?i=3"><img class="gallery-image-resize" src="img/olimpiadi_berlino.jpg"/></a>
    <div class="sub">Scienze Motorie:</br>Olimpiadi del 1936</div>
  </div>
  <div class="gallery-element">
     <a href="materia.php?i=4"><img class="gallery-image-resize" src="img/robert-oppenheimer.jpg"/></a>
     <div class="sub">Gestione Progetti:</br>Robert Oppenheimer</div>
  </div>
  <div class="gallery-element">
    <a href="materia.php?i=6"><img class="gallery-image-resize" src="img/vigenere-square.jpg"/></a>
    <div class="sub">Matematica:</br>Calcolo Combinatorio</div>
  </div>
</div>
</br></br>
<?php print_footer(); ?>
</body>
</html>
