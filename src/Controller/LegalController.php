<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(MailerInterface $mailer, Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = (new Email())
            ->from($form->get('email')->getData())
            ->to(new Address('johan.kasri@icloud.com', 'Admin RaveShop'))
            ->subject($form->get('objet')->getData())
            ->text($form->get('message')->getData())
            ;
            $mailer->send($mail);
            $this->addFlash('notice', 'Votre demande à bien été prise en compte nous y repondrons au plus tard 48 heures après votre demande ! ');
        }

        return $this->render('legals/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confidentialite", name="confidentialite")
     */
    public function PolitiqueCofidentialite(): Response
    {
        return $this->render('legals/index.html.twig', [
        ]);
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu(): Response
    {
        return $this->render('legals/cgu.html.twig', [
        ]);
    }

    /**
     * @Route("/mentions-legal", name="mentions")
     */
    public function Mention(): Response
    {
        return $this->render('legals/mentions.html.twig', [
        ]);
    }

    /**
     * @Route("/cookie", name="cookie")
     */
    public function Cookie(): Response
    {
        return $this->render('legals/cookie.html.twig', [
        ]);
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function Cgv(): Response
    {
        return $this->render('legals/cgv.html.twig', [
        ]);
    }
}
