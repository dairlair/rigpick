<?php declare(strict_types = 1);

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        return $this->render('views/dashboard/index.html.twig');
    }
}