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
    
        return new Response('Saved new product with id '.$product->getId());  
    
    }

    /**
     * @Route("/fetchDataProduct", name="fetchDataProduct")
     */
    public function fetchDataProducts()
    {
        extract($options);

        if (($csv_handle = fopen($csv_path, "r")) === FALSE)
            throw new Exception('Cannot open CSV file');

        if(!$delimiter)
            $delimiter = ',';

        if(!$table)
            $table = preg_replace("/[^A-Z0-9]/i", '', basename($csv_path));

        if(!$fields){
            $fields = array_map(function ($field){
                return strtolower(preg_replace("/[^A-Z0-9]/i", '', $field));
            }, fgetcsv($csv_handle, 0, $delimiter));
        }

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $i = 0;

        while (!feof($csv_handle)) {

            $line = fgetcsv($csv_handle);

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

            $i = $i + 1;
        }

        fclose($csv);

        $manager->flush();



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

        if ($i % 25 == 0) {
            $entityManager->flush(); 
            $entityManager->clear();
        }
    }
}
