<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use App\Services\SerieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/serie', name: 'app_serie')]
    public function index(SerieRepository $serieRepo)
    {
        return $this->render('serie/index.html.twig', [
            'LesSeries' => $serieRepo->findBy([], ["titre" => "ASC"]),
            'SerieCount' => $serieRepo->countAll(),
            "LastIds" => $serieRepo->findLastsIds(2)
        ]);
    }

    #[Route('/serie/{id}', name: 'app_serie_details')]
    public function serieDetails(SerieRepository $serieRepo, $id)
    {
        return $this->render('serie/details.html.twig', [
            'LaSerie' => $serieRepo->findById($id),
        ]);
    }
}
