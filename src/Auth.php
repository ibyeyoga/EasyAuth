<?php

namespace IBye\EasyAuth;

use IBye\EasyAuth\Business\BusinessModelInterface;
use IBye\EasyAuth\Exceptions\CreatedTokenException;
use IBye\EasyAuth\Exceptions\InvalidTokenException;
use IBye\EasyAuth\Utils\Base64Helper;

class Auth
{
    private $token = null;
    private $config = [];

    /**
     * Auth constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        $defaultConfig = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config/config.php';

        if (empty($config)) {
            $this->config = array_merge($defaultConfig, $config);
        }

        $header = $this->makeHeader();
        $this->token = new Token($header);

        if(isset($this->config['algs'])){
            $this->token->setAlgs($this->config['algs']);
        }

        if(empty($this->config['secret'])){
            throw new \Exception('need secret.');
        }
        $this->token->setSecret($this->config['secret']);
    }

    /**
     * make a Header Object
     * @return Header
     */
    private function makeHeader(): Header
    {
        $header = new Header();

        if (isset($this->config['alg'])) {
            $header->setAlg($this->config['alg']);
        }
        if (isset($this->config['typ'])) {
            $header->setTyp($this->config['typ']);
        }

        return $header;
    }

    private function makePayload($businessModel): Payload
    {
        $payload = new Payload();

        if ($businessModel instanceof BusinessModelInterface) {
            $payload->businessInfo = $this->getBusinessInfo($businessModel);
        } else {
            $payload->businessInfo = $businessModel;
        }

        $currentTime = time();
        $payload->iss = $this->config['issuer'] ?? 'EasyAuth';
        $payload->exp = $currentTime + ($this->config['expire'] ?? 0);
        $payload->iat = $currentTime;

        return $payload;
    }

    private function getBusinessInfo(BusinessModelInterface $businessModel): array
    {
        $businessInfo = [];
        foreach ($businessModel as $key => $value) {
            if (in_array($key, $businessModel->showableAttributeNames())) {
                $businessInfo[$key] = $value;
            }
        }
        return $businessInfo;
    }

    /**
     * @param $businessInfo
     * @return String
     * @throws CreatedTokenException
     */
    public function getToken($businessInfo)
    {
        if(empty($businessInfo)){
           throw new CreatedTokenException('businessInfo can\'t be empty.');
        }
        $this->token->setPayload($this->makePayload($businessInfo));
        return $this->token->toClaimString();
    }

    /**
     * parse the token string, if success that payload will return.
     * @param string $token
     * @return mixed
     * @throws \Exception
     */
    public function parseToken($token = ''): Payload
    {
        $tokenEx = explode('.', $token);
        if (count($tokenEx) != 3) {
            throw new InvalidTokenException();
        }
        $payloadEncode = $tokenEx[1] ?? '';
        $payloadDecode = Base64Helper::decode($payloadEncode);
        $payloadArray = json_decode($payloadDecode, true);
//        var_dump($payloadArray);exit;
        $parsedPayload = Payload::makePayload($payloadArray);
        return $parsedPayload;
    }
}