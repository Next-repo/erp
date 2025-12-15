<?php

namespace App\Controller\App;

use App\Entity\Dto\Search;
use App\Entity\User;
use App\Form\User\SearchType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/users')]
final class UserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
       
    }
    
    #[Route(name: 'users', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $user = new User();
        $clear = false;

        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }

        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);
        $users = $this->userRepository->search($search);

        return $this->render('app/user/index.html.twig', [
            'users' => $users,
            'clear' => $clear,
            'user' => $user,
            'searchForm' => $searchForm
        ]);
    }

    #[Route('/details/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('app/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user): Response
    {
        return $this->render('app/user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
    }
}
