<?php

namespace ProjetNormandie\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProjetNormandie\EmailBundle\Entity\Email;

class ResettingController extends Controller
{
    private $userManager;
    private $tokenGenerator;
    private $mailer;
    /**
     * @var int
     */
    private $retryTtl;

    /**
     * @param UserManagerInterface     $userManager
     * @param TokenGeneratorInterface  $tokenGenerator
     * @param MailerInterface          $mailer
     * @param int                      $retryTtl
     */
    public function __construct(
        UserManagerInterface $userManager,
        TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer, $retryTtl = 7200
    )
    {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
        $this->retryTtl = $retryTtl;
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function sendEmail(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->getResponse(false, 'USER_NOT_FOUND');
        }

        if ($user->isPasswordRequestNonExpired($this->retryTtl)) {
            return $this->getResponse(false, 'PASSWORD_REQUEST_NOT_EXPIRED');
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $rendered = $this->renderView(
            '@ProjetNormandieUser/Resetting/email.txt.twig',
            [
                'user' => $user,
                'confirmationUrl' => $_ENV['FRONT_URL'] . '/#/auth/reset?token=' . $user->getConfirmationToken(),
            ]
        );

        $renderedLines = explode("\n", trim($rendered));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);

        $mail = new Email();
        $mail
            ->setTargetMail($user->getEmail())
            ->setSubject($subject)
            ->setBodyHtml($body)
            ->setBodyText($body);

        $mailer = $this->get('projet_normandie_email.mailer');
        $mailer->send($mail);

        $user->setPasswordRequestedAt(new \DateTime());
        $this->userManager->updateUser($user);

        return $this->getResponse(true, 'PASSWORD_REQUEST_SEND');
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function reset(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'];
        $password = $data['password'];

        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return $this->getResponse(false, 'INVALID_TOKEN');
        }

        $user->setPlainPassword($password);
        $user->setConfirmationToken(null);
        $this->userManager->updateUser($user);

        return $this->getResponse(true, 'PASSWORD_CHANGE');
    }

    /**
     * @param bool $success
     * @param null    $message
     * @return Response
     */
    private function getResponse(bool $success, $message = null) {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
            'message' => $message,
        ]));
        return $response;
    }

}
