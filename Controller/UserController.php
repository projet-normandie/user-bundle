<?php

namespace ProjetNormandie\UserBundle\Controller;

use ProjetNormandie\UserBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function autocomplete(Request $request)
    {
        $q = $request->query->get('query', null);
        return $this->userService->autocomplete($q);
    }
}
