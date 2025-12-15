<?php

namespace App\Form\Autocomplete;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserField extends AbstractType
{
    //public function __construct(private UserService $userService) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => User::class,
            'label' => 'Membre',
            'placeholder' => 'Sélectionner un membre...',
            'query_builder' => function (UserRepository $userRepository) {
                return $this->getUsers($userRepository);
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

    public function getUsers(UserRepository $userRepository)
    {
        $query = $userRepository->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');

        return $query;
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
