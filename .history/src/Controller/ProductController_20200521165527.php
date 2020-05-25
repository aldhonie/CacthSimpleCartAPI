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
     * @Route("/fetchDataProduct", name="fetchDataProduct", methods={"GET","HEAD"})
     */
    public function fetchDataProducts()
    {
        $csv_path = "https://catch-code-challenge.s3-ap-southeast-2.amazonaws.com/products.csv";

        if (($csv_handle = fopen($csv_path, "r")) === FALSE) {
            throw new Exception('Cannot open CSV file');
        }

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        foreach (fgetcsv($csv_handle) as $key => $value) {
            # code...
            $dt = $value.$key;
        }

        $i = 0;

        while (!feof($csv_handle)) {
            
            $i++;

            if ($i > 1) {
                $line = fgetcsv($csv_handle);
                dd($line);
                $product = new Product();
                $product->setId($line[0]);
                $product->setName($line[1]);
                $product->setSalePrice($line[2]);
                $product->setRetailPrice($line[3]);
                $product->setImageUrl($line[4]);
                $product->setQuantityAvailable($line[5]);

                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($product);

                // actually executes the queries (i.e. the INSERT query)
                if ($i % 25 == 0) {
                    $entityManager->flush(); 
                    $entityManager->clear();
                }
            }
        }

        fclose($csv_handle);
    }
}
