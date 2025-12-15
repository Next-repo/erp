<?php

namespace App\Form\Annee;

use App\Entity\Annee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnneeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Désignation',
                'help' => 'Ex: 2025',
                'attr' => [
                    'placeholder' => 'Ex: 2025'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('startAt', DateType::class, [
                'label' => "Date de début",
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annee::class,
        ]);
    }
}
