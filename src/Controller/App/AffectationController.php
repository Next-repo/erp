<?php

namespace App\Controller\App;

use App\Entity\Affectation;
use App\Entity\Dto\Search;
use App\Form\Admin\SearchType;
use App\Repository\AffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/affectations')]
final class AffectationController extends AbstractController
{
    public function __construct(private AffectationRepository $affectationRepository)
    {
    }

    #[Route(name: 'affectations', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $affectation = new Affectation();
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }
        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest( $request);
        $affectations = $this->affectationRepository->search($search);

        return $this->render('app/affectation/index.html.twig', [
            'affectations' => $affectations,
            'clear' => $clear,
            'affectation' => $affectation,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/details/{id}', name: 'affectation_show', methods: ['GET'])]
    public function show(Affectation $affectation): Response
    {
        return $this->render('app/affectation/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    #[Route('/edit/{id}', name: 'affectation_edit', methods: ['GET', 'POST'])]
    public function edit(Affectation $affectation): Response
    {
        return $this->render('app/affectation/edit.html.twig', [
            'affectation' => $affectation
        ]);
    }

    #[Route('/delete/{id}', name: 'affectation_delete', methods: ['POST'])]
    public function delete(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $affectation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($affectation);
            $entityManager->flush();
            $this->addFlash('success', "L'affectation a bien été supprimée");
        }

        return $this->redirectToRoute('affectations', [], Response::HTTP_SEE_OTHER);
    }
}
