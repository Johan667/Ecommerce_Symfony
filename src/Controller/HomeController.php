<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
    public function index(MailerInterface $mailer, Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByNew(1);
        $bestseller = $this->entityManager->getRepository(Product::class)->findByBestseller(1);
        $promotion = $this->entityManager->getRepository(Product::class)->findByPromotion(1);

        $form = $this->createForm(NewsletterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = (new TemplatedEmail())
            ->from($form->get('email')->getData())
            ->to(new Address('johan.kasri@icloud.com', 'Admin RaveShop'))
            ->subject('RaveShop - Newsletter')
            ->htmlTemplate('email/newsletter.html.twig')
            ;
            $mailer->send($mail);
            $this->addFlash('notice', 'Votre demande à bien été prise en compte nous y repondrons au plus tard 48 heures après votre demande ! ');
        }

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'bestseller' => $bestseller,
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }
}
