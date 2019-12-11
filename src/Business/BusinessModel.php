<?php

namespace IBye\EasyAuth\Business;


class BusinessModel implements BusinessModelInterface
{
    public $uid = 1;
    public $name = 'yoga';
    public $score = 100;

    /**
     * need to return attribute names that you want.
     * @return array
     */
    public function showableAttributeNames(): array
    {
        return ['uid', 'name'];
    }

    /**
     * fill the model that use for business.
     * @param array $businessInfo
     * @return mixed
     */
    public function fillShowableAttribute($businessInfo = [])
    {
        foreach ($businessInfo as $key => $value){

        }
    }
}