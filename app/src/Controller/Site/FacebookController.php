<?php

namespace App\Controller\Site;

use App\Entity\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook")
     */
    public function connectAction()
    {
        // Scopes that we need from facebook
        $scopes = ['public_profile', 'email'];
        return $this->get('oauth2.registry')
            ->getClient(Key::PROVIDER_FACEBOOK)
            ->redirect($scopes);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     *
     * @param Request $request
     */
    public function connectCheckAction(Request $request)
    {
    }
}
