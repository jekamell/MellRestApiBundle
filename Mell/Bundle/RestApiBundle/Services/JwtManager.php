<?php

namespace Mell\Mell\Bundle\RestApiBundle\Services;

use Namshi\JOSE\JWS;
use Namshi\JOSE\SimpleJWS;

class JwtManager
{
    /**
     * @param array $payload
     * @param resource $privateKey
     * @param int $ttl
     * @param string $algorithm
     * @return string
     */
    public function decode(array $payload, $privateKey, $ttl = 86400, $algorithm = 'RS256')
    {
        $now = new \DateTime();
        $jws = new JWS($algorithm);
        $jws->setPayload(array_merge($payload, ['exp' => $now->getTimestamp() + $ttl]));
        $jws->sign($privateKey);

        return $jws->getTokenString();
    }

    /**
     * @param string $token
     * @param string $publicKey
     * @return array
     */
    public function encode($token, $publicKey)
    {
        try {
            /** @var SimpleJWS $jws */
            $jws = SimpleJWS::load($token);
            if (!$jws->isValid($publicKey)) {

                return $jws->getPayload();
            }
        } catch (\Exception $e) {
        }

        return [];
    }
}
