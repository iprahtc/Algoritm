<?php
$str = 'abcccaa';
$strArr = str_split($str);
$flag = '';
$buff = [];
$poz = 0;

for($x = 0; $x < strlen($str); $x++){
    if($flag === $str[$x]){
        $poz++;
        $buff[$x-$poz] .=  $flag;
        $flag = $str[$x];
    }
    else{
        $flag = $str[$x];
        $buff[$x-$poz] .=  $flag;
    }
}
//print_r($buff);
/*for($a = 0 ; $a < 256; $a++){
    echo "{".chr($a).",".$a."}";
}*/
//echo ord(255);
file_put_contents('output.txt', chr(255));
/*$a = file_get_contents('output.txt');
file_put_contents('final.txt', $a);*/
