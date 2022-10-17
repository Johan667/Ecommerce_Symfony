<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/adresses", name="app_account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="app_account_address_add")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();
        // Instancie une nouvelle adresse

        $formAddress = $this->createForm(AddressType::class, $address);

        $formAddress->handleRequest($request);
        // prise en charge du traitement du formulaire

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $address->setUser($this->getUser());

            $this->entityManager->persist($address);

            $this->entityManager->flush($address);

            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            } else {
                return $this->redirectToRoute('app_account_address');
            }
        }

        return $this->render('account/address_form.html.twig', [
            'formAddress' => $formAddress->createView(),
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="app_account_address_modify")
     */
    public function modify(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        $formAddress = $this->createForm(AddressType::class, $address);

        if (!$address || $address->getUser() != $this->getUser()) {
            // Si il n'a pas d'adresse on le redirige vers gestion adresse OU Si l'objet que je recupère je regarde si il est différent de l'user
            return $this->redirectToRoute('app_account_address');
        }

        $formAddress->handleRequest($request);

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $this->entityManager->flush($address);

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'formAddress' => $formAddress->createView(),
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="app_account_address_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush($address);
        }

        return $this->redirectToRoute('app_account_address');
    }
}
