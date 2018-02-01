<?php

namespace App\Controller\Site;

use App\Repository\VgaBiosIndexRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphicCardsController extends Controller
{
    /**
     * @Route("/graphic-cards/{page}", name="graphic_cards", defaults={"page"=1})
     *
     * @param VgaBiosIndexRepository $vgaBiosIndexRepository
     *
     * @param int $page
     *
     * @return Response
     */
    public function indexAction(VgaBiosIndexRepository $vgaBiosIndexRepository, int $page = 1): Response
    {
        $cards = $vgaBiosIndexRepository->findLatest($page);

        return $this->render('views/graphic-cards/index.html.twig', [
            'cards' => $cards,
        ]);
    }
}
