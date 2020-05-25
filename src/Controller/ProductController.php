<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
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
            ->findAllWithLimit();

        if (!$product) {
            $this->fetchDataProducts();

            return $this->redirectToRoute('product');
        }
       
        return $this->json($product);
    
    }

    /**
     * @Route("/fetchDataProduct", name="fetchDataProduct", methods={"GET","HEAD"})
     * @return Response
     */
    public function fetchDataProducts()
    {
        $csv_path = "https://catch-code-challenge.s3-ap-southeast-2.amazonaws.com/products.csv";

        if (($csv_handle = fopen($csv_path, "r")) === FALSE) {
            throw new Exception('Cannot open CSV file');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $unsetHeader = fgetcsv($csv_handle,2000,",");
        $i = 0;
        while (($line = fgetcsv($csv_handle,2000,",")) !== false) {

            $product = new Product();
            $product->setId($line[0]);
            $product->setName($line[1]);
            $product->setSalePrice($line[2]);
            $product->setRetailPrice($line[3] ?: null);
            $product->setImageUrl($line[4]);
            $product->setQuantityAvailable($line[5]);

            $entityManager->persist($product);

            $i++;
        }

        $entityManager->flush(); 
        $entityManager->clear();

        fclose($csv_handle);

        return new Response(
            'Fetch data success'
        );
    }
}
