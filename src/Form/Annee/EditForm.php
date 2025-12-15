<?php

namespace App\Form\Annee;

use App\Entity\Annee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'help' => 'Ex: 2025',
                'attr' => [
                    'placeholder' => 'Ex: 2025'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('objectGeneral', TextareaType::class, [
                'label' => 'Objectif général',
                'attr' => [
                    'placeholder' => 'Objectif général'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annee::class,
        ]);
    }
}
