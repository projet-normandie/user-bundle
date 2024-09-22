<?php

namespace ProjetNormandie\UserBundle\Controller\Security;

use ProjetNormandie\UserBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfirmRegistration extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly TranslatorInterface $translator
    ) {
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];

        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new BadRequestException($this->translator->trans('registration.token_invalid', [], 'PnUser'));
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->userManager->updateUser($user);

        return new JsonResponse(['success' => true]);
    }
}
