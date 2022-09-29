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

        $formSearch = $this->createForm(SearchType::class);

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData();
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);

            // dd($search);
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
        $stars = $this->entityManager->getRepository(Star::class)->findVote($this->getUser()->getId(), $product->getId());

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        // Partie commentaire
        $comment = new Comments();
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setProducts($product);

            // On récupère le contenu du champ parentid
            $parentId = $commentForm->get('parentid')->getData();

            if ($parentId != null) {
                // On va chercher le commentaire correspondant
                $parent = $this->entityManager->getRepository(Comments::class)->find($parentId);
            }

            // On définit le parent
            $comment->setParent($parent ?? null);
            // Si c'est parent alors c'est $parent sinon c'est null

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->addFlash('notice', 'votre commentaire a bien été envoyé, il sera visible après modération');

            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            // Je passe ma variable à la vue
            'products' => $products,
            // products avec un 's' sera tous les bestseller
            'commentForm' => $commentForm->createView(),
            'stars' => $stars,
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
}
