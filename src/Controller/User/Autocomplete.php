<?php

declare(strict_types=1);

namespace ProjetNormandie\UserBundle\Controller\User;

use ProjetNormandie\UserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class Autocomplete extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request): mixed
    {
        $q = $request->query->get('query', null);
        return $this->userRepository->autocomplete($q);
    }
}
