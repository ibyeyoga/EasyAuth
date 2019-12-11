<?php

namespace IBye\EasyAuth\Des;

interface DesInterface
{
    public function encrypt(String $data, String $secret): string;
}