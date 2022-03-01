<?php
namespace ProjetNormandie\UserBundle\Security;

use ProjetNormandie\UserBundle\Form\AdminLoginForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

final class AdminLoginAuthenticator extends AbstractFormLoginAuthenticator implements AuthenticatorInterface
{
    private FormFactoryInterface $formFactory;
    private RouterInterface $router;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'admin_login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        $form = $this->formFactory->create(AdminLoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['email']
        );

        return $data;
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->router->generate('admin_login'));
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->router->generate('admin_login');
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }
}