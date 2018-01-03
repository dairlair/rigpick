<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends Controller
{
    /**
     * @Route("/", name="site_index")
     */
    public function indexAction()
    {
        return $this->render('site/index.html.twig');
    }

    /**
     * @Route("/profile", name="profile")
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function profileAction()
    {
        return $this->render('site/profile.html.twig');
    }
}
