<?php

namespace IBye\EasyAuth;


use IBye\EasyAuth\Business\BusinessModelInterface;
use IBye\EasyAuth\Traits\BaseTrait;
use IBye\EasyAuth\Utils\Base64Helper;
use ReflectionClass;

class Payload
{
    use BaseTrait;

    public $businessInfo = null;
    public $iss = '';
    public $exp = -1;
    public $iat = -1;
    public $extra = [];
    public $nbf = -1;

    public function __toString()
    {
        return Base64Helper::encode(json_encode($this->toArray()));
    }

    /**
     * @param array $attributes
     * @return Payload
     * @throws \ReflectionException
     */
    public static function makePayload($attributes = [])
    {
        $classReflection = new ReflectionClass(Payload::class);
        $payload = new Payload();
        foreach ($classReflection->getProperties() as $property){
            $key = $property->getName();
            if(isset($attributes[$key])){
                $payload->$key = $attributes[$key];
            }
        }

        return $payload;
    }

    public function fillBusinessModel(BusinessModelInterface $businessModel): BusinessModelInterface
    {
        return $businessModel->fillShowableAttribute($this->businessInfo);
    }
}