<?php
	for($i = 0 ; $i < filesize('input.txt'); $i++){
		$final_str = "";
		$file = file_get_contents('input.txt', NULL, NULL, $i, 100);
		$file .= "ÿ";
		$arr = str_split($file);
		$a[0] = $file;
		for($j=0; $j<strlen($a[0])-1; $j++)
		{
			$a[]= substr($a[$j], 1) . $arr[$j];
		}
		sort($a);
		//print_r($a);
		foreach($a as $b)
		{
			$final_str.= $b[strlen($file)-1];
		}
		$i+=99;
		unset($a);
		file_put_contents('output.txt', $final_str, FILE_APPEND);
	}