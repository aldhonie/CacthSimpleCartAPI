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
        $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->findAll();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }
        
        print_r( $product;
        die();
    
        return new Response('Saved new product with id '.$product->getId());  
    
    }

    /**
     * @Route("/fetchDataProduct", name="fetchDataProduct")
     */
    public function fetchDataProducts()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();
        
        $product = new Product();
        $product->setName("Cellsafe Radi Chip Universal");
        $product->setSalePrice(3995);
        $product->setRetailPrice(3000);
        $product->setImageUrl("https://s.catch.com.au/images/product/0018/18663/5c986e257eb06332953424_w200.jpg");
        $product->setQuantityAvailable(53);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId()); 
    }
}
