<?php

namespace App\Controller\Site;

use App\Entity\VgaBiosIndex;
use App\Repository\VgaBiosIndexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphicCardsController extends Controller
{
    /**
     * @var VgaBiosIndexRepository
     */
    public $vgaBiosIndexRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->vgaBiosIndexRepository = $em->getRepository(VgaBiosIndex::class);
    }

    /**
     * @Route("/graphic-cards/{page}", name="graphic_cards", defaults={"page"=1}, requirements={"page"="\d+"})
     *
     * @param int $page
     *
     * @return Response
     */
    public function index(int $page = 1): Response
    {
        $cards = $this->vgaBiosIndexRepository->findLatest($page);

        return $this->render('views/graphic-cards/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    /**
     * @Route("/graphic-cards/{id}-{slug}", name="graphic_card", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param null|string $slug
     *
     * @return Response
     */
    public function show(int $id, ?string $slug): Response
    {
        /** @var VgaBiosIndex $card */
        $card = $this->vgaBiosIndexRepository->find($id);

        if ($slug !== $card->getSlug()) {

            return $this->redirectToRoute('graphic_card', ['id' => $card->getVgaBiosId(), 'slug' => $card->getSlug()], 301);
        }

        return $this->render('views/graphic-cards/show.html.twig', [
            'card' => $card,
        ]);
    }
}
