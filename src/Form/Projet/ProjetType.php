<?php

namespace App\Form\Projet;

use App\Entity\Projet;
use App\Enum\StatutProjet;
use App\Enum\TypeClient;
use App\Enum\TypePrestation;
use App\Form\Autocomplete\ClientField;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);

        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du projet",
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
                    'placeholder' => 'Entrez le nom du projet',
                ],
            ])
            ->add('code', TextType::class, [
                'label' => "Code du projet",
                'attr' => [
                    'placeholder' => 'Entrez le code du projet',
                ],
                'required' => false
            ])
            ->add('budget', MoneyType::class, [
                'label' => 'Budget estimatif',
                'attr' => ['placeholder' => 'Budget estimatif...'],
                'required' => false,
                'constraints' => [
                    new Positive(['message' => 'Le montant doit être positif']),
                ],
            ])
            ->add('typePrestation', ChoiceType::class, [
                'label' => "Type de prestation",
                'placeholder' => "Type de prestation...",
                'autocomplete' => true,
                'choices' => TypePrestation::choices(),
                'choice_label' => fn(TypePrestation $choice) => ucfirst($choice->value),
                'choice_value' => fn(?TypePrestation $choice) => $choice?->value,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionner un statut...",
                'autocomplete' => true,
                'choices' => StatutProjet::choices(),
                'choice_label' => fn(StatutProjet $choice) => ucfirst($choice->value),
                'choice_value' => fn(?StatutProjet $choice) => $choice?->value,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('startAt', DateType::class, [
                'label' => "Date de début",
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->addDependent('endAt', 'startAt', function (DependentField $field, ?DateTime $date) {
                if ($date) {
                    $field->add(DateType::class, [
                        'label' => "Date de cloture",
                        'widget' => 'single_text',
                        'attr' => [
                            'min' => $date->format('Y-m-d'),
                        ],
                        'constraints' => [
                            new NotBlank(),
                        ]
                    ]);
                }
            })
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => [
                    'placeholder' => 'Entrez la description du projet',
                ],
                'required' => false
            ])
            ->add('addClient', CheckboxType::class, [
                'label' => "Ajouter un client",
                'mapped' => false,
                'required' => false
            ])
            ->addDependent('nom', 'addClient', function (DependentField $field, ?bool $add) {
                if ($add) {
                    $field->add(TextType::class, [
                        'mapped' => false,
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
                    ]);
                }
            })
            ->addDependent('type', 'addClient', function (DependentField $field, ?bool $add) {
                if ($add) {
                    $field->add(ChoiceType::class, [
                        'mapped' => false,
                        'label' => "Type de client",
                        'placeholder' => "Sélectionner un client...",
                        'autocomplete' => true,
                        'choices' => TypeClient::choices(),
                        'choice_label' => fn(TypeClient $choice) => ucfirst($choice->value),
                        'choice_value' => fn(?TypeClient $choice) => $choice?->value,
                        'constraints' => [
                            new NotBlank(),
                        ]
                    ]);
                }
            })
            ->addDependent('email', 'addClient', function (DependentField $field, ?bool $add) {
                if ($add) {
                    $field->add(EmailType::class, [
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new Email([
                                'message' => 'Veuillez saisir un email valide.',
                            ]),
                        ],
                        'attr' => [
                            'placeholder' => 'Entrez votre email',
                        ],
                    ]);
                }
            })
            ->addDependent('telephone', 'addClient', function (DependentField $field, ?bool $add) {
                if ($add) {
                    $field->add(TextType::class, [
                        'mapped' => false,
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
            })
            ->addDependent('client', 'addClient', function (DependentField $field, ?bool $add) {
                if ($add == null) {
                    $field->add(ClientField::class, []);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
