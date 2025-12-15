<?php

namespace App\Twig\Components;

use App\Entity\Client;
use App\Entity\Projet;
use App\Form\Projet\ProjetType;
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
final class Projets extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Projet $projet = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeService $anneeService
    ) {
        $this->projet = new Projet();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = ProjetType::class;

        if ($this->projet->getId() !== null) {
            $formType = ProjetType::class;
        }

        return $this->createForm($formType, $this->projet);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'projets';
        $this->submitForm();
        $projet = $this->form->getData();
        $addClient = $this->form->get('addClient')->getData();

        if ($projet->getId() == null) {
            $projet->setCreatedBy($this->getUser());
            $projet->setAnnee($this->anneeService->getAnneeEncours());
            $this->entityManager->persist($projet);
        }
        $this->entityManager->flush();

        if ($addClient) {
            $client = new Client();
            $client->setNom($this->form->get('nom')->getData());
            $client->setType($this->form->get('type')->getData());
            $client->setEmail($this->form->get('email')->getData());
            $client->setTelephone($this->form->get('telephone')->getData());
            $this->entityManager->persist($client);
            $this->entityManager->flush();
            $projet->setClient($client);
            $this->entityManager->flush();
        }

        $this->addFlash('success', "Les informations du projet ont bien été enregistrés");
        return $this->redirectToRoute($roote);
    }
}
