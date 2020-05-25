<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function getProducts()
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        die($product);    
            

        // return $this->json([
        //     '' =>

        // ]);
    }
}
