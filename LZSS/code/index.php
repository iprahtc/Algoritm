<?php	
	$text=file_get_contents('rustup-init.exe');
	
	//Словарь
	$Buff="";
	
	//Буфеер для проверки повторов
	$Buff_text="";
	
	//Готовая строка для записи в файл
	$Buff_encode = "";
	
	//Позия вхождения
	$pozit=0;

	//Количество символов для повторения
	$step=0;
	
	//указатель
	$a=0;
	//echo strlen("01111101101111101110000001001111010001111010010010001000");
	for($i = &$a; $i<strlen($text); $i++){
		google($text[$i]);
	} 	
	
	 
	//echo $Buff_encode."<br>";
	//echo $Buff."<br>";
	
	//Дописываем недостающие нули
	while(strlen($Buff_encode)%8!=0)
	{
		$Buff_encode.="0";
	} 
	//Преобразуем с байт код в символьное представление
	for($j = 0 ; $j < strlen($Buff_encode); $j++){
		$simvol.= $Buff_encode[$j];
		if(strlen($simvol)%8 == 0)
		{
			$final_str.= ofBin($simvol);
			$simvol="";
		}
	}
	
	//Записываем в файл
	$fp = fopen('LZSS.txt', 'w');
	fwrite($fp, $final_str);
	//fwrite($fp, ofBin("11010001"));
	//echo onBin('ы');
	//echo $Buff_encode;
	//echo "</br>".$Buff_encode;
	//file_put_contents('LZSS.txt',$final_str);
	
	/* С буквы в двоичный код*/
	function onBin($str){
		//echo $str;
		$ascii = decbin(ord($str));
		//echo strlen($ascii);
		while(strlen($ascii)!=8)
		{
			$ascii = "0".$ascii;
		} 
		//echo $ascii;
		return $ascii;
		
	}
	
	/* С двоичного кода в символ*/
	function ofBin($str){
		
		$ascii=chr(bindec($str));
		return $ascii;
		
	}
	
	/* Смещение буфура на символ*/
	function oneStep($str_buf){
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
	//0 01100001 0 01100001 1 00000010 1 00000010 = aaaaaa
	//0 01100001 0 01100001 1 00000010 1 00000010
	//0 11111011 0 11111011 1 00000010 1 00000010 = ыыыыыы
	//0 11001111 0 00100000 0 11110100 0 11110100 0 11110100 1 00001011
	//0 11111011 0 11111011 1 00000010 0 11110100 0 11110100 1 00100010
	//0 01100001 0 01100001 1 00000010 1 00000100 1 00000111 1 00000111 1 00000111 1 00000111 1 00000100 1 00000111 1 00000111 1 00000100 
	//0 00110001 0 00110010 0 00110011 0 00110100 0 00110101 0 00110110 0 00110111 0 00111000 0 00111001 0 00110000 1 000000111 1 000111111 1 000100111 100000111110010001111000101111100001011110010011111000100100
	
	
	/* Поиск первого вхождения стр2 в стр1*/
	function google($str1){
		global $Buff_text;
		global $Buff;
		global $pozit;
		global $step;
		global $a;
		global $text;
		$Buff_text.= $str1;
		if(strlen($Buff_text)<7)
		{
			//echo "(buf=".$Buff.")".$Buff_text.$text[$a+1]." <br>";
			//echo "!!!!!!!!!!".strpos($Buff, $Buff_text.$text[$a+1])."!!!!!!!!!!!";
			if(strpos($Buff, $Buff_text.$text[$a+1])===false)
			{
				if($step == 0)
				{
					//echo $step." if_2 <br>";
					noPovtor($str1);
					oneStep($str1);
					$Buff_text="";
					$pozit = 0;
				}
				else
				{
					//echo ($step+1)." if_3 <br>";
					$step++;
					Povtor($pozit, $step);
					oneStep($Buff_text, $step);
					$step;
					$step = 0;
					$pozit = 0;
					$Buff_text = "";
				}
			}
			else
			{
				//if($step==0){
					$pozit=strpos($Buff,$Buff_text.$text[$a+1]);
					//if($Buff_text == )
					//echo $pozit." if_4 <br>";
					//echo "!".$Buff." <br>";
					//echo $Buff_text.$text[$a+1]." <br>";
					//return either FALSE (substring a 
				//}
				$step++;
				if(1==strlen($Buff_text) && strlen($text)==$a+1 || $step==64){
					//echo $step."if_5 <br>";
					oneStep($str1);
					noPovtor($str1);
					$step = 0;
					$pozit = 0;
					$Buff_text = "";
				}
				if(1 < strlen($Buff_text) && strlen($text)==$a+1){
					//echo $step." if_6 <br>";
					//echo "!!".$pozit."!!";
					Povtor($pozit, $step);
					oneStep($Buff_text, $step);
					$step = 0;
					$pozit = 0;
					$Buff_text = "";
				}
			}
		}
		else{
			//echo $step." if_7 <br>";
			$step++;
			Povtor($pozit, $step);
			oneStep($Buff_text, $step);
			//echo $step;
			$step = 0;
			$pozit = 0;
			$Buff_text = "";
		}
	}
	
	/* когда нет повторений пишем 0*/
	function noPovtor($str1){
		global $Buff_encode;
		$Buff_encode.= "0".onBin($str1);
	}
	
	/*  когда есть повтор*/
	function Povtor($poz, $ste){
		global $Buff_encode;
		$Buff_encode.= "1".bit_6($poz).bit_3($ste);	
	}
	
	// функция для добавления 6 бит указателя на начало совпадения
	function bit_6($b){
		$b = decbin($b);
		while(strlen($b)!=6)
		{
				$b = "0".$b;
		}
		//echo $b."</br>";
		return $b;
	}
	
	// функция для добавления 3 бит количество повторений
	function bit_3($b){
		$b = decbin($b);
		while(strlen($b)!=3)
			$b = "0".$b;
		return $b;
	} 