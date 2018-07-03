 <?php
	session_start();
	require "db.php";
	include "set.php";
	
	if(!isset($_SESSION["USER_ID"]))
		error_page("Devi loggarti per modificare le pagine!");
	if($_SESSION["USER_LVL"] < 50)
		error_page("Non ti è permesso modificare le pagine!");
	
	if(isset($_GET["i"]))
		$mat=$_GET["i"];
	else if(isset($_POST["i"]))
		$mat=$_POST["i"];
	else
		error_page("Variabile i assente!");

	if(!is_numeric($mat))
		error_page("Il parametro 'i' è invalido!");
		
	if(isset($_POST))
	{
		if(isset($_POST["ord-add"]) && !empty($_FILES["file-upload-add"]["tmp_name"]))
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
				$query = $conn->query("INSERT INTO immagini (link, ordine, fk_materie) VALUES ('img/". basename( $_FILES["file-upload-add"]["name"]). "',".$_POST["ord-add"].",".$mat.")");
				
				if($query != TRUE)
					error_page("Errore nella query del database! ".$conn->error);
					
			} 
			else 
			{
				error_page("Errore nello spostamento del file!");
			}
		}
		for($i = 0; $i < count($_POST["data"]); $i++)
		{
			$query = $conn->query("UPDATE immagini SET ordine = ".$_POST["data"][$i]["ord"]." WHERE ID = ".$_POST["data"][$i]["ID"]);
			if(!empty($_POST["data"][$i]["cancella"]))
			{
				$result = $conn->query("SELECT link FROM immagini WHERE ID = ".$_POST["data"][$i]["ID"]);
				$r = $result->fetch_assoc();
				unlink ($r["link"]);
				$query = $conn->query("DELETE FROM immagini WHERE ID = ".$_POST["data"][$i]["ID"]);
			}
			if($query != TRUE)
				error_page("Errore nella query del database! ".$conn->error);
		}
	}
            
	if(isset($_POST["nome"]) && isset($_POST["testo"]))
	{
		$sql = "UPDATE materie SET nome='".$_POST["nome"]."', testo='".$_POST["testo"]."' WHERE ID=".$mat;
		if($conn->query($sql) != TRUE)
		{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$query = $conn->query("SELECT nome, testo FROM materie WHERE ID=".$mat);
		$row = $query->fetch_assoc();
	}

	$query = $conn->query("SELECT nome, testo FROM materie WHERE ID=".$mat);
	$row = $query->fetch_assoc();
?>
 <html>
 <head>
 <link rel="icon" id="favicon" href="face.png" />
 <title><?php echo $row["nome"]; ?> - Edit</title>
 <link rel="stylesheet" href="styles/xsoftware.css" type="text/css">
 <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
 </head>
 <body>

<?php print_menu_param(array("index.php", "enigma.php", "https://gitlab.com/EnigmaXS/enigma-machine", "materia.php?i=".$mat, "login.php?d=1", "user.php"), array("Home", "Enigma", "Source Code", "Ritorna alla pagina", "Disconnetti", "Profilo"));?>
</div>
 <form action="edit.php" method="POST" enctype="multipart/form-data">
 <input type="text" name="nome" value="<?php echo $row["nome"]; ?>">
 <textarea class="edit" name="testo"><?php echo $row["testo"]; ?></textarea>
 
 <input type="hidden" name="i" value="<?php echo $mat; ?>">
 <div class="gallery cf">
 <h2>Carica Immagine:</h2>
 <?php
	$query = $conn->query("SELECT ID, link, ordine FROM immagini WHERE fk_materie=".$mat." ORDER BY ordine");
	for($i = 0; $i < $query->num_rows; $i++)
	{
		$data = $query->fetch_assoc();
		echo "<div class='gallery-element'>
		<img class='gallery-image' src='".$data["link"]."'></img>
		<div class='row'>
			<div style='width: 30%' class='half'>
				<input type='number' min='0' max='100' name='data[".$i."][ord]' value='".$data["ordine"]."'/>
			</div>
			<div style='width: 30%' class='half'>
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
	<input type='number' name='ord-add' value='0'/>
 <input style="display:none;" id='file-upload-add' name="file-upload-add" type='file'/>
 </div>
 </div>
 
 <button class="button-std">Modifica</button>
 </form>
 
 <?php print_footer(); ?>
 </body>
 </html>
