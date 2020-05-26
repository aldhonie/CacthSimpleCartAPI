<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function getProducts()
    {
        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->findAllWithLimit();

        if (!$product) {
            $this->fetchDataProducts();

            return $this->redirectToRoute('products');
        }
       
        return $this->json($product);
    }

    /**
     * @Route("/fetchDataProducts", name="fetchDataProducts", methods={"GET","HEAD"})
     * @return Response
     */
    public function fetchDataProducts()
    {
        $csv_path = "https://catch-code-challenge.s3-ap-southeast-2.amazonaws.com/products.csv";

        if (($csv_handle = fopen($csv_path, "r")) === false) {
            throw new Exception('Cannot open CSV file');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $unsetHeader = fgetcsv($csv_handle, 2000, ",");
        $i = 0;
        while (($line = fgetcsv($csv_handle, 2000, ",")) !== false) {
            $product = new Products();
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
