<?php

namespace App\Twig\Components;

use App\Entity\Client;
use App\Form\Client\ClientType;
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
final class Clients extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true, fieldName: 'formData')]
    public ?Client $client = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeService $anneeService
    ) {
        $this->client = new Client();
    }

    protected function instantiateForm(): FormInterface
    {
        $formType = ClientType::class;

        if ($this->client->getId() !== null) {
            $formType = ClientType::class;
        }

        return $this->createForm($formType, $this->client);
    }

    #[LiveAction]
    public function save(): Response
    {
        $roote = 'clients';
        $this->submitForm();
        $client = $this->form->getData();
        if ($client->getId() == null) {
            $client->setCreatedBy($this->getUser());
            $client->setAnnee($this->anneeService->getAnneeEncours());
            $this->entityManager->persist($client);
        }
        $this->entityManager->flush();
        $this->addFlash('success', "Les informations du client ont bien été enregistrées");
        return $this->redirectToRoute($roote);
    }
}
