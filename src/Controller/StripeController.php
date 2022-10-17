<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/stripe/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        $products_for_stripe = [];
        // J'instancie un tableau vide pour y entrer plus tard toutes les infos dont j'aurais besoin dans la create session

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        // Domaine local pour le moment pour afficher success ou error

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        // Trouve une commande par la référence

        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }
        // Si il n'y à pas de commande error

        foreach ($order->getOrderDetails()->getValues() as $product) {
            // Pour chaques commandes je récupères les valeurs du détails de la commande par article
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            // Je recupères le repository Product, je récupere un produit par son nom
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    // devise
                    'unit_amount' => $product->getPrice(),
                    // prix unitaire
                    'product_data' => [
                      'name' => $product->getProduct(),
                      'images' => [$YOUR_DOMAIN.'/uploads/'.$product_object->getIllustration()],
                  ],
              ],
                'quantity' => $product->getQuantity(),
            ];
            // On rempli le tableau avec les valeurs pour chaques articles  (prix, nom, image, quantité)
        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrerPrice(),
                'product_data' => [
                  'name' => $order->getCareerName(),
                  'images' => [$YOUR_DOMAIN],
              ],
          ],
            'quantity' => 1,
        ];
        // On rempli le tableau avec les valeurs de livraison choisi  (prix, nom, quantité = toujours à un car un seul transporteur )

        // Système de paiement Stripe :
        Stripe::setApiKey('sk_test_51L6amqAgDjI611jf49n3RURuEVn6KbawPxt0CKby4wsENM9plWmKeqkq7Cm3Sl1W4JcvjewbvVCBrwyA5knu6b2500QdV5lalL');
        // J'initialise ma clef API

        $checkout_session = Session::create([
                        'customer_email' => $this->getUser()->getEmail(),
                        // J'identifie le client par son email
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            $products_for_stripe,
                          ],
                        // Je passe à stripe toutes les valeurs récupérer plus haut dans mon foreach
                        'mode' => 'payment',
                        'success_url' => $YOUR_DOMAIN.'/commande/merci/{CHECKOUT_SESSION_ID}',
                        'cancel_url' => $YOUR_DOMAIN.'/commande/erreur/{CHECKOUT_SESSION_ID}',
                        'automatic_tax' => [
                          'enabled' => false,
                        ],
                      ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $this->redirect($checkout_session->url);
    }
}
