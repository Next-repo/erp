<?php

namespace App\Controller\App;

use App\Entity\Annee;
use App\Entity\Dto\Search;
use App\Form\Admin\SearchType;
use App\Repository\AnneeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/app/annees')]
#[IsGranted('ROLE_ACCESS_CONFIGS')]
final class AnneeController extends AbstractController
{
    #[Route(name: 'annees', methods: ['GET'])]
    public function index(AnneeRepository $anneeRepository, Request $request): Response
    {
        $clear = false;

        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }

        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);
        $annees = $anneeRepository->search($search);

        return $this->render('app/annee/index.html.twig', [
            'annees' => $annees,
            'clear' => $clear,
            'searchForm' => $searchForm
        ]);
    }

    #[Route('/{id}', name: 'annee_show', methods: ['GET'])]
    public function show(Annee $annee): Response
    {
        return $this->render('app/annee/show.html.twig', [
            'annee' => $annee,
        ]);
    }

    #[Route('/{id}/edit', name: 'annee_edit', methods: ['GET', 'POST'])]
    public function edit(Annee $annee): Response
    {
        return $this->render('app/annee/edit.html.twig', [
            'annee' => $annee,
        ]);
    }

    #[Route('/{id}', name: 'annee_delete', methods: ['POST'])]
    public function delete(Request $request, Annee $annee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annee->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($annee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annees', [], Response::HTTP_SEE_OTHER);
    }
}
