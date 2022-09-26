<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function getFullCart()
    {
        $cartComplete = [];
        if ($this->get()) {
            // $this -> accède à la méthode get dans la meme classe
            foreach ($this->get() as $id => $quantity) {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                if (!$product_object) {
                    $this->delete($id);
                    continue;
                }
                // Si le produit n'existe pas supprime ce que l'utilisateur  dans l'URL (id inconnu)

                $cartComplete[] = [
                'product' => $product_object,
                'quantity' => $quantity,
            ];
            }
        }

        return $cartComplete;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            // Si dans le panier il y à déja un produit
            ++$cart[$id];
        // On ajoute en quantité
        } else {
            $cart[$id] = 1;
            // Sinon vu que c'est vide on set la quantité à 1
        }

        // Set la Session 'cart'
        $this->session->set('cart', $cart);
    }

    public function get() // Obtenir le panier
    {
        return $this->session->get('cart');
    }

    public function remove() // Supprime le panier
    {
        return $this->session->remove('cart');
    }

    public function delete($id) // Supprime un article
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        // Supprime le produit
        return $this->session->set('cart', $cart);
        // Retourne le panier avec sa nouvelle valeur
    }

    public function deleteQuantity($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] > 1) {
            // Verifier si la quantité du produit n'est pas = à 1 sinon supprimer
            --$cart[$id];
        } else {
            unset($cart[$id]);
            // supprimer le produit
        }

        return $this->session->set('cart', $cart);
    }
}
