  <?php
	session_start();
	require "db.php";
	include "set.php";
	
	if(!isset($_SESSION["USER_ID"]))
		error_page("Devi loggarti per modificare le pagine!");
	if($_SESSION["USER_LVL"] < 100)
		error_page("Non ti è permesso modificare le pagine!");
		
	if(isset($_POST["data"]))
	{
		if(isset($_POST["data"]["add"]["nome"]) && !empty($_FILES["file-upload-add"]["tmp_name"]))
		{
		
			$target_dir = "img/";
			$target_file = $target_dir . basename($_FILES["file-upload-add"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["file-upload-add"]["tmp_name"]);
			if($check !== false) {
				$uploadOk = 1;
			} else
				error_page("Il file non è un immagine!");

			if (file_exists($target_file))
				error_page("L'immagine esiste già!");
				
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
				error_page("Solo i file JPG, JPEG, PNG & GIF sono consentiti!");
			
			if (move_uploaded_file($_FILES["file-upload-add"]["tmp_name"], $target_file)) 
			{
				$query = $conn->prepare("INSERT INTO materie (nome, argomento, immagine) VALUES (?, ?, ?)");
				$file = "img/". basename( $_FILES["file-upload-add"]["name"]);
				$query->bind_param("sss", $_POST["data"]["add"]["nome"], $_POST["data"]["add"]["argomento"], $file);
				$query->execute();
				
				if($query != TRUE)
					error_page("Errore nella query del database! ".$conn->error);
					
			} 
			else 
			{
				error_page("Errore nello spostamento del file!");
			}
		}
		for($i = 0; $i < count($_POST["data"]) -1; $i++)
		{
			$query = $conn->prepare("UPDATE materie SET nome=?, argomento=? WHERE ID=?");
			$query->bind_param("ssi", $_POST["data"][$i]["nome"], $_POST["data"][$i]["argomento"], $_POST["data"][$i]["ID"]);
			$query->execute();
			if(!empty($_POST["data"][$i]["cancella"]))
			{
				$result = $conn->query("SELECT immagine FROM materie WHERE ID = ".$_POST["data"][$i]["ID"]);
				$r = $result->fetch_assoc();
				unlink ($r["immagine"]);
				$query = $conn->query("DELETE FROM materie WHERE ID = ".$_POST["data"][$i]["ID"]);
			}
			if($query != TRUE)
				error_page("Errore nella query del database! ".$conn->error);
		}
	}
?>
 <html>
 <head>
 <link rel="icon" id="favicon" href="face.png" />
 <title>Index - Edit</title>
 <link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
 <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
 </head>
 <body>

<?php print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "index.php", "login.php?d=1", "user.php"), array("Home", "Enigma", "Source Code", "Ritorna alla pagina", "Disconnetti", "Profilo"));?>
</div>
<form action="edit-index.php" method="POST" enctype="multipart/form-data">
 <div class="gallery cf">
 <h2>Materie:</h2>
 <?php
	$query = $conn->query("SELECT ID, nome, argomento, immagine FROM materie");
	for($i = 0; $i < $query->num_rows; $i++)
	{
		$data = $query->fetch_assoc();
		echo "<div class='gallery-element'>
		<img class='gallery-image' src='".$data["immagine"]."'></img>
		<div class='row'>
			<div style='width: 29%' class='half'>
				<input type='text' name='data[".$i."][nome]' value='".$data["nome"]."' maxlength='64'/>
			</div>
			<div style='width: 29%' class='half'>
				<input type='text' name='data[".$i."][argomento]' value='".$data["argomento"]."' maxlength='64'/>
			</div>
			<div style='width: 20%' class='half'>
				<input class='button-std' type='submit' name='data[".$i."][cancella]' value='Cancella'/>
			</div>
		</div>
			<input style='display:none;' type='number' name='data[".$i."][ID]' value='".$data["ID"]."'/>
		</div>";
	}
 ?>
 <div class='gallery-element'>
	<label for='file-upload-add' >
	<img class='gallery-image' src='img/plus.jpg'></img>
	</label>
	<div class='row'>
		<div style='width: 30%' class='half'>
			<input type='text' name='data[add][nome]' maxlength='64'/>
		</div>
		<div style='width: 30%' class='half'>
			<input type='text' name='data[add][argomento]' maxlength='64'/>
		</div>
	</div>
 <input style="display:none;" id='file-upload-add' name="file-upload-add" type='file'/>
 </div>
 </div>
 
 <button class="button-std">Modifica</button>
 </form>
 
 <?php print_footer(); ?>
 </body>
 </html>
