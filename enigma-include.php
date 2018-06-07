<?php

$GLOBALS["ref"]=array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25);
$GLOBALS["rot"]=array(array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25),
			array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25),
			array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25));
$GLOBALS["pos"]=array(0,0,0);
$GLOBALS["fine"]=false;

function genRotore()
{
	$i=0;
	for($k = 0; $k < 3; $k++)
	{
		while($i < 26)
		{
			$buffer=mt_rand(0,25);
			if(!in_array($buffer,$GLOBALS["rot"][$k]))
			{
				$GLOBALS["rot"][$k][$i++] = $buffer;
			}
		}
	}
}

function cifra($c)
{
    if($c == ' ')
        return $c;
    if (!ctype_alpha($c))
        return -1;

    $c = $c - 65;

    /* Primo giro dei rotori 0-3 */
    for($i = 0; $i < 3; $i++)
    {
		$c = $c + $GLOBALS["pos"][$i];
		if ($c>25)
			$c = $c - 26;
		
		$c = $GLOBALS["rot"][$i][$c];
		
    }

    /* Processo del Reflettore */
    $c=$GLOBALS["ref"][$c];

    /* Secondo giro dei rotori 3-0 */
    for ($i = 2; $i >= 0; $i--)
    {   
		$c = $c + $GLOBALS["pos"][$i];
		if ($c>25)
			$c = $c - 26;
		
        for($j=0; $j < 26; $j++)
			if(($GLOBALS["rot"][$i]) == $c)
				$c=$j;
    }

        /* Avanzare il primo rotore */
        $GLOBALS["pos"][0]++;

        /* Controlla se deve avanzare il secondo e il terzo rotore */
        if ($GLOBALS["fine"])
        {
            /* Controlla se deve avanzare il secondo rotore */
            $GLOBALS["pos"][1]++;
            $GLOBALS["pos"][2]++;
            /* $GLOBALS["fine"]=true se anche il terzo rotore verrà avanzato */
            $GLOBALS["fine"]=false;
        }


        if ($GLOBALS["pos"][0] > 25)
        {
            $GLOBALS["pos"][1]++;
            if ($GLOBALS["pos"][1] > 25)
                $GLOBALS["fine"]=true;
        }
    return $c;
}

function cifra_stringa($testo)
{
	$testo=strtoupper($testo);
	$len=strlen($testo);
	for($i = 0; $i < $len; $i++) {
		$buffer = cifra(ord($testo[$i]));
		if($buffer >= 0 && $buffer <= 25)
			$offset[$i] = chr($buffer + 65);
    }
    
    if(isset($offset))
		return implode($offset);

}

function posizione_rotori($r1, $r2, $r3)
{
	$GLOBALS["pos"][0]=$r1;
	$GLOBALS["pos"][1]=$r2;
	$GLOBALS["pos"][2]=$r3;
}

function mostra_rotore($num)
{
	for($i = 0; $i < 26; $i++)
	{
		echo chr($GLOBALS["rot"][$num][$i] + 65);
	}
}
?>
