<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="app_cart")
     */
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFullCart(),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('app_cart');
        // Redirige vers le récapitulatif du panier
    }

    /**
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
        // Redirige vers le récapitulatif du panier
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_my_product")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('app_cart');
        // Redirige vers le récapitulatif du panier
    }

    /**
     * @Route("/cart/deletequantity/{id}", name="delete_quantity_product")
     */
    public function deleteQuantity(Cart $cart, $id): Response
    {
        $cart->deleteQuantity($id);

        return $this->redirectToRoute('app_cart');
    }
}
