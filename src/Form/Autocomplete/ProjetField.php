<?php

namespace App\Form\Autocomplete;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ProjetField extends AbstractType
{
    //public function __construct(private UserService $userService) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Projet::class,
            'label' => 'Projet',
            'placeholder' => 'Sélectionner un projet...',
            'query_builder' => function (ProjetRepository $projetRepository) {
                return $this->getProjets($projetRepository);
            },
            'constraints' => [
                new NotBlank()
            ]
            // 'choice_label' => 'name',

            // choose which fields to use in the search
            // if not passed, *all* fields are used
            // 'searchable_fields' => ['name'],

            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getProjets(ProjetRepository $projetRepository)
    {
        $query = $projetRepository->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC');

        return $query;
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
