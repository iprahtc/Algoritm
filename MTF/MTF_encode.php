<?php
	//Converting a String from a File to an Array
	//$file = file_get_contents("input.txt");
	$file = file_get_contents("jquery-3.2.1.slim.js");
	$fil = str_split($file);
	
	//Getting an ascii table in a string
	for($i = 0; $i < 255; $i++){
		$ascii .= chr($i);
		$asciiF .= chr($i);
	}
	
	//Position coding
	foreach($fil as $f){
		$number = strpos($ascii, $f);
		$final .= $asciiF[$number];
		$ascii = $f . str_replace($f, "", $ascii);
	}
	
	file_put_contents('output.txt', $final);
?>