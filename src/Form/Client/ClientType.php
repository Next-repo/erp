<?php

namespace App\Form\Client;

use App\Entity\Client;
use App\Enum\TypeClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire.',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => "Type de client",
                'placeholder' => "Sélectionner un client...",
                'autocomplete' => true,
                'choices' => TypeClient::choices(),
                'choice_label' => fn(TypeClient $choice) => ucfirst($choice->value),
                'choice_value' => fn(?TypeClient $choice) => $choice?->value,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez saisir un email valide.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                ],
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numéro de téléphone est obligatoire.',
                    ]),
                    new Regex([
                        'pattern' => '/^\+?\d{9,15}$/',
                        'message' => 'Veuillez saisir un numéro de téléphone valide.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
