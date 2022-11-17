<?php
namespace ProjetNormandie\UserBundle\Util;

use Exception;

class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @return string
     * @throws Exception
     */
    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}