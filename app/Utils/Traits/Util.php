<?php


namespace App\Utils\Traits;


trait Util
{
    public function filterNullVal($orgVal, $newVal) {
        if(!empty($newVal))
            return $newVal;
        return $orgVal;
    }

}
