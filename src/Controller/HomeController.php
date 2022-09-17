<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByNew(1);
        $bestseller = $this->entityManager->getRepository(Product::class)->findByBestseller(1);
        $promotion = $this->entityManager->getRepository(Product::class)->findByPromotion(1);

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'bestseller' => $bestseller,
            'promotion' => $promotion,
        ]);
    }
}
