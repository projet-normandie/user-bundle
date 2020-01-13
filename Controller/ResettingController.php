<?php

namespace ProjetNormandie\UserBundle\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProjetNormandie\EmailBundle\Entity\Email;

class ResettingController extends Controller
{
    private $userManager;
    private $tokenGenerator;
    /**
     * @var int
     */
    private $retryTtl;

    /**
     * @param UserManagerInterface     $userManager
     * @param TokenGeneratorInterface  $tokenGenerator
     * @param int                      $retryTtl
     */
    public function __construct(
        UserManagerInterface $userManager,
        TokenGeneratorInterface $tokenGenerator, $retryTtl = 7200
    )
    {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->retryTtl = $retryTtl;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function sendEmail(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->getResponse(false, $this->get('translator')->trans('resetting.user_not_found'));
        }

        if ($user->isPasswordRequestNonExpired($this->retryTtl)) {
            return $this->getResponse(false, $this->get('translator')->trans('resetting.request_not_expired'));
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $body = sprintf(
            $this->get('translator')->trans('resetting.email.message'),
            $user->getUsername(),
            $_ENV['FRONT_URL'] . '/#/auth/reset?token=' . $user->getConfirmationToken()
        );

        $mail = new Email();
        $mail
            ->setTargetMail($user->getEmail())
            ->setSubject($this->get('translator')->trans('resetting.email.subject'))
            ->setBodyHtml($body)
            ->setBodyText($body);

        // todo replace by PN mailer injection
        $mailer = $this->get('projet_normandie_email.mailer');
        $mailer->send($mail);

        $user->setPasswordRequestedAt(new DateTime());
        $this->userManager->updateUser($user);

        return $this->getResponse(true, sprintf($this->get('translator')->trans('resetting.check_email'), $this->retryTtl / 3600));
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

        return $this->getResponse(true, $this->get('translator')->trans('resetting.success'));
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
