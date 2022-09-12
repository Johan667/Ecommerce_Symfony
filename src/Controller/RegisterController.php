<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    // Injection de dépendance -> On demande à la fonction index d'embarquer le/les objet(s) donné en paramètre
    {
        $user = new User();
        // Instancie un nouvelle utilisateur
        $registerForm = $this->createForm(RegisterType::class, $user);
        // Instancie mon formulaire dans la variable, parametre 1 classe de mon formulaire, 2 les datas de mon objet.

        $registerForm->handleRequest($request);
        // On demande au formulaire d'être à l'écoute de l'objet request pour la traité

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            // Si mon formulaire est soumis et qu'il est valide par rapport au contraintes
            $user = $registerForm->getData();
            // Rappel l'objet user et injecte toutes les donnée récupérer en formulaire
            $user->setPassword(
                $encoder->hashPassword(
                        $user,
                        $registerForm->get('password')->getData())
                );
            // On hash le mot de passe grace à UserPasswordInterface
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
        // persit = fige la donnée
        // flush = execute, et enregistre en bdd

        return $this->render('register/index.html.twig', [
            // Je passe ma variable en template sous forme de tableau
            'registerForm' => $registerForm->createView(),
            // On à crée le formulaire l.20 , on crée la vue ici
        ]);
    }
}
