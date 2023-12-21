<?php

namespace App\Controller;

use App\Entity\SerieWeb;
use App\Repository\GenreRepository;
use App\Repository\PaysRepository;
use App\Repository\SerieRepository;
use App\Repository\SerieWebRepository;
use App\Repository\SerieTvRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isInstanceOf;

class SerieController extends AbstractController
{

    #[Route('/serie', name: 'app_serie')]
    public function index(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieWebRepository $serieWebRepo, SerieTvRepository $serieTvRepo)
    {
        return $this->render('serie/index.html.twig', [
            'LesSeriesTv' => $serieTvRepo->findBy([], ["titre" => "ASC"]),
            'LesSeriesWeb' => $serieWebRepo->findBy([], ["titre" => "ASC"]),
            'SerieTvCount' => $serieTvRepo->countAll(),
            'SerieWebCount' => $serieWebRepo->countAll(),
            "LastIds" => array_merge($serieTvRepo->findLastsIds(2), $serieWebRepo->findLastsIds(2)),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }

    #[Route('/serie/details/{id}', name: 'app_serie_details')]
    public function serieDetails(SerieRepository $serieRepo, $id)
    {
        try {
            $serie = $serieRepo->find($id);
            if ($serie == null) throw $this->createNotFoundException('La série recherchée est introuvable');
            return $this->render('serie/details.html.twig', [
                'LaSerie' => $serie
            ]);
        } catch (NotFoundHttpException $e) {
            return $this->render('errors/error.html.twig', ["error" => $e->getStatusCode(), "message" => $e->getMessage()]);
        }
    }

    #[Route('/serie/pays/{id}', name: 'app_series_by_pays')]
    public function paysSeries(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieWebRepository $serieWebRepo, SerieTvRepository $serieTvRepo, $id)
    {
        try {
            $pays = $paysRepo->find($id);
            if ($pays == null) throw $this->createNotFoundException("Le pays est introuvable");
        } catch (NotFoundHttpException $e) {
            return $this->render('errors/error.html.twig', ["error" => $e->getStatusCode(), "message" => $e->getMessage()]);
        }

        $series = $pays->getSeries();

        $seriesTv = array();
        $seriesWeb = array();

        foreach ($series as $s) {
            if ($s instanceof SerieWeb) $seriesWeb[] = $s;
            else $seriesTv[] = $s;
        }

        return $this->render('serie/index.html.twig', [
            'LesSeriesTv' => $seriesTv,
            'LesSeriesWeb' => $seriesWeb,
            'SerieTvCount' => count($seriesTv),
            'SerieWebCount' => count($seriesWeb),
            "LastIds" => array_merge($serieTvRepo->findLastsIds(2), $serieWebRepo->findLastsIds(2)),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }

    #[Route('/serie/genre/{id}', name: 'app_series_by_genre')]
    public function genreSeries(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieWebRepository $serieWebRepo, SerieTvRepository $serieTvRepo, $id)
    {
        try {
            $genre = $genreRepo->find($id);
            if ($genre == null) throw $this->createNotFoundException("Le genre est introuvable");
        } catch (NotFoundHttpException $e) {
            return $this->render('errors/error.html.twig', ["error" => $e->getStatusCode(), "message" => $e->getMessage()]);
        }

        $series = $genre->getLesSeries();

        $seriesTv = array();
        $seriesWeb = array();

        foreach ($series as $s) {
            if ($s instanceof SerieWeb) $seriesWeb[] = $s;
            else $seriesTv[] = $s;
        }

        return $this->render('serie/index.html.twig', [
            'LesSeriesTv' => $seriesTv,
            'LesSeriesWeb' => $seriesWeb,
            'SerieTvCount' => count($seriesTv),
            'SerieWebCount' => count($seriesWeb),
            "LastIds" => array_merge($serieTvRepo->findLastsIds(2), $serieWebRepo->findLastsIds(2)),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll()
        ]);
    }

    #[Route('/serie/between', name: 'app_series_between_dates_redirect')]
    public function betweenDateSeriesRedirect(Request $request)
    {
        $date1 = $request->query->get("date1") ? $request->query->get("date1") : "0000-01";
        $date2 = $request->query->get("date2") ? $request->query->get("date2") : "9999-12";

        if ($date1 == "0000-01" && $date2 == "9999-12") return $this->redirectToRoute("app_serie");
        return $this->redirectToRoute('app_series_between_dates', [
            "date1" => $date1,
            "date2" => $date2
        ]);
    }

    #[Route('/serie/between/{date1}/{date2}', name: 'app_series_between_dates')]
    public function betweenDateSeries(PaysRepository $paysRepo, GenreRepository $genreRepo, SerieWebRepository $serieWebRepo, SerieTvRepository $serieTvRepo, $date1, $date2)
    {
        $seriesTv = $serieTvRepo->findBetweenDates($date1, $date2);
        $seriesWeb = $serieWebRepo->findBetweenDates($date1, $date2);

        return $this->render('serie/index.html.twig', [
            'LesSeriesTv' => $seriesTv,
            'LesSeriesWeb' => $seriesWeb,
            'SerieTvCount' => count($seriesTv),
            'SerieWebCount' => count($seriesWeb),
            "LastIds" => array_merge($serieTvRepo->findLastsIds(2), $serieWebRepo->findLastsIds(2)),
            'lesPays' => $paysRepo->findAll(),
            'lesGenres' => $genreRepo->findAll(),
            "date1" => $date1,
            "date2" => $date2 == "9999-12" ? null : $date2,
        ]);
    }
}
