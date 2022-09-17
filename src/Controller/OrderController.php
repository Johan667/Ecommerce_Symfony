<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="app_order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_address_add');
        }
        $formCommande = $this->createForm(OrderType::class, null, [
        'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
        'formCommande' => $formCommande->createView(),
        'cart' => $cart->getFullCart(),
    ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $formCommande = $this->createForm(OrderType::class, null, [
        'user' => $this->getUser(),
        ]);
        $formCommande->handleRequest($request);

        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            $date = new DateTimeImmutable();
            $career = $formCommande->get('career')->getData();
            $delivery = $formCommande->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }

            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().' - '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();

            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCareerName($career->getName());
            $order->setCarrerPrice($career->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);
            // Status 0 = Non validé
            $this->entityManager->persist($order);

            // Enregistrer mes produits dans l'entité OrderDetails

            foreach ($cart->getFullCart() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);
            }
            $this->entityManager->flush();

            // retourne la route uniquement si le formulaire est soumis
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFullCart(),
                'career' => $career,
                'delivery' => $delivery_content,
                'reference' => $order->getReference(),
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
