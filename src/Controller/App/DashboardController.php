<?php

namespace App\Controller\App;

use App\Repository\ClientRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use App\Service\AnneeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/dashboard')]
final class DashboardController extends AbstractController
{
    public function __construct(
        private ProjetRepository $projetRepository,
        private ClientRepository $clientRepository,
        private UserRepository $userRepository,
        private AnneeService $anneeService
    ) {

    }

    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $totalProjets = count($this->projetRepository->findBy(['annee' => $this->anneeService->getCurrenteAnnee()]));
        $totalClients = count($this->clientRepository->findBy(['annee' => $this->anneeService->getCurrenteAnnee()]));
        $totalUsers = count($this->userRepository->findAll());
        $projets = $this->projetRepository->findBy(['annee' => $this->anneeService->getAnneeEncours()], ['created' => 'DESC']);

        return $this->render('app/dashboard/index.html.twig', [
            'totalProjets' => $totalProjets,
            'totalClients' => $totalClients,
            'totalUsers' => $totalUsers,
            'projets' => $projets,
        ]);
    }
}
