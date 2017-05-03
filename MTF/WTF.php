<?php
	$file = file_get_contents("input.txt");
	$file = str_split($file);
	$A = array_unique($file);
	sort($A);
	$A = implode($A);
	echo $A.'</br>';
	//print_r($file);
	foreach($file as $f){
		$flag = strpos($A, $f);
		$Buf_one = substr($A, 0, $flag);
		$Buf_two = substr($A, $flag+1);
		echo $f.$Buf_one.$Buf_two.'</br>';
		//$A = $f . substr($A, 0, -1);
		//echo strpos($A, 0, $flag).'</br>';
	}
	echo $final;
?>