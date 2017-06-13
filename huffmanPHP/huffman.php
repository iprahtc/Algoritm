<?php

class Huffman {

	
	public function __construct($dictionary = null) {
		if($dictionary)
			$this->setDictionary($dictionary);
			
	}
	
	//Корневой узел дерева
	protected $root = null;
	//Ассоциативный массив листьев в дереве (значение => узел).
	protected $leaves = array();
	
	
		/**
	* Постройте дерево Хаффмана с данным словарем.
	*
	* @param словарь.
	* @return Размер словаря.
	*/
	public function setDictionary($dictionary) {
		if(!$dictionary)
			die("No dictionary provided.");
		$this->root = new Node();
		if(is_string($dictionary))
			$dictionary = str_split($dictionary);
		$dictionary["nextIndex"] = 0;
		$this->root->setDictionary($dictionary,0);
		$size = $dictionary["nextIndex"];
		unset($dictionary["nextIndex"]);
		return $size;
	}
	
		/**
	* Возвращает строку или массив объектов, представляющих дерево.
	*
	* @param asArray (необязательно)
	* Будет возвращен как массив (по умолчанию = false). Если это
	* Значение false, возвращается строка.
	* @return Вернет словарь.
	*/
	public function getDictionary($asArray = false) {
		$dictionary = array();
		if(!$this->root)
			throw "Невозможно извлечь словарь из несуществующего дерева.";
		$this->root->getDictionary($dictionary,0);
		if(!$asArray)
			return implode($dictionary);
		else
			return $dictionary;
	}
	
	/**
	* Создает дерево в соответствии с алгоритмом Хаффмана.
	*
	* @param data Это данные, из которых построено дерево.
	* Может быть либо строкой, либо массивом объектов / значений.
	* Если объекты содержатся, их функция toString ()
	* Должен возвращать уникальную строку, используемую для выделения объектов.
	*/
	public function buildTree($data) {
		// Корневые узлы, в то время как у нас есть более одного из них.
		// Это ассоциативный массив с valueToString (значение)
		// ключ и узел как значение.
		$roots = array();

		if(is_string($data))
			$data = str_split($data);
		
		// Определение частот.
		for($index=0;$index < count($data);$index++) {
			$key = $data[$index];
		
		// Добавить значение, если новое.
			if(!isset($roots[$key])) {
				$roots[$key] = new Node($key);
				$this->leaves[$key] = $roots[$key];
			}
				
			$roots[$key]->frequency++;
		}
		
		// Нужно, по крайней мере, два разных элемента.
		if(count($roots) === 1) {
			$key = strlen($key) === 1 ? chr(255 - ord($key)) : $key."+";
			$artificial = new Node($key);
			$roots[$key] = $artificial;
			$this->leaves[$key] = $artificial;
		}		

		
		$roots = array_values($roots);
		
		// Создаем дерево.
		while(count($roots) > 1) {
			// Находим два узла с самой низкой частотой.
			if($roots[0]->frequency < $roots[1]->frequency) {
				$leastOften = 0;
				$secondLeastOften = 1;
			} else {
				$leastOften = 1;
				$secondLeastOften = 0;
			}
			for($index=2;$index < count($roots);$index++)
				if($roots[$index]->frequency < $roots[$leastOften]->frequency) {
					$secondLeastOften = $leastOften;
					$leastOften = $index;
				} else if($roots[$index]->frequency < $roots[$secondLeastOften]->frequency)
					$secondLeastOften = $index;
					
			// Слияние этих двух узлов.
			$node = new Node();
			$leastZero = true;
			if($roots[$leastOften]->height > $roots[$secondLeastOften]->height)
				$leastZero = false;
			else if($roots[$leastOften]->height == $roots[$secondLeastOften]->height
				&& $roots[$leastOften]->value > $roots[$secondLeastOften]->value)
				$leastZero = false;
			if($leastZero) {
				$node->zeroChild = $roots[$leastOften];
				$node->oneChild = $roots[$secondLeastOften];
			} else {
				$node->zeroChild = $roots[$secondLeastOften];
				$node->oneChild = $roots[$leastOften];
			}
			$node->frequency = $node->zeroChild->frequency + $node->oneChild->frequency;
			$node->height = 1 + max($node->zeroChild->height,$node->oneChild->height);
			$node->zeroChild->myParent = $node;
			$node->oneChild->myParent = $node;
			$roots[$leastOften] = $node;
			unset($roots[$secondLeastOften]);
			$roots = array_values($roots);
		}
		
		$this->root = $roots[0];
	}
	
	/**
	* Преобразует 32-разрядное целое число в 4-буквенную строку.
	*
	* @param value 32-битное целое число.
	* @return 4-буквенная строка.
	*/
	protected function intToString($value) {
		return chr(($value >> 24) & 0xFF)
					.chr(($value >> 16) & 0xFF)
					.chr(($value >> 8) & 0xFF)
					.chr($value & 0xFF);
	}
	
	/**
	* Сжатие данных с использованием текущего
	* Дерево Хаффмана.
	*
	* @param data Массив из 32-битных значений или строка, которая
	* сжата.
	* @param asArray (необязательно) Независимо от того, сжаты ли данные
	* Возвращается как массив из 32-битных значений. Если это
	* Значение false (= по умолчанию), возвращается строка.
	* @return Сжатая форма данных.
	*/
	public function compressData($data,$asArray = false) {
		$dword = 0;		// Текущее 32-битное $value.
		$bitsLeft = 32;	// Количество бит, оставшихся в $ dword.

		if(is_string($data))
			$data = str_split($data);
			
		if(!$asArray)
			$compressed = $this->intToString(count($data));
		else {
			$compressed = array();
			$compressed[] = count($data);
		}
		for($index=0;$index < count($data);$index++) {
			// Сопоставление $data с $node.
			$key = $data[$index];				
			$node = $this->leaves[$key];
			if(!$node)
				throw "Дерево Хаффмана не соответствует входным данным.";
			
			// Если этот лист еще не имеет $value делаем это
			// теперь, двигаясь к корню.
			if($node->bitLength == 0) {
				$node->bits = 0;
				$current = $node;
				while($current->myParent) {
					if($current->myParent->oneChild == $current)
						$node->bits |= (1 << $node->bitLength);
					$node->bitLength++;
					$current = $current->myParent;
				}
			}
			
			// Добавляем битыв $node в $data.
			if($bitsLeft >= $node->bitLength) {
				// Он вписывается в $dword.
				$dword = ($dword << $node->bitLength) | $node->bits;
				$bitsLeft -= $node->bitLength;
			} else {
				// Это не подходит, разбивается.
				$dword = ($dword << $bitsLeft) | ($node->bits >> ($node->bitLength - $bitsLeft));
					// Для того, чтобы это смещение битов для правильной работы, предположение
					// что в словаре осталось меньше 2 ^ 32 - 1 значений.
				$value = $dword & 0xffffffff;
				if(!$asArray)
					$compressed .= $this->intToString($value);
				else
					$compressed[] = $value;
				$dword = $node->bits;
				$bitsLeft = 32 - ($node->bitLength - $bitsLeft);
			}

		}
		$value = ($dword << $bitsLeft) & 0xffffffff;
		if(!$asArray)
			$compressed .= $this->intToString($value);
		else
			$compressed[] = $value;
		
		return $compressed;
	}
	
	public function compress($data) {
		$this->buildTree($data);
		if(is_string($data))
			return $this->getDictionary(false).$this->compressData($data,false);
		else
			return array_merge($this->getDictionary(true),$this->compressData($data,true));
	}
	
	protected function stringToInt($str) {
		return (ord(substr($str,0,1)) << 24)
				| (ord(substr($str,1,1)) << 16)
				| (ord(substr($str,2,1)) << 8)
				| ord(substr($str,3,1));
	}
	public function decompressData($compressed,$asArray = false,$startIndex = 0) {
		// Some initialization.
		$index = 0;
		$bitIndex = 32;
		$data = array();
		if(!$startIndex)
			$compressedIndex = 0;
		else
			$compressedIndex = $startIndex;
		if(is_string($compressed)) {
			$count = $this->stringToInt(substr($compressed,$compressedIndex,4));
			$compressedIndex += 4;
		} else {
			$count = $compressed[$compressedIndex];
			$compressedIndex++;
		}
		
		while($index < $count) {
			$node = $this->root;
			while($node->value === null) {
				if(is_string($compressed))
					$value = $this->stringToInt(substr($compressed,$compressedIndex,4));
				else
					$value = $compressed[$compressedIndex];
				$bit = ($value >> ($bitIndex - 1)) & 1;
				$bitIndex--;
				if($bitIndex==0) {
					if(is_string($compressed))
						$compressedIndex += 4;
					else
						$compressedIndex++;
					$bitIndex = 32;
				}

				if($bit)
					$node = $node->oneChild;
				else
					$node = $node->zeroChild;
			}
			
			$data[] = $node->value;
			$index++;
		}
		
		if(!$asArray)
			return implode($data);
		else
			return $data;
	}
	
	public function decompress($bitStream) {
		$index = $this->setDictionary($bitStream);
		$asArray = !is_string($bitStream);
		return $this->decompressData($bitStream,$asArray,$index);
	}
	
	public function __toString() {
		if(!$this->root)
			return "no tree";
		else
			return $this->root->__toString();
	}

}

class Node {

	public $value = null;
	
	public $frequency = 0;
	
	public $zeroChild = null;
	
	public $oneChild = null;

	public $myParent = null;
	
	public $height = 0;
	
	public $bits = null;
	
	public $bitLength = 0;
	
	public function __construct($value = null) {
		$this->value = $value;
	}
	
	public function setDictionary(&$dictionary,$bitLength) {
		if($dictionary[$dictionary["nextIndex"] + 1] == $bitLength + 1) {
			$this->zeroChild = new Node($dictionary[$dictionary["nextIndex"]]);
			$this->zeroChild->myParent = $this;
			$dictionary["nextIndex"] += 2;
		} else {
			$this->zeroChild = new Node();
			$this->zeroChild->myParent = $this;
			$this->zeroChild->setDictionary($dictionary,$bitLength + 1);
		}
		
		if($dictionary[$dictionary["nextIndex"] + 1] == $bitLength + 1) {
			$this->oneChild = new Node($dictionary[$dictionary["nextIndex"]]);
			$this->oneChild->myParent = $this;
			$dictionary["nextIndex"] += 2;
		} else {
			$this->oneChild = new Node();
			$this->oneChild->myParent = $this;
			$this->oneChild->setDictionary($dictionary,$bitLength + 1);
		}
	}
	
	public function getDictionary(&$dictionary,$bitLength) {
		if($this->value === null) {
			$this->zeroChild->getDictionary($dictionary,$bitLength + 1);
			$this->oneChild->getDictionary($dictionary,$bitLength + 1);
		} else {
			$dictionary[] = $this->value;
			$dictionary[] = $bitLength;
		}
	}
	
	public function __toString() {
		$str = "";
		if($this->zeroChild)
			$str .= "[" . ($this->value===null?"null":$this->value) . "," . $this->frequency . "," . $this->height . "]"
					. "(" . $this->zeroChild->__toString() . "," . $this->oneChild->__toString() . ")";
		else
			$str .= "[" . $this->value . "," . $this->frequency . "," . $this->height . "]";
		return $str;
	}

}

$original = file_get_contents('test.exe');
$huffman = new Huffman();
$compressed = $huffman->compress($original);
file_put_contents('output.txt', $compressed);

$original2 = file_get_contents('output.txt');
$huffman2 = new Huffman();
$decompressed = $huffman2->decompress($compressed);
file_put_contents('test2.exe', $decompressed);
//echo $decompressed;
?>
