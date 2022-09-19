<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
            [
                'label'=> 'Quel nom souhaitez vous donner à votre adresse ?',
                'attr'=>[
                    'placeholder'=>'Maison, Bureau, Voisin',
                ]
            ])
            ->add('firstname',TextType::class,
            [
                'label'=> 'Mon prénom',
                'attr'=>[
                    'placeholder'=>'Saisir mon prénom',
                ]
            ])
            ->add('lastname',TextType::class,
            [
                'label'=> 'Mon nom',
                'attr'=>[
                    'placeholder'=>'Saisir mon nom',
                ]
            ])
            ->add('company',TextType::class,
            [
                'label'=> 'Ma société ',
                'attr'=>[
                    'placeholder'=>'(optionnel)',
                ]
            ])
            ->add('address',TextType::class,
            [
                'label'=> 'Mon adresse',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'6 Avenue des Champs Elysée',
                ]
            ])
            ->add('postal',TextType::class,
            [
                'label'=> 'Code postal',
                'attr'=>[
                    'placeholder'=>'Saisir mon code postal',
                ]
            ])
            ->add('city',TextType::class,
            [
                'label'=> 'Ville',
                'attr'=>[
                    'placeholder'=>'Saisir ma ville',
                ]
            ])
            ->add('country', CountryType::class,
            [
                'label'=> 'Pays',
                'attr'=>[
                    'placeholder'=>'Saisir mon pays',
                ]
            ])
            ->add('phone',TelType::class,
            [
                'label'=> 'Numéro de téléphone',
                'attr'=>[
                    'placeholder'=>'Saisir mon numéro de téléphone',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Gérer mon adresse',
                'attr'=>[
                    'class'=>'btn-block btn-info',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}