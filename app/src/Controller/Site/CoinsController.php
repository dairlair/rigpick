<?php

namespace App\Controller\Site;

use App\Entity\Coin;
use App\Repository\CoinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CoinsController extends Controller
{
    /**
     * @Route("/coins/{page}", name="coins", defaults={"page"=1}, requirements={"page"="\d+"})
     *
     * @param CoinRepository $coinRepository
     * @param int $page
     *
     * @return Response
     */
    public function index(CoinRepository $coinRepository, int $page = 1): Response
    {
        $coins = $coinRepository->findLatest($page);

        return $this->render('views/coins/index.html.twig', [
            'coins' => $coins,
        ]);
    }

    /**
     * @Route("/coins/{ticker}", name="coin")
     *
     * @param CoinRepository $coinRepository
     * @param string $ticker
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function show(CoinRepository $coinRepository, string $ticker): Response
    {
        /** @var Coin $coin */
        $coin = $coinRepository->findByTicker($ticker);
        if (!$coin) {
            throw new NotFoundHttpException("Coin [$ticker] not found");
        }

        return $this->render('views/coins/show.html.twig', ['coin' => $coin]);
    }
}
