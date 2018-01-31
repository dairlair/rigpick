<?php

namespace App\Controller\Site;

use App\Entity\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google")
     */
    public function connectAction()
    {
        // Scopes that we need from google
        $scopes = ['profile', 'email'];
        return $this->get('oauth2.registry')
            ->getClient(Key::PROVIDER_GOOGLE)
            ->redirect($scopes);
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     *
     * @param Request $request
     */
    public function connectCheckAction(Request $request)
    {
    }
}
