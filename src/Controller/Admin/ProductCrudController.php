<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    // ici on configure les input de création produit de la partie admin
    {
        return [
            TextField::new('name'),
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads')
            // Upload les photo dans un dossier
            ->setFormTypeOptions(['data_class' => null])
            ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            BooleanField::new('bestseller'),
            BooleanField::new('new'),
            BooleanField::new('promotion'),
            MoneyField::new('price')
            ->setCurrency('EUR'),
            AssociationField::new('category'),
            SlugField::new('slug')
            ->setTargetFieldName('name'),
            // Génère un slug en fonction du nom
        ];
    }
}
