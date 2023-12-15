<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Pays;
use App\Entity\SerieTv;
use App\Entity\SerieWeb;
use App\Form\GenreType;
use App\Form\PaysType;
use App\Form\SerieTvType;
use App\Form\SerieType;
use App\Form\SerieWebType;
use App\Repository\GenreRepository;
use App\Repository\PaysRepository;
use App\Repository\SerieRepository;
use App\Repository\SerieTvRepository;
use App\Repository\SerieWebRepository;
use Doctrine\ORM\EntityManager;
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


    #[Route('/admin/series/delete/{id}', name: 'app_admin_deleteserie', methods: 'delete')]
    public function deleteSerie(SerieRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $serie = $repo->find($id);

        if (!$serie) {
            $this->addFlash("err", "Série introuvable");
            return $this->redirectToRoute("app_admin");
        }
        if (
            !$this->isCsrfTokenValid("DeLeteCSRFtokenSerie" . $id . "MonReufré", $request->get("token"))
        ) {
            $this->addFlash("err", "Token INVALIDE");
            return $this->redirectToRoute("app_admin");
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash("success", "La série à bien été supprimée avec succès !");
        return $this->redirectToRoute("app_serie");
    }

    #[Route('/admin/genre/delete/{id}', name: 'app_admin_deletegenre', methods: 'delete')]
    public function deleteGenre(GenreRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $genre = $repo->find($id);

        if (!$genre) {
            $this->addFlash("err", "Genre introuvable");
            return $this->redirectToRoute("app_admin");
        }
        if (
            !$this->isCsrfTokenValid("DeLeteCSRFtokenGenre" . $id . "MonReufré", $request->get("token"))
        ) {
            $this->addFlash("err", "Token invalide");
            return $this->redirectToRoute("app_admin");
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($genre);
        $entityManager->flush();

        $this->addFlash("success", "Le genre à bien été supprimé avec succès !");
        return $this->redirectToRoute("app_serie");
    }

    #[Route('/admin/pays/delete/{id}', name: 'app_admin_deletepays', methods: 'delete')]
    public function deletePays(PaysRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $pays = $repo->find($id);

        if (!$pays) {
            $this->addFlash("err", "Pays introuvable");
            return $this->redirectToRoute("app_admin");
        }
        if (
            !$this->isCsrfTokenValid("DeLeteCSRFtokenPays" . $id . "MonReufré", $request->get("token"))
        ) {
            $this->addFlash("err", "Token invalide");
            return $this->redirectToRoute("app_admin");
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($pays);
        $entityManager->flush();

        $this->addFlash("success", "Le pays à bien été supprimé avec succès !");
        return $this->redirectToRoute("app_serie");
    }

    #[Route('/admin/series/add/{type}', name: 'app_admin_addserie')]
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

            $this->addFlash("success", "La série à bien été ajoutée avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieForm.html.twig', [
            "form" => $form->createView(),
            "type" => $type
        ]);
    }

    #[Route('/admin/genre/add/', name: 'app_admin_addgenre')]
    public function addGenre(ManagerRegistry $repo, Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $repo->getManager();
            $entityManager->persist($genre);

            foreach ($genre->getLesSeries() as $serie) {
                $serie->addLesGenre($genre);
            }

            $entityManager->flush();

            $this->addFlash("success", "Le genre à bien été ajouté avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/genreForm.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/admin/pays/add/', name: 'app_admin_addpays')]
    public function addPays(ManagerRegistry $repo, Request $request): Response
    {
        $pays = new Pays();
        $form = $this->createForm(PaysType::class, $pays);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $repo->getManager();
            $entityManager->persist($pays);
            $entityManager->flush();

            $this->addFlash("success", "Le pays à bien été ajouté avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/paysForm.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/admin/listeSeries/{action}/{type}', name: 'app_admin_listeseries')]
    public function listeSeries(SerieTvRepository $serieTv, SerieWebRepository $serieWeb, string $action, string $type): Response
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
            "LesSeries" => $series,
            "action" => $action
        ]);
    }

    #[Route('/admin/listeGenres/{action}', name: 'app_admin_listegenres')]
    public function listeGenres(GenreRepository $genre, string $action): Response
    {
        $genres = $genre->findAll();
        return $this->render('admin/listeGenre.html.twig', [
            "LesGenres" => $genres,
            "action" => $action
        ]);
    }

    #[Route('/admin/listePays/{action}', name: 'app_admin_listepays')]
    public function listePays(PaysRepository $pays, string $action): Response
    {
        $pays = $pays->findAll();
        return $this->render('admin/listePays.html.twig', [
            "LesPays" => $pays,
            "action" => $action
        ]);
    }

    #[Route('/admin/series/update/{id}', name: 'app_admin_updateserie')]
    public function updateSerie(SerieRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $serie = $repo->find($id);
        $form = $this->createForm(SerieType::class, $serie);
        if ($serie instanceof SerieTv) {
            $form->add('chaineDiffusion');
            $type = "tv";
        } else if ($serie instanceof SerieWeb) {
            $form->add('site');
            $type = "web";
        } else {
            $this->addFlash("err", "Une erreur est survenue sur cette série");
            return $this->redirectToRoute("app_admin");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash("success", "La série à bien été modifiée avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/serieForm.html.twig', [
            "form" => $form->createView(),
            "type" => $type
        ]);
    }

    #[Route('/admin/genres/update/{id}', name: 'app_admin_updategenre')]
    public function updateGenre(GenreRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $genre = $repo->find($id);
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();

            foreach ($genre->getLesSeries() as $serie) {
                $serie->addLesGenre($genre);
            }

            $entityManager->flush();

            $this->addFlash("success", "Le genre à bien été modifié avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/genreForm.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/admin/pays/update/{id}', name: 'app_admin_updatepays')]
    public function updatePays(PaysRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        $pays = $repo->find($id);
        $form = $this->createForm(PaysType::class, $pays);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->flush();

            $this->addFlash("success", "Le pays à bien été modifié avec succès !");
            return $this->redirectToRoute("app_serie");
        }

        return $this->render('admin/paysForm.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
