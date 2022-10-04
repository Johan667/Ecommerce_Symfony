<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/mes-commandes", name="app_account_order")
     */
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        // findSuccessOrders() -> Fonction personnalisée pour afficher les commandes payée les plus récente
        return $this->render('account/order.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/compte/mes-commandes/{id}", name="app_account_order_show")
     */
    public function show($id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneById($id);

        $orderDetails = $this->entityManager->getRepository(OrderDetails::class)->findOrderId($id);
        if (!$order || $order->getUser() != $this->getUser()) {
            // Si il n'y à pas de commande ou que la commande de l'utilisateur courant est différente à celle ci
            return $this->redirectToRoute('app_account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }
}

// * @ParamConverter("produit", options={"mapping": {"slug" : "slug"}})
