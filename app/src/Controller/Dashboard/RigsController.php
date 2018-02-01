<?php declare(strict_types=1);

namespace App\Controller\Dashboard;

use App\Entity\Rig;
use App\Entity\RigGpu;
use App\Entity\VgaBios;
use App\Entity\VgaBiosIndex;
use App\Form\RigGpuType;
use App\Form\RigType;
use App\Repository\RigRepository;
use App\Services\RigsHashGenerator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RigsController extends Controller
{
    private $rigRepository;

    public function __construct(RigRepository $rigRepository)
    {
        $this->rigRepository = $rigRepository;
    }

    /**
     * @Route("/rigs/add", name="dashboard_rigs_add")
     *
     * @param Request $request
     * @param RigsHashGenerator $rigsHashGenerator
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, RigsHashGenerator $rigsHashGenerator)
    {
        // Build the form
        $rig = new Rig();
        $rig->setHash($rigsHashGenerator->generate());
        $rig->setCreatedAt(new DateTime());
        $rig->setUser($this->getUser());
        $form = $this->createForm(RigType::class, $rig);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rig);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('views/dashboard/rigs/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/rigs/{id}/add-graphic-card", name="dashboard_rigs_add_graphic_card")
     *
     * @param int $id Rig ID
     * @param Request $request
     *
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws NotFoundHttpException
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGraphicCard($id, Request $request)
    {
        /** @var Rig $rig */
        $rig = $this->rigRepository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        if (!$rig) {
            throw new NotFoundHttpException("Rig #$id not found");
        }

        $rigGpu = new RigGpu();
        $rigGpu->setRig($rig);

        $form = $this->createForm(RigGpuType::class, $rigGpu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rigGpu);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('views/dashboard/rigs/add-graphic-card.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
