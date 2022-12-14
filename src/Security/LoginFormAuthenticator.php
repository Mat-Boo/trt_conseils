<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private $entityManager;
    
    use TargetPathTrait;
    
    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManqager)
    {
        $this->entityManager = $entityManqager;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                ]
            );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }
        if (in_array('ROLE_CONSULTANT', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_consultant'));
        }
        if (in_array('ROLE_CANDIDATE', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_candidate'));
        }
        if (in_array('ROLE_RECRUITER', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_recruiter'));
        }
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
