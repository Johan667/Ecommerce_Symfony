<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updateState', 'En cours de préparation')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison En cours')->linkToCrudAction('updateDelivery');

        return $actions

        ->add('index', 'detail')
        ->add('detail', $updatePreparation)
        ->add('detail', $updateDelivery);
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        // Grace à Admin context on peux récuperer l'entity
        $order->setState(2);
        // On passe le statut préparation en cours
        $this->entityManager->flush();
        // On flush le statut

        $this->addFlash('notice', '<span style="color:grey;"><strong>La commande'.$order->getReference().' est bien en cours de préparation</strong></span>');
        $url = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', '<span style="color:green;"><strong>La commande'.$order->getReference().' est bien en cours de livraison</strong></span>');
        $url = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configuredCrud(Crud $crud)
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
        // Affichera les dernieres entrées en bdd (commande,user etc)
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Date Commande'),
            TextField::new('user.getFullname', 'Client'),
            TextEditorField::new('delivery', 'Adresse de livraison')->formatValue(function ($value) { return $value; })->onlyOnDetail(),
            // format Value pour interpréter le html des <br>
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('careerName', 'Transporteur'),
            MoneyField::new('carrerPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Statut commande')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Preparation en cours' => 2,
                'Livraison' => 3,
            ]),
            ArrayField::new('orderDetails', 'Produits acheté')->hideOnIndex(),
        ];
    }
}
