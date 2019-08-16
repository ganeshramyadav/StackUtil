<?php

namespace StackUtil\Utils;


class Utility
{    
	const idLength = 24;
	const keySplitlength = 4;
	const keyLength =16;
	const idStr = "1234567890abcdefghijklmnopqrstuvwxyz";
	const keyStr = "1234567890";

    public static function helloworld()
	{
		return 'HelloWorld';
	}
	
	public static function generateId($firstChar, $shortName){
		$id = substr(str_shuffle(self::idStr),0,self::idLength);
		$id = $firstChar.$shortName.$id;
		return $id;
   }

	public static function generateKey($shortName){
		return $shortName;
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

