<?php

namespace ProjetNormandie\UserBundle\Controller\Security;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ProjetNormandie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ConfirmPassword extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }


    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];
        $password = $data['password'];

        $user = $this->em->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (null === $user) {
            throw new BadRequestException();
        }

        $passwordRequestedAt = $user->getPasswordRequestedAt();
        $now = new DateTime();

        $interval = $now->diff($passwordRequestedAt);
        $hours = intval($interval->format('%d')) * 24 + intval($interval->format('%h'));

        if ($hours > 24) {
            throw new BadRequestException();
        }

        $user->setPlainPassword($password);
        $user->setConfirmationToken(null);

        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}
