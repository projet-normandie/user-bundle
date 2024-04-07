<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ORM\Entity]
#[ORM\Table(name: 'pnu_refresh_tokens')]
class RefreshToken extends BaseRefreshToken
{
}
