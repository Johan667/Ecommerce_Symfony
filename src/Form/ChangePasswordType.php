<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'attr' => ['class' => 'input-full'],
                'label' => 'Mon adresse Email',
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'attr' => ['class' => 'input-full'],
                'label' => 'Mon prénom',
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'attr' => ['class' => 'input-full'],
                'label' => 'Mon nom',
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe actuel',
                ],
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'label' => 'Mon nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nouveau mot de passe',
                ],
                // 'constraints' => [
                //     new Regex([
                //         'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                //         'match' => true,
                //         'message' => 'Le mot de passe doit contenir : min 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                //     ]),
                // ],
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => ['label' => 'Mon nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez mon nouveau mot de passe'],
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre nouveau mot de passe',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
