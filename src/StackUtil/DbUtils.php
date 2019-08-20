<?php

namespace StackUtil\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DbUtils
{
    public static function generateQuery($objName,$idOrKey=null, $select = null, $where = null, $orderBy = null)
    {
        $query = DB::table($objName);
        if(!empty($idOrKey) || $idOrKey != null){
            $query = DbUtils::generateSelect($query,"all");
            $result = $query->where(['id'=> $idOrKey])->orwhere(['key'=>$idOrKey])->first();
            if (!empty($result)){
                return $result;
            }else{
                throw (new ModelNotFoundException)->setModel($objName, $idOrKey);
            }
        }else{
            $query = DbUtils::generateSelect($query, $select);
            if(!empty($where) || $where != null){
                $query = DbUtils::generateWhere($query, $where);
            }
            if(!empty($orderBy) || $orderBy != null){
                $query = DbUtils::generateOrderSort($query, $orderBy);
            }else{
                $query = DbUtils::generateOrderSort($query, '-created_at');
            }
        }
        $query = $query->get();
        return $query;
    }

    public static function generateSelect($query,$select = null)
    {
        if(empty($select) || $select == null)
        {
            $selectResult = array('id','name','created_at');
        }elseif($select == "all" ){
            $selectResult = array("*");
        }else{
            $selectResult = explode( ",", $select );
        }
        $query->select($selectResult);
        return $query;
    }

    public static function generateWhere($query,$where){
        $whereResult = explode( ",", $where );
        $whereArray = DbUtils::generateKeyValueWithOperators($whereResult);
        foreach($whereArray as $whereObject)
        {
            if(strpos($whereObject['value'],':') == true)
            {
                $whereOrResult['value'] = explode( ":", $whereObject['value'] );
                foreach($whereOrResult['value'] as $whereOrArray['value']){
                    $query->orWhere($whereObject['key'], $whereObject['operator'] , $whereOrArray['value']);
                }
            }else{
                $query->where($whereObject['key'], $whereObject['operator'] , $whereObject['value']);
            }
        }
        return $query;
    }

    public static function generateKeyValueWithOperators($whereResult)
    {
        $whereArray = [];
        $index = 0;
        foreach($whereResult as $whereKey){

            if(strpos($whereKey,'!=') == true)
            {
                $record = explode('!=',$whereKey);
                $whereArray[$index]['key']  = $record[0] ;
                $whereArray[$index]['operator']  = '!=' ;
                $whereArray[$index]['value']   = $record[1];
            }
            elseif(strpos($whereKey,'<=') == true)
            {
                $record = explode('<=',$whereKey);
                $whereArray[$index]['key']  = $record[0] ;
                $whereArray[$index]['operator']  = '<=' ;
                $whereArray[$index]['value']   = $record[1];
            }
            elseif(strpos($whereKey,'>=') == true)
            {
                $record = explode('>=',$whereKey);
                $whereArray[$index]['key']  = $record[0] ;
                $whereArray[$index]['operator']  = '>=' ;
                $whereArray[$index]['value']   = $record[1];
            }
            elseif (strpos($whereKey, '=') == true) {
                $record = explode('=',$whereKey);
                $whereArray[$index]['key']  = $record[0] ;
                $whereArray[$index]['operator']  = '=' ;
                $whereArray[$index]['value']   = $record[1];
            }elseif(strpos($whereKey, '<') == true){
                $record = explode('<',$whereKey);
                $whereArray[$index]['key']  = $record[0] ;
                $whereArray[$index]['operator']  = '<' ;
                $whereArray[$index]['value']   = $record[1];
            }elseif(strpos($whereKey, '>') == true){
                $record = explode('>',$whereKey);
                $whereArray[$index]['key']  = $record[0];
                $whereArray[$index]['operator']  = '>' ;
                $whereArray[$index]['value']   = $record[1];
            }
            if(sizeof($whereArray) != 0){
                $index++;
            }
        }
        return $whereArray;
    }

    public static function generateOrderSort($query, $orderByArray)
    {
        $orderByResult = explode( ",", $orderByArray );
        foreach($orderByResult as $orderBy)
        {
            if($orderBy[0] == '-'){
                $record = explode('-',$orderBy);
                $query = $query->orderBy($record[1], 'desc');
            }elseif($orderBy[0] == ' '){
                $record = explode(' ',$orderBy);
                $query = $query->orderBy($record[1]);
            }else{
                $query = $query->orderBy($orderBy);
            }
        }
        return $query;
    }

    public static function generateInsert($objName, $data){
        $query = DB::table($objName);
        $query = $query->insert($data);
        return $query;
    }

    public static function generateUpdateRecord($request, $objName, $idOrKey)
    {
        $data = $request->all();
        unset($data['id']);
        unset($data['key']);
        $query = DB::table($objName);
        $query = $query->where(['id'=> $idOrKey])->orwhere(['key'=>$idOrKey]);
        $query = $query->update($data);
        return $query;
    }

}
