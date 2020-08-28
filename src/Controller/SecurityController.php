<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\RedefinePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('employee');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/redefinePasswordForm/", name="redefinePasswordForm")
     */
    public function redefinePasswordForm(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(RedefinePasswordType::class, new Employee());
        $form->handleRequest($request);
        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $this->getUser();
            $employee->setRedefinedPassword(true);
            // on change le password du user avec un nouveau, chiffré
            $employee->setPassword($passwordEncoder->encodePassword($employee, $form->get('password')->getData()));
            $em->flush();
            // on redirige
            return $this->redirectToRoute('employee');

        } else {
            return $this->render('security/redefinePassword.html.twig', ['form'=> $form->createView()]);
        }
    }
}
