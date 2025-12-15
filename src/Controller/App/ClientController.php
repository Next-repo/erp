<?php

namespace App\Controller\App;

use App\Entity\Client;
use App\Entity\Dto\Search;
use App\Form\Client\SearchType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients')]
final class ClientController extends AbstractController
{
    public function __construct(private ClientRepository $clientRepository)
    {
       
    }

    #[Route(name: 'clients', methods: ['GET'])]
    public function listing(Request $request): Response
    {
        $client = new Client();
        $clear = false;

        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }

        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);
        $clients = $this->clientRepository->search($search);

        return $this->render('app/client/index.html.twig', [
            'clients' => $clients,
            'clear' => $clear,
            'searchForm' => $searchForm,
            'client' => $client
        ]);
    }

    #[Route('/details/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('app/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/edti/{id}', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Client $client): Response
    {
        return $this->render('app/client/edit.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/delete/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clients', [], Response::HTTP_SEE_OTHER);
    }
}
