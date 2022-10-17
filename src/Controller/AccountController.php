<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/compte/supprimer", name="delete_account")
     */
    public function deleteAcount()
    {
        $user = $this->getUser();
        $newSession = new Session();
        $newSession->invalidate();
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
