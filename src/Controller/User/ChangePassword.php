<?php

namespace ProjetNormandie\UserBundle\Controller\User;

use Doctrine\ORM\EntityManagerInterface;
use ProjetNormandie\UserBundle\Entity\User;
use ProjetNormandie\UserBundle\Event\PasswordChangedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsController]
class ChangePassword extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $currentPassword = $data['currentPassword'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        /** @var User $user */
        $user = $this->getUser();

        // Verify current password
        if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
            return new JsonResponse(
                [
                    'message' => $this->translator->trans(
                        'change_password.current_password_invalid',
                        [],
                        'PnUser',
                        $user->getLanguage()
                    ),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Hash the new password directly
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $newPassword
        );
        $user->setPassword($hashedPassword);

        // Save changes
        $this->entityManager->flush();

        // Dispatch password changed event manually
        $passwordChangedEvent = new PasswordChangedEvent($user);
        $this->eventDispatcher->dispatch($passwordChangedEvent);

        return new JsonResponse(['success' => true]);
    }
}
