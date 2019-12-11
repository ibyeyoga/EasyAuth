<?php

namespace IBye\EasyAuth;

use IBye\EasyAuth\Traits\BaseTrait;
use IBye\EasyAuth\Utils\Base64Helper;

class Header
{
    use BaseTrait;

    private $alg = 'HS256';
    private $typ = 'JWT';

    public function __toString()
    {
        return Base64Helper::encode(json_encode($this->toArray()));
    }

    /**
     * @return string
     */
    public function getAlg(): string
    {
        return $this->alg;
    }

    /**
     * @param string $alg
     */
    public function setAlg(string $alg): void
    {
        $this->alg = $alg;
    }

    /**
     * @return string
     */
    public function getTyp(): string
    {
        return $this->typ;
    }

    /**
     * @param string $typ
     */
    public function setTyp(string $typ): void
    {
        $this->typ = $typ;
    }
}