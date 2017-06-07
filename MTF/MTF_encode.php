<?php
	$file = file_get_contents("input.txt");
	$fil = str_split($file);
	for($i = 0; $i < 255; $i++){
		$ascii .= chr($i);
		$asciiF .= chr($i);
	}
	echo $ascii."</br>";
	foreach($fil as $f){
		$number = strpos($ascii, $f);
		$final .= $asciiF[$number];
		$ascii = $f . str_replace($f, "", $ascii);
	}
	file_put_contents('output.txt', $final);
?>