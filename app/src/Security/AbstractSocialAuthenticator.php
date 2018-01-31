<?php

namespace App\Security;

use App\Entity\Key;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractSocialAuthenticator extends SocialAuthenticator
{
    abstract protected function socialNetworkSlug() : string;

    protected $clientRegistry;
    protected $em;
    protected $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getSocialClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $socialClient = $this->getSocialClient();

        /** @var ResourceOwnerInterface $socialNetworkUser */
        $socialNetworkUser = $socialClient->fetchUserFromToken($credentials);

        /** @var Key $key */
        $key = $this->em->getRepository(Key::class)->findOneBy([
            'provider' => $this->socialNetworkSlug(),
            'id' => $socialNetworkUser->getId(),
        ]);

        if ($key) {
            return $key->getUser();
        }

        if (method_exists($socialNetworkUser, 'getEmail')) {
            /** @var User $user */
            $user = $this->em->getRepository(User::class)
                ->findOneBy(['email' => $socialNetworkUser->getEmail()]);

            if ($user) {
                // This user already registered with this email. We have to store Key for him.
                $this->createKey($socialNetworkUser, $user);

                return $user;
            }
        }

        // New user, register him
        $user = $this->createUser($socialNetworkUser);

        $this->createKey($socialNetworkUser, $user);

        return $user;
    }

    /**
     * @param ResourceOwnerInterface $socialNetworkUser
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return User
     */
    protected function createUser(ResourceOwnerInterface $socialNetworkUser) : User
    {
        $user = new User();
        if (method_exists($socialNetworkUser, 'getEmail')) {
            $user->setEmail($socialNetworkUser->getEmail());
        }
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * @param ResourceOwnerInterface $resourceOwner
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createKey(ResourceOwnerInterface $resourceOwner, User $user) : void
    {
        $key = new Key($this->socialNetworkSlug(), $resourceOwner->getId());
        $key->setUser($user);
        $this->em->persist($key);
        $this->em->flush();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->router->generate('dashboard');

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }


    public function supports(Request $request)
    {
        return $request->getPathInfo() === '/connect/' . $this->socialNetworkSlug() . '/check';
    }

    protected function getSocialClient()
    {
        return $this->clientRegistry->getClient($this->socialNetworkSlug());
    }

    /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }
}
