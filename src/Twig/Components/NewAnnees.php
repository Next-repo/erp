<?php

namespace App\Twig\Components;

use App\Entity\Annee;
use App\Form\Annee\AnneeForm;
use App\Repository\AnneeRepository;
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
final class NewAnnees extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Annee $annee = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeRepository $anneeRepository,
        private AnneeService $anneeService
    ) {
        $this->annee = new Annee();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = AnneeForm::class;
        return $this->createForm($formType, $this->annee);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'dashboard';
        $this->submitForm();

        $annee = $this->form->getData();
        //$periodes = $this->form->get('periodes')->getData();
        $startAt = $annee->getStartAt();
        $endAt = $this->anneeService->calculateDateEnd($startAt);

        // Sauvegarde

        // Enregistrer l'année si elle est nouvelle pour avoir un ID
        if ($annee->getId() === null) {

            // 1. Mettre à jour les autres années en cours (sauf celle qu'on vient de créer)
            $currentAnnees = $this->anneeRepository->findBy([
                'statut' => 'En cours'
            ]);

            foreach ($currentAnnees as $currentAnnee) {
                if ($currentAnnee->getId() !== $annee->getId()) {
                    $currentAnnee->setStatut('Passer');
                    $currentAnnee->setCurrente(false);
                }
            }
            $this->entityManager->flush();
            $annee->setCreatedBy($this->getUser());
            $annee->setCurrente(true);
            $annee->setStatut('En cours');
            $annee->setEndAt($endAt);
            $this->entityManager->persist($annee);
            $this->entityManager->flush(); // Pour avoir l'ID dans la BDD
        }

        $this->entityManager->flush();

        $this->addFlash('success', "L'année a bien été enregistrer");
        return $this->redirectToRoute($roote);
    }
}
