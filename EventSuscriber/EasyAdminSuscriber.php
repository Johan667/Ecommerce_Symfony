<?php

// Surchage les classes d'EasyAdmin

namespace App\EventSuscriber;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->AppKernel = $appKernel;
        // Contient toutes les informations techniques de mon application Symfony
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            // Avant de faire le persist sur mon entité, set l'illustration

             BeforeEntityUpdatedEvent::class => ['setIllustration'],
            // Avant de faire la mise à jour
        ];
    }

    public function uploadIllustration(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $tmp_name = $entity->getIllustration();

        $extension = pathinfo($_FILES['Product']['name']['illustration'], PATHINFO_EXTENSION);
        // pathinfo permet d'aller chercher l'extension d'un fichier dans ce cas
        // $_FILES contient un tableau avec produit, j'accède à la propriété name

        $filename = uniqid();
        // Génère un id unique

        $project_directory = $this->AppKernel->getProjectDir();
        // Renvoie le chemin complet

        move_uploaded_file($tmp_name, $project_directory.'/public/uploads'.$filename.'.'.$extension);
        // Change de repertoire le fichier uploadé

        $entity->setIllustration($filename.'.'.$extension);
        // Stock le nom et l'extension en bdd
    }
}
