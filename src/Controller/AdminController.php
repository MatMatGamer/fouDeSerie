<?php

namespace App\Controller;

use App\Entity\SerieTv;
use App\Entity\SerieWeb;
use App\Form\SerieTvType;
use App\Form\SerieType;
use App\Form\SerieWebType;
use App\Repository\SerieRepository;
use App\Repository\SerieTvRepository;
use App\Repository\SerieWebRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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

    #[Route('/admin/addSerie/{type}', name: 'app_admin_addserie')]
    public function addSerie(ManagerRegistry $repo, Request $request, string $type): Response
    {
        if ($type == "tv") {
            $serie = new SerieTv();
            $form = $this->createForm(SerieType::class, $serie)
                ->add('chaineDiffusion');
        } else if ($type == "web") {
            $serie = new SerieWeb();
            $form = $this->createForm(SerieType::class, $serie)
                ->add('site');
        } else {
            $this->addFlash("err", "Le type spécifié est introuvable !");
            return $this->redirectToRoute("app_admin");
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $repo->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieForm.html.twig', [
            "form" => $form->createView(),
            "type" => $type
        ]);
    }

    #[Route('/admin/listeSeries/{type}', name: 'app_admin_listeseries')]
    public function listeSeries(SerieTvRepository $serieTv, SerieWebRepository $serieWeb, string $type): Response
    {
        if ($type == "tv") {
            $series = $serieTv->findAll();
        } else if ($type == "web") {
            $series = $serieWeb->findAll();
        } else {
            $this->addFlash("err", "Le type spécifié est introuvable !");
            return $this->redirectToRoute("app_admin");
        }
        return $this->render('admin/listeSerie.html.twig', [
            "type" => $type,
            "LesSeries" => $series
        ]);
    }

    #[Route('/admin/series/update/{id}', name: 'app_admin_updateserie')]
    public function updateSerie(SerieRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $serie = $repo->find($id);
        $form = $this->createForm(SerieType::class, $serie);
        if ($serie instanceof SerieTv) {
            $form->add('chaineDiffusion');
        } else if ($serie instanceof SerieWeb) {
            $form->add('site');
        } else {
            $this->addFlash("err", "Une erreur est survenue sur cette série");
            return $this->redirectToRoute("app_admin");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->flush();
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieTvForm.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
