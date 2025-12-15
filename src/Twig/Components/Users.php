<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\User\UserType;
use App\Service\AnneeService;
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
final class Users extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?User $user = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeService $anneeService
    ) {
        $this->user = new User();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = UserType::class;

        if ($this->user->getId() !== null) {
            $formType = UserType::class;
        }

        return $this->createForm($formType, $this->user);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'users';
        $this->submitForm();
        $user = $this->form->getData();
        if ($user->getId() == null) {
            $user->setCreatedBy($this->getUser());
            $user->setAnnee($this->anneeService->getAnneeEncours());
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        $this->addFlash('success', "Les informations du user ont bien été enregistrés");
        return $this->redirectToRoute($roote);
    }
}
