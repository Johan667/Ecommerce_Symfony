<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Star;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/etoile/{slug}/{note}", name="app_star")
     *
     * @ParamConverter("product", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("note", options={"mapping": {"note" : "note"}})
     * Cette technique permet d'utiliser les tables d'une base de donnée comme des objets
     */
    public function Star(Product $product, int $note)
    {
        $star = new Star(); // Crée le vote
        $star->setProduct($product); // Parametre l'id du produit
        $star->setUser($this->getUser()); // Parametre l'user
        $star->setNote($note); // Parametre la note

        $this->entityManager->persist($star);
        $this->entityManager->flush();

        return $this->redirectToRoute('product', [
            'slug' => $product->getSlug(),
            'note' => $note,
        ]);
    }
}
