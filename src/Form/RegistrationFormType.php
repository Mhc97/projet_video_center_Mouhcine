<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'You should agree to our terms.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
            ->add('firstname', TextType::class, [
             'label' => 'Prénom',
             'attr' => ['class' => 'form-control'],
             'constraints' => [
                new NotBlank(message: 'Le prénom ne peut pas être vide.'),
                new Length(min: 2, minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères.'),
    ],
             ])
    ->add('lastname', TextType::class, [
        'label' => 'Nom',
        'attr' => ['class' => 'form-control'],
        'constraints' => [
            new NotBlank(message: 'Le nom ne peut pas être vide.'),
            new Length(min: 2, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.'),
    ],
    ])
    ->add('email', EmailType::class, [
        'label' => 'Email',
        'attr' => ['class' => 'form-control'],
        'constraints' => [
            new NotBlank(message: 'L\'email ne peut pas être vide'),
            new Length(min: 5, minMessage: 'L\'email doit contenir au moins {{ limit }} caractères.'),
            new Email(message: 'Veuillez entrer une adresse email valide.'),
        ]
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
