<?php

namespace ProjetNormandie\UserBundle\Controller;

use Exception;
use Doctrine\Persistence\ManagerRegistry;
use ProjetNormandie\UserBundle\Doctrine\UserManager;
use ProjetNormandie\UserBundle\Util\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    private UserManager $userManager;
    private ManagerRegistry $doctrine;
    private TokenGenerator $tokenGenerator;
    private TranslatorInterface $translator;
    private MailerInterface $mailer;

    /**
     * RegistrationController constructor.
     * @param UserManager         $userManager
     * @param ManagerRegistry     $doctrine
     * @param TokenGenerator      $tokenGenerator
     * @param TranslatorInterface $translator
     * @param MailerInterface     $mailer
     */
    public function __construct(
        UserManager $userManager,
        ManagerRegistry $doctrine,
        TokenGenerator $tokenGenerator,
        TranslatorInterface $translator,
        MailerInterface $mailer
    ) {
        $this->userManager = $userManager;
        $this->doctrine = $doctrine;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $username = $data['username'];
        $password = $data['password'];
        $rules_accepted = $data['rules_accepted'] ?? false;

        // Check username
        if (!$rules_accepted) {
            return $this->getResponse(false, $this->translator->trans('registration.must_accept_rules'));
        }

        $this->checkIp($request, $username);

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
            ($request->server->get('HTTP_ORIGIN') ?? null)  . '/en/register?token=' . $user->getConfirmationToken()
        );

        $email = (new Email())
            ->to($user->getEmail())
            ->subject(sprintf($this->translator->trans('registration.email.subject'), $user->getUsername()))
            ->text($body)
            ->html($body);

        $this->mailer->send($email);

        return $this->getResponse(true, sprintf($this->translator->trans('registration.check_email'), $email));
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function confirm(Request $request): Response
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
     * @param Request $request
     * @param string  $username
     * @return Response|void
     * @throws TransportExceptionInterface
     */
    private function checkIp(Request $request, string $username)
    {
        // check IP
        $remotAddr = $request->getClientIp();
        $ip = $this->doctrine->getRepository('ProjetNormandie\UserBundle\Entity\Ip')
            ->findOneBy(array('label' => $remotAddr));
        if ($ip && $ip->isBanned()) {
            // Send alert email
            $body = sprintf(
                $this->translator->trans('ip.email.message'),
                $username,
                $remotAddr
            );
            $this->mailer->send(
                $this->translator->trans('ip.email.subject'),
                $body
            );
            return $this->getResponse(false, $this->translator->trans('registration.error'));
        }
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
        ]));
        return $response;
    }
}
