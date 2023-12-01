<?php

namespace App\Controller;

use App\Entity\SerieTv;
use App\Entity\SerieWeb;
use App\Form\SerieTvType;
use App\Form\SerieWebType;
use App\Repository\SerieTvRepository;
use App\Repository\SerieWebRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/serieTv', name: 'app_admin_addserietv')]
    public function addSerieTv(ManagerRegistry $repo, Request $request): Response
    {
        $serie = new SerieTv();
        $form = $this->createForm(SerieTvType::class, $serie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $repo->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieTvForm.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/serieWeb', name: 'app_admin_addserieweb')]
    public function addSerieWeb(ManagerRegistry $repo, Request $request): Response
    {
        $serie = new SerieWeb();
        $form = $this->createForm(SerieWebType::class, $serie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $repo->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieWebForm.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/seriesTv', name: 'app_admin_seriestv')]
    public function listeSerieTv(SerieTvRepository $repo): Response
    {
        return $this->render('admin/listeSerie.html.twig', [
            "type" => "tv",
            "LesSeries" => $repo->findAll()
        ]);
    }

    #[Route('/admin/seriesTv/update/{id}', name: 'app_admin_update_serietv')]
    public function updateSerieTv(SerieTvRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $serie = $repo->find($id);
        $form = $this->createForm(SerieTvType::class, $serie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieTvForm.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/seriesWeb', name: 'app_admin_seriesweb')]
    public function listeSerieWeb(SerieWebRepository $repo): Response
    {
        return $this->render('admin/listeSerie.html.twig', [
            "type" => "web",
            "LesSeries" => $repo->findAll()
        ]);
    }

    #[Route('/admin/seriesWeb/update/{id}', name: 'app_admin_update_serieweb')]
    public function updateSerieWeb(SerieWebRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $serie = $repo->find($id);
        $form = $this->createForm(SerieWebType::class, $serie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieWebForm.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
