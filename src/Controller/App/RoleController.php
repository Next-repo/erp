<?php

namespace App\Controller\App;

use App\Entity\Dto\Search;
use App\Entity\Role;
use App\Form\Admin\SearchType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/roles')]
final class RoleController extends AbstractController
{
    public function __construct(private RoleRepository $roleRepository)
    {
        
    }

    #[Route(name: 'roles', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $role = new Role();
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0) {
            $clear = true;
        }
        $search = new Search();
        $search->page = $request->get('page', 1);
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest( $request);
        $roles = $this->roleRepository->search($search);

        return $this->render('app/role/index.html.twig', [
            'roles' => $roles,
            'role' => $role,
            'clear' => $clear,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/edit/{id}', name: 'role_edit', methods: ['GET', 'POST'])]
    public function edit(Role $role): Response
    {
        return $this->render('app/role/edit.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route('/delete/{id}', name: 'role_delete', methods: ['POST'])]
    public function delete(Request $request, Role $role, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($role);
            $entityManager->flush();
        }

        return $this->redirectToRoute('roles', [], Response::HTTP_SEE_OTHER);
    }
}
