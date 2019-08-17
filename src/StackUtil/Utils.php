<?php

namespace StackUtil\Utils;


class Utility
{    
	const idLength = 20;
	const keySplitlength = 4;
	const keyLength = 12;
	const idStr = "1234567890abcdefghijklmnopqrstuvwxyz";
    const keyStr = "1234567890123456789012345678901234567890";
    const splitChar = '-';

	public static function generateId($firstChar, $shortName){
		$id = substr(str_shuffle(self::idStr),0,self::idLength);
		$id = $firstChar.$shortName.$id;
		return $id;
   }

	public static function generateKey($shortName){
		$key = substr(str_shuffle(self::keyStr),0,self::keyLength);
		$generatedKey = strtoUpper($shortName);
		for($i = 0;$i < self::keyLength; $i = $i + self::keySplitlength){
			$keyString = substr($key, $i, self::keySplitlength);
			$generatedKey = $generatedKey.self::splitChar.$keyString;
		}
		return $generatedKey;
	}

	public static function objArraySearch($array, $index, $value)
    {
		foreach($array as $arrayInf) {
            if($arrayInf[$index] == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

}

