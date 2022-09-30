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
     */
    public function Star(Product $product, int $note)
    {
        $star = new Star(); // Crée le vote
        $star->setProduct($product);
        $star->setUser($this->getUser());
        $star->setNote($note);

        $this->entityManager->persist($star);
        $this->entityManager->flush();

        $this->addFlash('sucess', 'Votre vote a bien été pris en compte !');

        return $this->redirectToRoute('product', [
            'slug' => $product->getSlug(),
            'note' => $note,
        ]);
    }
}
