<?php

namespace App\Form\Projet;

use App\Entity\Dto\Search;
use App\Form\Autocomplete\ClientFilterField;
use App\Form\Autocomplete\UserFilterField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'attr' => [
                    'placeholder' => 'Je cherche...'
                ],
                'required' => false,
            ])
            ->add('client', ClientFilterField::class, [])
            ->add('affectedTo', UserFilterField::class, [])
            ->add('from', DateType::class, [
                'label' => "Date de début",
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('to', DateType::class, [
                'label' => "Date de cloture",
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('limit', ChoiceType::class, [
                'label' => "Affichage de",
                'placeholder' => "Limite",
                'autocomplete' => true,
                'required' => false,
                'choices' => [
                    '100' => 100,
                    '200' => 200,
                    '300' => 300,
                    '400' => 400,
                    '500' => 500,
                    '600' => 600,
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
