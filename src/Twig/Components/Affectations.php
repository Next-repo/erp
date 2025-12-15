<?php

namespace App\Twig\Components;

use App\Entity\Affectation;
use App\Form\Affectation\AffectationType;
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
final class Affectations extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Affectation $affectation = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeService $anneeService
    ) {
        $this->affectation = new Affectation();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = AffectationType::class;

        if ($this->affectation->getId() !== null) {
            $formType = AffectationType::class;
        }

        return $this->createForm($formType, $this->affectation);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'affectations';
        $this->submitForm();
        $affectation = $this->form->getData();
        if ($affectation->getId() == null) {
            $affectation->setCreatedBy($this->getUser());
            $affectation->setAnnee($this->anneeService->getAnneeEncours());
            $this->entityManager->persist($affectation);
        }
        $this->entityManager->flush();
        $this->addFlash('success', "Les informations ont bien été enregistrées");
        return $this->redirectToRoute($roote);
    }
}
