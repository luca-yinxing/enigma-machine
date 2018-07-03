<?php

$GLOBALS["ref"] = "YRUHQSLDPXNGOKMIEBFZCWVJAT";

function creaSessione()
{
	require "db.php";
	$ID = 0;
	while(TRUE)
	{
		$ID = mt_rand();
		if($conn->query("INSERT INTO sessione (ID)  VALUES (".$ID.")") == TRUE)
			break;
	}
	
	$conn->close();
	return $ID;
}


/*
function genRotore()
{
	$i=0;
	for($k = 0; $k < 3; $k++)
	{
		while($i < 26)
		{
			$buffer=mt_rand(0,25);
			if(!in_array($buffer,$_SESSION["rot"][$k]))
			{
				$_SESSION["rot"][$k][$i++] = $buffer;
			}
		}
	}
}
*/


function ruota()
{
	/* Avanzare il primo rotore */
	$buffer = $_SESSION["rot"][0][0];
	
	for($i = 0; $i < 25; $i++)
		$_SESSION["rot"][0][$i] = $_SESSION["rot"][0][$i+1];
		
	$_SESSION["rot"][0][25] = $buffer;

	/* Controlla se deve avanzare il secondo e il terzo rotore */
	if (ord($_SESSION["rot"][0][0]) == 'Z')
	{
		$buffer = $_SESSION["rot"][1][0];
	
		for($i = 0; $i < 25; $i++)
			$_SESSION["rot"][1][$i] = $_SESSION["rot"][1][$i+1];
			
		$_SESSION["rot"][1][25] = $buffer;
	}
	
	if (ord($_SESSION["rot"][1][0]) == 'Z')
	{
		$buffer = $_SESSION["rot"][2][0];
	
		for($i = 0; $i < 25; $i++)
			$_SESSION["rot"][2][$i] = $_SESSION["rot"][2][$i+1];
			
		$_SESSION["rot"][2][25] = $buffer;
	}
}

function cifra($c)
{
    if($c == 32 || $c == 43)
        return 32;
    if (!ctype_alpha($c))
        return -1;
    $c = $c - 65;
    
    /* Primo giro dei rotori 0-3 */
    for($i = 0; $i < 3; $i++)
    {
		$c = ord($_SESSION["rot"][$i][$c]) - 65;
    }
    
    /* Processo del Reflettore */
    $c=ord($GLOBALS["ref"][$c]) - 65;
    
    /* Secondo giro dei rotori 3-0 */
    for ($i = 2; $i >= 0; $i--)
    {   
        for($j=0; $j < 26; $j++)
			if((ord($_SESSION["rot"][$i][$j]) - 65) == $c)
			{
				$c=$j;
				break;
			}
    }
    
    return $c;
}

function scambio($testo, $sca)
{
	$len = strlen($sca);
	if(($sca % 2) != 0)
		return $testo;
		
	for($i=0; $i < strlen($testo); $i++)
		for($k=0; $k < $len; $k+=2)
			if($testo[$i] == $sca[$k])
				$testo[$i] = $sca[$k+1];
				
	return $testo;
}

function cifra_stringa($rot1, $rot2, $rot3, $pos1, $pos2, $pos3, $testo)
{
	require "db.php";
	$len=strlen($testo);
	for($i = 0; $i < $len; $i++) {
		$buffer = cifra(ord($testo[$i]));
		if($buffer == 32)
			$offset[] = ' ';
		ruota();
		if($buffer >= 0 && $buffer <= 25)
			$offset[] = chr($buffer + 65);
    }
    
    $offset = implode($offset);
    if($conn->query("INSERT INTO cifra (posizione1, posizione2, posizione3, chiaro, cifrato, fk_rotori1, fk_rotori2, fk_rotori3, fk_sessione)  
    VALUES (".$pos1.",".$pos2.",".$pos3.",'".$testo."','".$offset."',".$rot1.",".$rot2.",".$rot3.",".$_SESSION["ID"].")") != TRUE)
		echo "Error: ". $conn->error;
    $conn->close();
    
	return $offset;

}

function rotori($rot1, $rot2, $rot3, $pos1, $pos2, $pos3)
{
	require "db.php";
	$v[0] = $rot1; $p[0] = $pos1;
	$v[1] = $rot2; $p[1] = $pos2;
	$v[2] = $rot3; $p[2] = $pos3;

	for($i = 0; $i < 3; $i++)
	{
		$query = $conn->query("SELECT valore FROM rotori WHERE ID = ".$v[$i]);
		$row = $query->fetch_assoc();
		
		$a = substr($row["valore"], $p[$i]);
		$b = substr($row["valore"], 0, $p[$i]);
		
		$offset[] = $a . $b;
	}
	$conn->close();
	return $offset;
}

?>
