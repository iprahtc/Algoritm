<?php
	//Converting a String from a File to an Array
	$file = file_get_contents("output.txt");
	$fil = str_split($file);
	
	//Getting an ascii table in a string
	for($i = 0; $i < 255; $i++){
		$asciiF .= chr($i);
	}
	
	//Position coding
	foreach($fil as $f){
		$final .= $asciiF[ord($f)];
		$asciiF = $asciiF[ord($f)] . str_replace($asciiF[ord($f)], "", $asciiF);
	}
	
	//file_put_contents('final.txt', $final);
	file_put_contents('jquery-3.2.2.slim.js', $final);
?>