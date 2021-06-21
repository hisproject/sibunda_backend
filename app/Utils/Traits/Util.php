<?php


namespace App\Utils\Traits;


trait Util
{
    public function filterNullVal($orgVal, $newVal) {
        if(!empty($newVal) || strtolower($newVal) == 'null')
            return $newVal;
        return $orgVal;
    }

    public function nullableVal($val, ... $nullables) {
        if(empty($val) || strtolower($val) == 'null')
            return null;
        foreach ($nullables as $n)
            if($val == $n)
                return null;
        return $val;
    }
}
