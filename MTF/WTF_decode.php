<?php
	$file = file_get_contents("output.txt");
	$len_alphabet = (int)$file;
	$alphabet = substr($file, strlen($len_alphabet), $len_alphabet);
	for($i = (strlen($len_alphabet)+strlen($alphabet)); $i<strlen($file); $i++){
		$final .= chr(ord($file[$i]));
		//echo $final." ";
	}
	echo $final;
	file_put_contents('final.txt', $final);
	
	
	
	/* $file = str_split($file);
	$A = array_unique($file);
	sort($A);
	$A = implode($A);
	$final .= strlen($A);
	$final .= $A;
	foreach($file as $f){
		$number .= strpos($A, $f);
		$A = $f . str_replace($f, "", $A);
	}
	$number = decbin($number);
	$ost = strlen($number)%8;
	for($i = 0; $i < $ost; $i++)
		$number.="0";
	for($i = 1 ; $i <= strlen($number); $i++){
		$buf .= $number[$i-1];
		if((strlen($buf)%8) == 0){
			$final .= chr(bindec($buf));
			$buf = "";
		}
	} */
	file_put_contents('final.txt', $final);
?>