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

        $i = 0;

        while (!feof($csv)) {
            
            $line = fgetcsv($csv_handle);

            $coordinatesfrcity[$i] = new CoordFRCity();
            $coordinatesfrcity[$i]->setAreaPre2016($line[0]);
            $coordinatesfrcity[$i]->setAreaPost2016($line[1]);
            $coordinatesfrcity[$i]->setDeptNum($line[2]);
            $coordinatesfrcity[$i]->setDeptName($line[3]);
            $coordinatesfrcity[$i]->setdistrict($line[4]);
            $coordinatesfrcity[$i]->setpostCode($line[5]);
            $coordinatesfrcity[$i]->setCity($line[6]);

            $manager->persist($coordinatesfrcity[$i]);

            $this->addReference('coordinatesfrcity-'.$i, $coordinatesfrcity[$i]);


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

        return new Response('Saved new product with id '.$product->getId()); 
    }
}
