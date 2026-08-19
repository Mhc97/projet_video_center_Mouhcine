<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VideoController extends AbstractController
{
#[Route('/{_locale}/profile', name: 'app_profile', requirements: ['_locale' => 'fr|en'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à votre profil.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'videos' => $user->getVideos(),
        ]);
    }
    // ✅ AJOUTER LA MÉTHODE EDIT
    #[Route('/{_locale}/profile/edit', name: 'app_profile_edit', requirements: ['_locale' => 'fr|en'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour modifier votre profil.');
            return $this->redirectToRoute('app_login');
        }

        // Créer UserType si pas encore fait
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'Profil modifié avec succès !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}