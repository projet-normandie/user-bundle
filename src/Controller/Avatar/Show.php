<?php

namespace ProjetNormandie\UserBundle\Controller\Avatar;

use League\Flysystem\FilesystemException;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Service\AvatarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class Show extends AbstractController
{
    public function __construct(private readonly AvatarManager $avatarManager)
    {
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{id}/avatar', name: 'pn_user_avatar_show', requirements: ['page' => '\d+'], stateless: false)]
    public function download(User $user): StreamedResponse
    {
        return $this->avatarManager->read($user->getAvatar());
    }
}
