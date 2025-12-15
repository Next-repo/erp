<?php

namespace App\Form\Autocomplete;

use App\Entity\Role;
use App\Repository\RoleRepository;
use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class RoleManyField extends AbstractType
{
    public function __construct(private UserService $userService) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Role::class,
            'label' => 'Rôle(s)',
            'multiple' => true,
            'attr' => [
                'placeholder' => 'Sélectionner des rôles...',
            ],
            'query_builder' => function (RoleRepository $roleRepository) {
                return $this->getMembreRoles($roleRepository);
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

    public function getMembreRoles(RoleRepository $roleRepository)
    {
        $query = $roleRepository->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC');

        return $query;
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
