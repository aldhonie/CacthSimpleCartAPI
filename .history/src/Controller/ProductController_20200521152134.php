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
        // $product = new Product();
        // print_r($product);
        // die();

        // die($entityManager);
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        print_r($product);
        die();    
            

        // return $this->json([
        //     '' =>

        // ]);
    }
}
