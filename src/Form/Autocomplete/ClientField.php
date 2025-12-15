<?php

namespace App\Form\Autocomplete;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ClientField extends AbstractType
{
    //public function __construct(private UserService $userService) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Client::class,
            'label' => 'Client',
            'placeholder' => 'Sélectionner un client...',
            'query_builder' => function (ClientRepository $clientRepository) {
                return $this->getClients($clientRepository);
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

    public function getClients(ClientRepository $clientRepository)
    {
        $query = $clientRepository->createQueryBuilder('c')
            /*->andWhere('m.organisation = :organisation')
            ->setParameter('organisation', $this->userService->getOrganisation())*/
            ->orderBy('c.nom', 'ASC');

        /*if ($this->userService->isRole('ROLE_ADMIN')) {
            $query = $clientRepository->createQueryBuilder('c')
                ->orderBy('m.nom', 'ASC');
        }*/

        return $query;
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
