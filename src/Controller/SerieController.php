<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\PaysRepository;
use App\Repository\SerieRepository;
use App\Services\SerieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/serie', name: 'app_serie')]
    public function index(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieRepository $serieRepo)
    {
        return $this->render('serie/index.html.twig', [
            'LesSeries' => $serieRepo->findBy([], ["titre" => "ASC"]),
            'SerieCount' => $serieRepo->countAll(),
            "LastIds" => $serieRepo->findLastsIds(2),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }

    #[Route('/serie/details/{id}', name: 'app_serie_details')]
    public function serieDetails(SerieRepository $serieRepo, $id)
    {
        return $this->render('serie/details.html.twig', [
            'LaSerie' => $serieRepo->findById($id),
        ]);
    }

    #[Route('/serie/pays/{id}', name: 'app_series_by_pays')]
    public function paysSeries(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieRepository $serieRepo, $id)
    {
        $series = $paysRepo->find($id)->getSeries();
        return $this->render('serie/index.html.twig', [
            'LesSeries' => $series,
            'SerieCount' => count($series),
            'LastIds' => $serieRepo->findLastsIds(2),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }

    #[Route('/serie/genre/{id}', name: 'app_series_by_genre')]
    public function genreSeries(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieRepository $serieRepo, $id)
    {
        $series = $genreRepo->find($id)->getLesSeries();
        return $this->render('serie/index.html.twig', [
            'LesSeries' => $series,
            'SerieCount' => count($series),
            'LastIds' => $serieRepo->findLastsIds(2),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }
}
