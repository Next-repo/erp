<?php

namespace App\Controller\App;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\User\CompteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/espace')]
final class EspaceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('/compte', name: 'compte', methods: ['POST', 'GET'])]
    public function compte(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $form = $this->createForm(CompteType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', "Votre compte a bien été mis à jour");
            $this->entityManager->flush();
            $this->addFlash('success', "Votre compte a bien été mis à jour.");
            return $this->redirectToRoute('compte', [], Response::HTTP_SEE_OTHER);
        }

        // The token is valid; allow the user to change their password.
        $resetForm = $this->createForm(ChangePasswordFormType::class);
        $resetForm->handleRequest($request);

        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $resetForm->get('plainPassword')->getData();
            // Encode(hash) the plain password, and set it.
            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            $this->entityManager->flush();
            $this->addFlash('success', "Votre mot de passe a bien été mis à jour.");
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('app/espace/compte.html.twig', [
            'form' => $form,
            'resetForm' => $resetForm,
            'user' => $user
        ]);
    }
}
