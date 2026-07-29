<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
                $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
            ])
             ->add('videoLink', TextType::class, [
                'label' => 'Lien vidéo',
                'attr' => ['class' => 'form-control'],
                'help' => 'Exemple: https://www.youtube.com/watch?v=lcZDWo6hiuI',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5],
            ])
            ->add('premiumVideo', CheckboxType::class, [
                'label' => 'Vidéo premium (réservée aux utilisateurs vérifiés)',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults([
        'data_class' => Video::class,
       ]);
    }
}

