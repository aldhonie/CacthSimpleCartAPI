<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function getProducts()
    {
        // $entityManager = $this->getDoctrine()->getManager();

        die($entityManager);
        // $product = $this->getDoctrine()
        //     ->getRepository(Product::class)
        //     ->findAll();

        die($product);    
            

        // return $this->json([
        //     '' =>

        // ]);
    }
}
