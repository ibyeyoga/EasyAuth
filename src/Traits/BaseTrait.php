<?php

namespace IBye\EasyAuth\Traits;


trait BaseTrait
{
    protected function toArray(){
        $attributeArray = [];
        foreach ($this as $attributeKey => $attributeValue){
            $attributeArray[$attributeKey] = $attributeValue;
        }
        return $attributeArray;
    }
}