<?php
namespace ProjetNormandie\UserBundle\Security;

use ProjetNormandie\UserBundle\Form\AdminLoginForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class AdminLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    private FormFactoryInterface $formFactory;
    private RouterInterface $router;
    private UserPasswordHasherInterface $passwordHasher;
    private UserProviderInterface $userProvider;

    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        UserPasswordHasherInterface $passwordHasher,
        UserProviderInterface $userProvider
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->passwordHasher = $passwordHasher;
        $this->userProvider = $userProvider;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('admin_login');
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
     * @return UserInterface
     */
    public function getUser($credentials): UserInterface
    {
        return $this->userProvider->loadUserByUsername($credentials['email']);
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $credentials['password']);
    }


    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $firewallName
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
    }


    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);
        $user = $this->getUser($credentials);
        if ($this->checkCredentials($credentials, $user)) {
            return new SelfValidatingPassport(
                new UserBadge(
                    $user->getId(),
                    fn () => $user
                )
            );
        } else {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }
    }
}
