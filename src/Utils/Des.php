<?php

namespace IBye\EasyAuth\Utils;
use IBye\EasyAuth\Des\DesInterface;

class Des implements DesInterface
{

    /**
     * @param String $data
     * @param String $secret
     * @return string
     */
    public function encrypt(String $data, String $secret): string
    {
        return hash_hmac('sha256', $data, $secret);
    }
}