<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Entity\User;
use App\Repository\AnneeRepository;
use App\Service\AnneeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class IndexController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnneeRepository $anneeRepository,
        private AnneeService $anneeService
    ) {}

    #[Route('/change-annee/{id}', name: 'change_annee')]
    #[IsGranted('ROLE_USER')]
    public function changeAnnee(
        Annee $annee,
        Request $request
    ): Response {

        $annees = $this->anneeRepository->findAll();

        $ids = array_map(fn($a) => $a->getId(), $annees);

        if (in_array($annee->getId(), $ids, true)) {
            $this->anneeService->setAnneeInSession($annee);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/change-mode/{mode}', name: 'change_mode')]
    #[IsGranted('ROLE_USER')]
    public function changeColor(String $mode, Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();
        $modes = ['light', 'dark'];
        if (in_array($mode, $modes)) {
            $user->setMode($mode);
            $this->entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

}
