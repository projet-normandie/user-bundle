<?php

namespace ProjetNormandie\UserBundle\Service;

use ProjetNormandie\UserBundle\Repository\UserRepository;
use VideoGamesRecords\CoreBundle\Entity\User;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @param $date
     * @return array
     */
    public function getLoggedToday($date) : array
    {
        return $this->userRepository->getLoggedToday($date);
    }
}
