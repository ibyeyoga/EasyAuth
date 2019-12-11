<?php
return [
    'secret' => 'EasyAuth',
    'expire' => 86400 * 3, //three days
//    'refresh' => 86400 * 2,
    'issuer' => 'EasyAuth',
    'algs' => [
        'HS256' => [
            'class' => 'IBye\EasyAuth\Utils\Des',
            'args' => []
        ]
//        'HS256' => function($data, $secret){
//            return hash_hmac('sha256', $data, $secret);
//        }
    ]
];