<?php

namespace ProjetNormandie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    public function profile(): array
    {
        return array(
            $this->getUser()->getRoles(),
            $this->getUser(),
        );
    }
}
