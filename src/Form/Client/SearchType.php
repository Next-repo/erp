<?php

namespace App\Form\Client;

use App\Entity\Dto\Search;
use App\Enum\TypeClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('typeClient', ChoiceType::class, [
                'label' => "Type de client",
                'placeholder' => "Sélectionner un client...",
                'autocomplete' => true,
                'choices' => TypeClient::choices(),
                'choice_label' => fn(TypeClient $choice) => ucfirst($choice->value),
                'choice_value' => fn(?TypeClient $choice) => $choice?->value,
                'required' => false
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
