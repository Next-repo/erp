<?php

namespace App\Controller\App;

use App\Entity\Dto\Search;
use App\Entity\Projet;
use App\Form\Projet\SearchType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/projets')]
final class ProjetController extends AbstractController
{
    public function __construct(private ProjetRepository $projetRepository)
    {
        
    }

    #[Route(name: 'projets', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $projet = new Projet();
        $clear = false;

        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }

        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);
        $projets = $this->projetRepository->search($search);

        return $this->render('app/projet/index.html.twig', [
            'clear' => $clear,
            'projets' => $projets,
            'projet' => $projet,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('app/projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        return $this->render('app/projet/edit.html.twig', [
            'projet' => $projet
        ]);
    }

    #[Route('/{id}', name: 'projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $projet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projets', [], Response::HTTP_SEE_OTHER);
    }
}
