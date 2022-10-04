<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="app_account_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        $modifPassword = $this->createForm(ChangePasswordType::class);

        $modifPassword->handleRequest($request);

        if ($modifPassword->isSubmitted() && $modifPassword->isValid()) {
            $old_pwd = $modifPassword->get('old_password')->getData();
            // On veux verifier ici que l'ancien mot de passe coresspond à celui de la bdd.
            if ($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $modifPassword->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->flush($password);
                $notification = 'Votre mot de passe à bien été mise à jours';
            }
        }

        return $this->render('account/password.html.twig', [
            'modifPassword' => $modifPassword->createView(),
            'notification' => $notification,
        ]);
    }
}
