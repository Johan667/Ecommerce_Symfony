<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Star;
use Doctrine\ORM\EntityManagerInterface;

class StarService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function StarAverage(int $id)
    {
        $average = 0;

        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $stars = $this->entityManager->getRepository(Star::class)->find($id);
    }
}
