<?php
namespace IBye\EasyAuth\Business;

interface BusinessModelInterface
{
    /**
     * need to return attribute names that you want.
     * @return array
     */
    public function showableAttributeNames() :  array;

    /**
     * fill the model that use for business.
     * @param array $businessInfo
     * @return mixed
     */
    public function fillShowableAttribute($businessInfo = []) : ?BusinessModelInterface;
}