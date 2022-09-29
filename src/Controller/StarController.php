<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Star;
use App\Repository\StarRepository;
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
     * @ParamConverter("note")
     */
    public function Star(Product $product, StarRepository $st, int $note)
    {
        $vote = null;
        if ($this->getUser()) {
            $vote = $st->findVote($this->getUser()->getId(), $product->getId());
        }

        if ($note >= 1 && $note <= 5 && $this->getUser()) {
            // verifie si il y à une note et que l'utilisateur et connecte
            $star = new Star(); // Crée le vote
            $star->setProduct($product);
            $star->setUser($this->getUser());
            $star->setNote($note);

            $this->entityManager->persist($star);
            $this->entityManager->flush();
            $this->addFlash('sucess', 'Votre vote a bien été pris en compte !');
        } else {
            $this->addFlash('error', 'Votre vote est invalide !');
        }

        return $this->redirectToRoute('product', [
            'slug' => $product->getSlug(),
            'vote' => $vote,
        ]);
    }
}
