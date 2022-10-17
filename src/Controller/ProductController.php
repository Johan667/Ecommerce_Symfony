<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\Product;
use App\Entity\Star;
use App\Form\CommentsType;
use App\Form\SearchType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        // J'utilise l'entityManager pour aller dans le repository produits et tous les trouver

        $search = new Search();
        // Nouvelle instance de l'objet Search

        $formSearch = $this->createForm(SearchType::class);
        // Je crée le formulaire SearchType lié à la classe Search

        $formSearch->handleRequest($request);
        // Prise en charge du traitement du formulaire

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            // Si le formulaire de recherche est soumis et qu'il est valide
            $search = $formSearch->getData();
            // On récupere les données
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            // On cherche les produits grace à la fonction findWithSearch du repository product
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            // Je passe ma variable à la vue
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByBestseller(1);
        if ($this->getUser()) {
            $stars = $this->entityManager->getRepository(Star::class)->findVote($this->getUser()->getId(), $product->getId());
        }
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        // Parti commentaire
        $comment = new Comments();
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setUsers($this->getUser());
            $comment->setProducts($product);
            $comment->setContent($commentForm->get('content')->getData());
            $comment->setActive(false);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé, il sera visible après modération');

            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }
        $commentsList = $this->entityManager->getRepository(Comments::class)->findBy(
            ['products' => $product->getId(), 'active' => true], ['created_at' => 'DESC']
        );

        return $this->render('product/show.html.twig', [
            'product' => $product,
            // Je passe ma variable à la vue
            'products' => $products,
            // products avec un 's' sera tous les bestseller
            'commentForm' => $commentForm->createView(),
            'stars' => isset($stars) ? $stars : null,
            // afficher la liste des commentaire
            'commentsList' => $commentsList,
        ]);
    }

    /**
     * @Route("/produit/categorie/{id}", name="produit_categorie")
     */
    public function AfficherCategorie($id, Category $category): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByCategory($id);

        return $this->render('product/category.html.twig', [
            'products' => $products,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/comment/delete/{id}", name="del_comment")
     */
    public function delComment(Comments $comments, Request $request)
    {
        $this->entityManager->remove($comments);
        $this->entityManager->flush();
        $this->addFlash('sucess', 'Votre commentaire a bien été supprimé !');

        return $this->redirect($request->headers->get('referer'));
    }
}
