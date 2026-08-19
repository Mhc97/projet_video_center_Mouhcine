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
<<<<<<< HEAD
use knp\component\Pager\PaginatorInterface;
use PHPUnit\Metadata\Api\Requirements;
use Knp\Bundle\PaginatorBundle\Definition\AbstractPaginatorAware;
=======
use Knp\Component\Pager\PaginatorInterface;

>>>>>>> d50f532ab68544ec6c44f4a594fd113e818452b1

class VideoController extends AbstractController
{
    #[Route('/', name: 'app_home')]
<<<<<<< HEAD
    public function index(VideoRepository $videoRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search', '');
        $showPremium = $this->getUser() && $this->getUser()->isVerified();

        $query = $videoRepository->findBySearchAndVisibility($search, $showPremium);

        $videos = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('video/index.html.twig', [
            'videos' => $videos,
            'searchTerm' => $search,
=======
    public function index(VideoRepository $videoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $videos = $pageinator->paginate(
            $videoRepository->findAll(),
            $request->query->getInt('page', 1),
            6
        );
        $search = $request->query->get('search');

        if ($search){
            $videos = $videoRepository->search($search);
            
        }else {
            $videos = $videoRepository->findAll();
        }
        return $this->render('video/index.html.twig', [
            // 'videos' => $videoRepository->findAll(),
            'videos' => $videos,
            'search' => $search,
>>>>>>> d50f532ab68544ec6c44f4a594fd113e818452b1
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

   #[Route('/video/{id}', name: 'app_video_show' Requirements: ['id' => '\d+'])]
    public function show(Video $video): Response
{
    $videoLink = $video->getVideoLink();
    parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
    $videoId = $queryParams['v'] ?? '';
    if ($video->isPremiumVideo()){
        if (!$this->getUser()){
            $this->addFlash('danger', 'Vous devez vous connecter pour voir une vidéo Premium !');
            return $this->redirectToRoute('app_login');
        }  
             if (!$this->getUser()->isVerified()){
            $this->addFlash('danger', 'Vous devez confirmer votre email pour voir une vidéo Premium !');
            return $this->redirectToRoute('app_home');
        }
 
    }
    return $this->render('video/show.html.twig', [
        'video' => $video,
        'cideoId' => $videoId,
    ]);
}

#[Route('/video/{id}/edit', name: 'app_video_edit')]
public function edit(Request $request, Video $video, EntityManagerInterface $em): Response
{
    if (!$this->getUser()){
        $this->addFlash('danger', 'Vous devez être connecté pour modifier une vidéo.');
        return $this->redirectToRoute('app_login');
    }

    // if (!$this->getUser()->isVerified()){
    //     $this->addFlash('danger', 'Vous devez confirmer votre email pour modifier une vidéo.');
    //     return $this->redirectToRoute('app_home');
    // }


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

