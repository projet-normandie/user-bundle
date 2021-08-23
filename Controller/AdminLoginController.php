<?php
namespace ProjetNormandie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ProjetNormandie\UserBundle\Form\AdminLoginForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

final class AdminLoginController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function loginAction(): Response
    {
        $form = $this->createForm(AdminLoginForm::class, [
            'email' => $this->authenticationUtils->getLastUsername()
        ]);

        return $this->render('ProjetNormandieUserBundle:security:login.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'form' => $form->createView(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logoutAction(): void
    {
        // Left empty intentionally because this will be handled by Symfony.
    }
}