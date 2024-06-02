<?php

namespace ProjetNormandie\UserBundle\Controller\Security;

use DateTime;
use Exception;
use ProjetNormandie\UserBundle\Manager\UserManager;
use ProjetNormandie\UserBundle\Util\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsController]
class SendPasswordResetLink extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly TokenGenerator $tokenGenerator,
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
        private readonly int $retryTtl = 7200
    ) {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

        $user = $this->userManager->findUserByUsernameOrEmail($username);
        if ($user && (null === $user->getPasswordRequestedAt() || $user->isPasswordRequestExpired($this->retryTtl))) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
            $body = sprintf(
                $this->translator->trans('resetting.email.message', [], 'PnUser'),
                $user->getUsername(),
                ($request->server->get('HTTP_ORIGIN') ?? null) . '/en/auth/reset?token=' . $user->getConfirmationToken()
            );

            $email = (new Email())
                ->to($user->getEmail())
                ->subject($this->translator->trans('resetting.email.subject', [], 'PnUser'))
                ->text($body)
                ->html($body);

            $this->mailer->send($email);

            $user->setPasswordRequestedAt(new DateTime());
            $this->userManager->updateUser($user);
        }

        return new JsonResponse(['success' => true]);
    }
}
