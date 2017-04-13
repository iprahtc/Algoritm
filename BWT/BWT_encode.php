<?php
	$file = file_get_contents('input.txt');
	$a=[0=>$file];
	$final_str;
	for($i=0; $i<strlen($file)-1; $i++){
		$a[]= substr($a[$i], 1) . $file[$i];
	}
	sort($a);
	foreach($a as $b){
		$final_str.= $b[strlen($file)-1];
	}
	file_put_contents('output.txt', $final_str);