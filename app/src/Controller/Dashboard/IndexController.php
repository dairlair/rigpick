<?php declare(strict_types = 1);

namespace App\Controller\Dashboard;

use App\Entity\Rig;
use App\Repository\RigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @param RigRepository $rigRepository
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function indexAction(RigRepository $rigRepository): Response
    {
        /** @var Rig[] $rigs */
        $rigs = $rigRepository->findByUserWithGpus($this->getUser());

        return $this->render('views/dashboard/index.html.twig', [
            'rigs' => $rigs,
        ]);
    }
}
