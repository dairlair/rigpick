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
}
