<?php

namespace App\Controller\Site;

use App\Form\UserType;
use App\Entity\User;
use App\Security\UserLoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends Controller
{
    private $guardAuthenticatorHandler;
    private $userLoginFormAuthenticator;

    public function __construct(GuardAuthenticatorHandler $handler, UserLoginFormAuthenticator $authenticator)
    {
        $this->guardAuthenticatorHandler = $handler;
        $this->userLoginFormAuthenticator = $authenticator;
    }

    /**
     * @Route("/join", name="join")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function joinAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        // Build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Save the user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->guardAuthenticatorHandler
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->userLoginFormAuthenticator,
                    'db_provider'
                );
        }

        return $this->render(
            'views/auth/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/login", name="login")
     *
     * @param Request             $request
     * @param AuthenticationUtils $authUtils
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $form = $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl('login_check'),
        ]);

        // Get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('views/auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}
