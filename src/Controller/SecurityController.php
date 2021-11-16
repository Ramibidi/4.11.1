<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            // tells Doctrine you want to save the User
            $entityManager->persist($user);
            //executes the queries (i.e. the INSERT query) 
            $entityManager->flush();
            //return $this->redirectToRoute('security_login');
        }

        $users = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findAll();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            foreach ($users as $user) {
                $temp = array(
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),

                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(), 'users' => $users
        ]);
    }





    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {

        return $this->render('security/login.html.twig');
        // $this->redirectToRoute('blog');

    }






    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        //return $this->render('security/login.html.twig');
    }






    /**
     * @Route("/")
     */
    public function affiche()
    {

        return $this->redirectToRoute('your_profile');
    }



    /**
     * @Route("/profile", name="your_profile")
     */
    public function profileUser(Security $security)
    {

        //$userName = $security->getUser()->getUsername();
        // return $this->render('user/yourProfile.html.twig', ['user' => $userName]);
        return new Response('bonjour');
    }



    /**
     * @Route("/blog", name="blog")
     */
    public function accueil()
    {

        return $this->render('user/welcome.html.twig');
    }
}
