<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Products;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartsController extends AbstractController
{
    /**
     * @Route("/carts", name="cart", methods={"POST"})
     */
    public function createCart()
    {
        // Init Cart
        try {
            $carts = new Cart();
            $carts->setId(uuid_create(UUID_TYPE_RANDOM));
            $carts->setItems([]);
            $carts->setTotal(0);
            $carts->setTotalFormatted("$0.00");
            $carts->setSavings(0);
            $carts->setSavingsFormatted("$0.00");

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($carts);
            $entityManager->flush();

            $baseUrl = Request::createFromGlobals()->getUri();
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Location', $baseUrl."/".$carts->getId());
            $response->setContent("Cart created successfully");

            return $response->send();
        } catch (\Throwable $e) {
            return $this->json($e->getMessage(), 400);
        }
    }

    /**
     * @Route("/carts/{id}", name="getCart", methods={"GET"})
     */
    public function getCartById($id)
    {
        if (!uuid_is_valid($id)) {
            return $this->json('Invalid or missing Cart id', 400);
        }

        try {
            $cart = $this->getDoctrine()
                ->getRepository(Cart::class)
                ->findOneById($id);

            return $this->json($cart);
        } catch (\Throwable $e) {
            return $this->json($e->getMessage(), 400);
        }
    }

    /**
     * @Route("/carts/{id}/updateItems", name="updateCartItemsById", methods={"POST"})
     * Summary : Update the items in a Cart
     */

    public function updateCartItemsById(ValidatorInterface $validator, LoggerInterface $logger, $id)
    {
        // check cart id is valid
        $cartId = $id;
        if (!uuid_is_valid($cartId)) {
            return $this->json('Invalid or missing Cart id', 400);
        }

        try {
            // Get request $_POST
            $request = Request::createFromGlobals();
            $productId = $request->request->get('productId');
            $quantity = $request->request->get('quantity', 0);

            // Begin Doctrine
            $entityManager = $this->getDoctrine()->getManager();

            // Cart Item Section
            // Set data ProductId and Quantity Cart
            $cartItem = new CartItem();
            $cartItem->setProductId($productId);
            $cartItem->setQuantity($quantity);

            // Validate data request ProductId and Quantity
            $errors = $validator->validate($cartItem);
            if (count($errors) > 0) {
                return $this->json((string) $errors, 400);
            }

            // Products Section
            // Find Products
            $product = $entityManager->getRepository(Products::class)->find($productId);

            if (empty($product) || ($product->getQuantityAvailable() <= $quantity)) {
                return $this->json('Invalid Products.', 400);
            }

            // Set Availability Products
            $quantityAvailable = $product->getQuantityAvailable();
            $product->setQuantityAvailable($quantityAvailable - $quantity);

            // Execute Save Cart Item and Update Products Availability
            $entityManager->persist($cartItem);
            $entityManager->persist($product);
            $entityManager->flush();

            // Logger query have been excute
            $logger->info("Products quantity updated. id:".$product->getId());
            $logger->info("Cart item updated. id:".$cartItem->getId());

            // Cart section
            $cart = $entityManager->getRepository(Cart::class)->find($cartId);

            // Populate value data for cart
            $items = $cart->getItems();
            array_push($items, $cartItem->getId());
            $savings = $cart->getSavings() + $product->getRetailPrice();
            $total = $cart->getTotal() + $product->getSalePrice();

            // Set data to Cart
            $cart->setItems($items);
            $cart->setSavings($savings);
            $cart->setSavingsFormatted("$".number_format($savings/100, 2, ".", "."));
            $cart->setTotal($total);
            $cart->setTotalFormatted("$".number_format($total/100, 2, ".", "."));

            // Execute cart
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->json('Successfully updated the cart items', 200);
        } catch (\Throwable $e) {
            return $this->json($e->getMessage(), 400);
        }
    }
}
