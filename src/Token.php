<?php

namespace IBye\EasyAuth;


use IBye\EasyAuth\Des\DesInterface;
use IBye\EasyAuth\Exceptions\CreatedTokenException;

class Token
{
    private $header;
    private $payload;
    private $signature;
    private $algs = [];
    private $secret = '';

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload(Payload $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @param Header $header
     */
    public function setHeader(Header $header): void
    {
        $this->header = $header;
    }

    public function __construct(Header $header)
    {
        $this->setHeader($header);
    }

    /**
     * generate token
     * @return String
     * @throws CreatedTokenException
     * @throws \ReflectionException
     */
    public function toClaimString(): String
    {
        if (empty($this->header) || empty($this->payload)) {
            throw new CreatedTokenException();
        }

        $headerAndPayloadEncode = "{$this->header}.{$this->payload}";

        $this->signature = $this->sign($headerAndPayloadEncode);
        return "{$headerAndPayloadEncode}.{$this->signature}";
    }

    /**
     * @param $data
     * @return mixed
     * @throws CreatedTokenException
     * @throws \ReflectionException
     */
    public function sign($data)
    {
        if ($this->header instanceof Header) {
            if (isset($this->algs[$this->header->getAlg()])) {
                $val = $this->algs[$this->header->getAlg()];
                if (is_callable($val)) {
                    $signature = $val($data, $this->getSecret());
                } else if (is_array($val)) {
                    if (!isset($val['class'])) {
                        throw new CreatedTokenException('class is null.');
                    }

                    $className = $val['class'];
                    $args = $val['args'] ?? [];
                    $class = new \ReflectionClass($className);
                    if ($class->getConstructor() !== null) {
                        $des = $class->newInstance($args);
                    } else {
                        $des = new $className();
                    }
                    if ($des instanceof DesInterface) {
                        $signature = $des->encrypt($data, $this->getSecret());
                    }
                }

                if (empty($signature)) {
                    throw new CreatedTokenException('sign error.');
                }
                return $signature;
            }
        }
        throw new CreatedTokenException('getting signature error.');
    }

    /**
     * @return array
     */
    public function getAlgs(): array
    {
        return $this->algs;
    }

    /**
     * @param array $algs
     */
    public function setAlgs(array $algs): void
    {
        $this->algs = $algs;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }
}