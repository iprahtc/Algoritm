<?php
	$text = file_get_contents("code/LZSS.txt");
	//$text = file_get_contents("1.txt");
	$Buff="";
	$Buff_text="";
	$Final_text="";
	$text_text = "";
	
	//0 01110011 0 01100110 0 01100110 1 00001010 0 00100000 0 01110011 0 01110011 0 00100000 1 00011010 0 00111011 0 00100000 0 01100111 0 01100111 1 01011011 
	//0 01110011 0 01100110 0 01100110 1 00001010 0 00100000 0 01110011 0 01110011 0 00100000 1 00001010 0 00111011 0 00100000 0 01100111 0 01100111 1 00101011
	//0 00110001 0 00110010 0 00110011 0 00110100 0 00110101 0 00110110 0 00110111 0 00111000 0 00111001 0 00110000 1 00000111 1 00111111 1 00100111 1 00001111 1 00010010
	//0 11110100 0 11110100 1 000000010 0 00100000 1 000000011 0 11111011 0 11111011 1 000100011
	//0 01110011 0 01110011 1 000000010 0 00100000 1 000010011 0 01100110 0 01100110 1 000100011
	
	decode(text_bin($text));
	//echo text_bin($text);
	file_put_contents("rustup-init.exe", $Final_text);
	//перевод строки в байт представление
	function text_bin($text){
		$text_text="";
		for($i = 0; $i < strlen($text); $i++){
			$text_text .= onBin($text[$i]);
		}
		//echo $text_text;
		/* if(($le = strlen($text_text)%9) > 0)
			$text_text = substr($text_text, 0 , -$le); */
		return $text_text;
	}
	
	//Основная функция декодирования
	function decode($str_bin){
		global $Final_text;
		global $Buff_text;
		global $Buff;
		for($i=0 ; $i<strlen($str_bin); $i++)
		{
			if($i==0){
				for($j=1; $j<=8; $j++){
						$Buff_text.=$str_bin[$i+$j];
					}
					//echo $Buff_text." if_1 <br>";
					$i+=8;
					//echo $Buff_text."<br>";
					Buff_f(ofBin($Buff_text));
					$Final_text .= ofBin($Buff_text);
					//echo $Final_text."</br>";
					$Buff_text = "";
			}
			else{
				if($str_bin[$i]=="1")
				{
					for($j=1; $j<=9; $j++){
						$Buff_text.=$str_bin[$i+$j];
					}
					//echo $Buff_text." if_2 <br>";
					$i+=9;
					Repeat($Buff_text);
					$Buff_text = "";
				}
				else{
					for($j=1; $j<=8; $j++){
						$Buff_text.=$str_bin[$i+$j];
					}
					//echo $Buff_text." if_1 <br>";
					$i+=8;
					//echo $Buff_text."<br>";
					Buff_f(ofBin($Buff_text));
					$Final_text .= ofBin($Buff_text);
					//echo $Final_text."</br>";
					$Buff_text = "";
				}
			}
		}
	}
	// Вытаскивает с буфера нужные символы
	function Repeat($str_poz){
		global $Final_text;
		global $Buff;
		$Buf6="";
		$Buf3="";
		$flag="";
		for($i=0; $i<strlen($str_poz); $i++){
			if($i<6)
				$Buf6.=$str_poz[$i];
			else
				$Buf3.=$str_poz[$i];
		}
		$position = bindec($Buf6);
		//echo $position."</br>";
		$quantity = bindec($Buf3);
		//echo $quantity."</br>";
		for($j = 0; $j<$quantity; $j++)
		{
			$Final_text.=$Buff[$j+$position];
			$flag.=$Buff[$j+$position];
		}
		Buff_f($flag);
	}
	
	//Запись в буфер
	function Buff_f($str_buf){
		global $Buff;
		$step = strlen($str_buf);
		if(strlen($Buff)+$step<=64)
			$Buff.=$str_buf;
		else{
			$ost=64-strlen($Buff);
			if($step>1){
				for($p=0; $p<$ost; $p++){
						$Buff.= $str_buf[$p];
				}
				
				//echo $Buff."+$simvol<br>";
				$str_buf = substr($str_buf, $ost);
				
				//echo $Buff."+$simvol<br>";
				$Buff = substr($Buff, strlen($str_buf)).$str_buf;
			}
			if($step==1)
				$Buff = substr($Buff, strlen($step)).$str_buf;
		}
	}
	
	
	/* С буквы в двоичный код*/
	function onBin($str){
		$ascii = decbin(ord($str));
		while(strlen($ascii)!=8)
			$ascii = "0".$ascii;
		return $ascii;
	}
	
	/* С двоичного кода в символ*/
	function ofBin($str){
		
		$ascii=chr(bindec($str));
		return $ascii;
		
	}