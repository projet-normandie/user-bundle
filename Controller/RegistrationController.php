<?php

namespace ProjetNormandie\UserBundle\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProjetNormandie\EmailBundle\Service\Mailer;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    private $userManager;
    private $tokenGenerator;
    private $translator;
    private $mailer;

    /**
     * RegistrationController constructor.
     * @param UserManagerInterface    $userManager
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TranslatorInterface     $translator
     * @param Mailer                  $mailer
     */
    public function __construct(
        UserManagerInterface $userManager,
        TokenGeneratorInterface $tokenGenerator,
        TranslatorInterface $translator,
        Mailer $mailer
    ) {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];

        // Check username
        $user = $this->userManager->findUserByUsername($username);
        if ($user !== null) {
            return $this->getResponse(false, $this->translator->trans('registration.username_exists'));
        }

        // Check email
        $user = $this->userManager->findUserByEmail($email);
        if ($user !== null) {
            return $this->getResponse(false, $this->translator->trans('registration.email_exists'));
        }

        $user = $this->userManager->createUser();
        $user->setEnabled(false);
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setConfirmationToken($this->tokenGenerator->generateToken());


        $this->userManager->updateUser($user);

        // Send email to activate account
        $body = sprintf(
            $this->translator->trans('registration.email.message'),
            $user->getUsername(),
            $this->getParameter('projetnormandie_user.url.front') . '#/en/register?token=' . $user->getConfirmationToken()
        );

        $this->mailer->send(
            $user->getEmail(),
            sprintf($this->translator->trans('registration.email.subject'), $user->getUsername()),
            $body
        );

        return $this->getResponse(true, sprintf($this->translator->trans('registration.check_email'), $email));
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function confirm(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];

        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return $this->getResponse(false, $this->translator->trans('registration.token_invalid'));
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->userManager->updateUser($user);

        return $this->getResponse(
            true,
            sprintf($this->translator->trans('registration.confirmed'), $user->getUsername())
        );
    }

    /**
     * @param bool $success
     * @param null    $message
     * @return Response
     */
    private function getResponse(bool $success, $message = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
        ]));
        return $response;
    }
}
