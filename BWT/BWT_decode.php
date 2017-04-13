<?php
	$L = file_get_contents('output.txt');
	$arr = str_split($L);
	$A = array_unique($arr);
	sort($A);
	$D = [];
	$P = [];
	$T = [];
	$sum = 0;
	$key = [];
	$final = [];
	$buf = 0;
	echo count($A);
	for($i = 0; $i<strlen($L); $i++){
		$D[$i] = $sum;
		$sum+= substr_count($L, $A[$i]);
	}
	print_r($D);
	
	for($j = 0; $j<strlen($L); $j++){
		$P[] = substr_count($str, $L[$j]);
		$str .= $L[$j];
		
		$key[] = array_search($L[$j], $A);
		$T[] = $D[$key[$j]]+1+$P[$j];
	}
	foreach($T as $ti){
		$final[]=$L[$buf];
		$buf = $T[$buf]-1;
		//print_r($final);
	}
	$final = array_reverse($final);
	$final = implode($final);
	//echo '<br>'.$final;
	file_put_contents('final.txt', $final);
	