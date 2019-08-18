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
        }elseif(!empty($where) || $where != null){
            $query = DbUtils::generateSelect($query, $select);
            $query = DbUtils::generateWhere($query, $where);

            if(!empty($orderBy) || $orderBy != null){
                $query = DbUtils::generateOrderSort($query, $orderBy);
            }
           
        }elseif(!empty($select) || $select != null){
            $query = DbUtils::generateSelect($query, $select);
        }
        $query = $query->get();
        return $query;
    }

    public static function generateSelect($query = null ,$select = null)
    {
        if(empty($select) || $select == null)
        {
            $selectResult = array('id','name','created_at');
        }elseif($select == "all" ){
            $selectResult = array("*");
        }else{
            $selectResult = explode( ",", $select );
        }

        if(!empty($query) && $query != null){
            $query->select($selectResult);
            return $query;
        }else{
            $query = $selectResult;
            return $query;
        }
        
    }

    public static function generateWhere($query = null ,$where){
        $whereResult = explode( ",", $where );
        if(!empty($query) && $query != null){
            $whereArray = DbUtils::generateKeyValueWithOperators($whereResult);
            foreach($whereArray as $whereObject)
            {
                $query->where($whereObject['key'], $whereObject['operator'] , $whereObject['value']);
            }
        }else{
            $array = array();
            foreach($whereResult as $value){
                $query = explode( "=", $value );
                array_push($array,$query[0]);
            }
            $query = $array;
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

    public static function generateOrderSort($query = null, $orderBy)
    {
        if($orderBy[0] == '-'){
            $record = explode('-',$orderBy);
            if(!empty($query) && $query != null){
                $query = $query->orderBy($record[1], 'desc');
            }else{
                $query = $record[1];
            }
        }elseif($orderBy[0] == ' '){
            $record = explode(' ',$orderBy);
            if(!empty($query) && $query != null){
                $query = $query->orderBy($record[1], 'asc');
            }else{
                $query = $record[1];
            }
        }else{
            if(!empty($query) && $query != null){
                $query = $query->orderBy($orderBy);
            }else{
                $query = $record[1];
            }
            
        }
        return $query;
    }

    public static function generateInsert($objName, $data){
        $query = DB::table($objName);
        $query = $query->insert($data);
        return $query;
    }

    public static function generateUpdateRecord($objName, $where, $data){
        $query = DB::table($objName);
       /*  DB::table('Home_Content')->where('id',1)->update(
            [$_POST['name'] => $_POST['content']],
            [$_POST['title'] => $_POST['titleMsg']]
            ); */
        return $query;
    }
}