<?php

namespace ProjetNormandie\UserBundle\Controller;

use DateTime;
use Exception;
use ProjetNormandie\UserBundle\Doctrine\UserManager;
use ProjetNormandie\UserBundle\Util\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ProjetNormandie\EmailBundle\Service\Mailer;

class ResettingController extends AbstractController
{
    private UserManager $userManager;
    private TokenGenerator $tokenGenerator;
    private TranslatorInterface $translator;
    private Mailer $mailer;

    /**
     * @var int
     */
    private int $retryTtl;

    /**
     * @param UserManager   $userManager
     * @param TokenGenerator          $tokenGenerator
     * @param TranslatorInterface     $translator
     * @param Mailer                  $mailer
     * @param int                     $retryTtl
     */
    public function __construct(
        UserManager $userManager,
        TokenGenerator $tokenGenerator,
        TranslatorInterface $translator,
        Mailer $mailer,
        int $retryTtl = 7200
    ) {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->retryTtl = $retryTtl;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function sendEmail(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->getResponse(false, $this->translator->trans('resetting.user_not_found'));
        }

        if ($user->isPasswordRequestNonExpired($this->retryTtl)) {
            return $this->getResponse(false, $this->translator->trans('resetting.request_not_expired'));
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $body = sprintf(
            $this->translator->trans('resetting.email.message'),
            $user->getUsername(),
            ($request->server->get('HTTP_ORIGIN') ?? null) . '/en/auth/reset?token=' . $user->getConfirmationToken()
        );

        $this->mailer->send(
            $this->translator->trans('resetting.email.subject'),
            $body,
            null,
            $user->getEmail()
        );

        $user->setPasswordRequestedAt(new DateTime());
        $this->userManager->updateUser($user);

        return $this->getResponse(
            true,
            sprintf($this->translator->trans('resetting.check_email'), $this->retryTtl / 3600)
        );
    }


    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function reset(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];
        $password = $data['password'];

        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return $this->getResponse(false, $this->translator->trans('resetting.invalid_token'));
        }

        $user->setPlainPassword($password);
        $user->setConfirmationToken(null);
        $this->userManager->updateUser($user);

        return $this->getResponse(true, $this->translator->trans('resetting.success'));
    }

    /**
     * @param bool $success
     * @param null    $message
     * @return Response
     */
    private function getResponse(bool $success, $message = null): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
            'server' => $_SERVER
        ]));
        return $response;
    }
}