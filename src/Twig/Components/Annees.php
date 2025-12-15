<?php

namespace App\Twig\Components;

use App\Entity\Annee;
use App\Form\Annee\AnneeForm;
use App\Form\Annee\EditForm;
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
final class Annees extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Annee $annee = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->annee = new Annee();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = AnneeForm::class;

        if ($this->annee->getId() !== null) {
            $formType = EditForm::class;
        }

        return $this->createForm($formType, $this->annee);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'annees';
        $this->submitForm();
        $annee = $this->form->getData();
        $this->entityManager->flush();
        $this->addFlash('success', "L'année a bien été mise à jour");
        return $this->redirectToRoute($roote);
    }
}
