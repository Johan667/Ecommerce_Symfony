<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/wishlist", name="app_wishlist")
     */
    public function index(): Response
    {
        // dd($this->getUser()->getWishlist());

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $this->getUser()->getWishlist(),
            // on recupere les produits qui ont ete enregistré dans la collection par l'utilisateur
        ]);
    }

    /**
     * @Route("/wishlist/add/{idProduct}", name="add_wishlist")
     * @ParamConverter("product", options= {"mapping": {"idProduct":"id.product"}})
     */
    public function addWishlist(Product $product, Request $request, Wishlist $wishlist): Response
    {
        $wishlist->addProduct($product);
        $wishlist->setUser($this->getUser());

        // ajoute le produit voulu dans la relation ManyToMany(wishlist)

        $this->entityManager->flush();
        // sauvegarde

        return $this->redirect($request->headers->get('referer'));
        // Renvoi sur la même page
    }

    /**
     * @Route("/wishlist/del/{idProduct}", name="del_wishlist")
     * @ParamConverter("product", options= {"mapping": {"idProduct":"id.product"}})
     */
    public function delWishlist(Product $product, Request $request, Wishlist $wishlist): Response
    {
        $wishlist->removeProduct($product);
        // supprime le produit  de la relation ManyToMany(wishlist)

        $this->entityManager->flush();
        // sauvegarde

        return $this->redirect($request->headers->get('referer'));
        // Renvoi sur la même page
    }
}
