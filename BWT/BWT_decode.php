<?php
	for($i = 0; $i<filesize('output.txt'); $i++){
		$sum = 0;
		$file = file_get_contents('output.txt', NULL, NULL, $i, 101);
		//echo $file."</br>";
		$i += 100;
		$L = str_split($file);
		//echo "L = ";print_r($L);echo "</br>";
		$A = array_unique($L);
		sort($A);
		//echo "A = ";print_r($A);echo "</br>";
		
		foreach($A as $a){
			$buf = substr_count($file, $a);
			$C[] = $buf;
			$D[] = $sum;
			$sum += $buf;
		}
		//echo "C = ";print_r($C);echo "</br>";
		//echo "D = ";print_r($D);echo "</br>";
		$A = array_flip($A);
		for($j = 0; $j < strlen($file); $j++){
			$P[] = substr_count($bug_str, $file[$j]);
			$bug_str .= $file[$j];
			$T[] = $D[$A[$file[$j]]] + $P[$j]+1;
		}
		
		//echo "P = ";print_r($P);echo "</br>";
		//echo "T = ";print_r($T);echo "</br>";
		$flag = stripos($file, 'ÿ');
		for($z = 0; $z < count($T); $z++){
			$flag = $T[$flag]-1;
			if($L[$flag]==='ÿ')
				continue;
			$final_str = $L[$flag] . $final_str;
		}
		file_put_contents('final.txt', $final_str, FILE_APPEND);
		//echo $final_str;
		unset($C, $D, $P, $T, $A, $sum, $bug_str, $final_str);
	}
	