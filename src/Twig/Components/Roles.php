<?php

namespace App\Twig\Components;

use App\Entity\Role;
use App\Form\Affectation\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Roles extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Role $role = null;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->role = new Role();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = RoleType::class;

        if ($this->role->getId() !== null) {
            $formType = RoleType::class;
        }

        return $this->createForm($formType, $this->role);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'roles';
        $this->submitForm();
        $role = $this->form->getData();
        if ($role->getId() == null) {
            $role->setCreatedBy($this->getUser());
            $this->entityManager->persist($role);
        }
        $this->entityManager->flush();
        $this->addFlash('success', "Les informations ont bien été enregistrées");
        return $this->redirectToRoute($roote);
    }
}
