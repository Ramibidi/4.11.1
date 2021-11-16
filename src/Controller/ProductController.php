<?php

namespace App\Controller;



use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class  ProductController extends AbstractController
{


    /**
     * @Route("/afficheProduit", name="afficheProduit")
     */
    public function afficheProduit()
    {

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        return $this->render('product/product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit")
     */
    public function index($id)
    {

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
        return $this->render('product/productProfile.html.twig', [
            'product' => $product
        ]);
    }
}
