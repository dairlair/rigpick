<?php

namespace App\Controller;

use App\Entity\Key;
use App\Services\Socializator;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook")
     */
    public function connectAction()
    {
        // Scopes that we need from facebook
        $scopes = ['public_profile', 'email'];
        // will redirect to Facebook!
        return $this->get('oauth2.registry')
            ->getClient('facebook_main') // key used in knpu_oauth2_client.yaml
            ->redirect($scopes);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
        $client = $this->get('oauth2.registry')
            ->getClient('facebook_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
            $resourceOwner = $client->fetchUser();

            /** @var Socializator $socializator */
            $socializator = $this->container->get(Socializator::class);
            $user = $socializator->findOrCreate(Key::PROVIDER_FACEBOOK, $resourceOwner);
            var_dump($user->getId());
            return '';
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());die;
        }
    }
}