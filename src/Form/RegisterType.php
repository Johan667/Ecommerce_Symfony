<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // Ajout des inputs dans le formulaire
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le prénom doit posséder 2 caractère minimum',
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit posséder 2 caractère minimum',
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => "L'email doit posséder 6 caractère minimum",
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre email',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Votre mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : min 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    ]),
                ],
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre mot de passe',
                ],
            ])
            ->add('CGU', CheckboxType::class, [
                'label' => "J'accepte les CGU *",
                'mapped' => false,
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
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
