<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VideoController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('video/index.html.twig', [
            'videos' => $videoRepository->findAll(),
        ]);
    }

    //  MÉTHODE AJOUTER !!:
    #[Route('/video/create', name: 'app_video_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            $this->addFlash('danger', 'Vous devez être connecté pour créer une vidéo.');
            return $this->redirectToRoute('app_login');
        }

        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();

            $this->addFlash('success', 'Vidéo créée avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('video/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   #[Route('/video/{id}', name: 'app_video_show')]
    public function show(Video $video): Response
{
    return $this->render('video/show.html.twig', [
        'video' => $video,
    ]);
}

#[Route('/video/{id}/edit', name: 'app_video_edit')]
public function edit(Request $request, Video $video, EntityManagerInterface $em): Response
{
    if (!$this->getUser()){
        $this->addFlash('danger', 'Vous devez être connecté pour modifier une vidéo.');
        return $this->redirectToRoute('app_login');
    }


    // vérifier si l'utilsateur est bien le propriétaire de la vidéo
    if($video->getUser() !== $this->getUser()){
        $this->addFlash('danger', 'Vous n\'êtes pas autorisé.');
        return $this->redirectToRoute('app_login');
    }
    // si on arrive ici l'utilisatteur est bien le vrai utisateur

    $form = $this->createForm(VideoType::class, $video);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
        $em->flush();
        $this->addFlash('success', 'vidéo modifié !');
        return $this->redirectToRoute('app_home');
    }

    return $this->render('video/edit.html.twig', [
        'form' => $form->createView(), 
        'video' => $video
    ]);
}

#[Route('/video/{id}/delete', name: 'app_video_delete')]
public function delete(Request $request, Video $video, EntityManagerInterface $em): Response
{

    // vérifier si l'utilsateur est connecté
        if (!$this->getUser()){
        $this->addFlash('danger', 'Vous devez être connecté pour modifier une vidéo.');
        return $this->redirectToRoute('app_login');
    }
    // si on arrive ici l'utilisatteur est bien le vrai utisateur
    if($video->getUser() !== $this->getUser()){
        $this->addFlash('danger', 'Vous n\'êtes pas autorisé.');
        return $this->redirectToRoute('app_login');
    }

    if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
        
        $em->remove($video);
        $em->flush();
        $this->addFlash('success', 'Vidéo supprimée !');

    }
    return $this->redirectToRoute('app_home');
}
}

