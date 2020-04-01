<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Admin;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/inscription", name="admin_security_registration")
     */
    public function adminRegistration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $admin = new Admin();

        $form = $this->createForm(AdminRegistrationType::class, $admin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($admin, $admin->getPassword());

            $admin->setPassword($hash);

            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('security/adminRegistration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login() 
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() 
    {
    }
}
