<?php

namespace App\Controller;

use LogicException;
use App\Entity\Products;
use App\Form\ProduitRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductWorkFlowController extends AbstractController
{
    public $envoiCommandeWorkflow;
    public function __construct(WorkflowInterface $envoiCommandeWorkflow)
    {
        $this->envoiCommandeWorkflow = $envoiCommandeWorkflow;
    }
    /**
     * @Route("/slect", name="slect")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prod = new Products();

        $prod->setUser($this->getUser());

        $form = $this->createForm(ProduitRequestType::class, $prod);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->envoiCommandeWorkflow->apply($prod, 'pour_creation');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($prod);
            $entityManager->flush();
            $this->addFlash('success', 'Demande enregistrée !');
            return $this->redirectToRoute('buy', ['id' => $prod->getId()]);
        }


        return $this->render('productworkfow/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/buy/{id}", name="buy")
     */
    public function change(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $prod = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        $prod->setUser($this->getUser());
        //$form = $this->createForm(ProduitRequestType::class, $prod);
        //$form->handleRequest($request);


        if (isset($_POST['submit1'])) {
            try {
                $this->envoiCommandeWorkflow->apply($prod, 'pour_paiement');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($prod);
            $entityManager->flush();
            return $this->redirectToRoute('expediee', ['id' => $prod->getId()]);
        }

        if (isset($_POST['submit2'])) {
            try {
                $this->envoiCommandeWorkflow->apply($prod, 'to_annulee');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($prod);
            $entityManager->flush();
            return $this->redirectToRoute('slect');
        }
        return $this->render('productworkfow/annuler.html.twig');
    }


    /**
     * @Route("/expediee/{id}", name="expediee")
     */
    public function expediee(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $prod = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        $prod->setUser($this->getUser());
        $form = $this->createForm(ProduitRequestType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->envoiCommandeWorkflow->apply($prod, 'pour_expedition');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($prod);
            $entityManager->flush();
            return $this->redirectToRoute('livree', ['id' => $prod->getId()]);
        }
        return $this->render('productworkfow/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/livree/{id}", name="livree")
     */
    public function livree(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $prod = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        $prod->setUser($this->getUser());
        $form = $this->createForm(ProduitRequestType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->envoiCommandeWorkflow->apply($prod, 'pour_livraison');
            } catch (LogicException $exception) {
                //
            }
            $entityManager->persist($prod);
            $entityManager->flush();
        }
        return $this->render('productworkfow/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
