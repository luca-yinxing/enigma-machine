<html>
<head>
<link rel="icon" id="favicon" href="face.png" />
<title>Errore</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<meta charset="UTF-8">
<link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
</head>
<body>
<?php 
session_start();
include "set.php";

print_menu();


echo "</br></br><h1>Errore:".$_SESSION["ERROR_MESSAGE"]."</h1>";

print_footer();
?>
</body>
</html>
