<?php declare(strict_types = 1);

namespace App\Controller\Dashboard;

use App\Entity\Rig;
use App\Form\RigType;
use App\Services\RigsHashGenerator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RigsController extends Controller
{
    /**
     * @Route("/rigs/add", name="dashboard_rigs_add")
     *
     * @param Request $request
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
}
