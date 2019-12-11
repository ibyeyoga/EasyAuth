<?php

namespace IBye\EasyAuth\Utils;

class Base64Helper
{
    public static function encode($string = ''): String
    {
        return str_replace('=', '', strtr(base64_encode($string), '+/', '-_'));
    }

    public static function decode($string = ''): String
    {
        $remainder = strlen($string) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $string .= str_repeat('=', $addlen);
        }

        return base64_decode(strtr($string, '-_', '+/'));
    }
}