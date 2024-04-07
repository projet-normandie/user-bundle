<?php

namespace ProjetNormandie\UserBundle\Util;

interface TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generateToken(): string;
}
