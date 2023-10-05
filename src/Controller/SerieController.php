<?php

namespace App\Controller;

use App\Services\SerieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/serie', name: 'app_serie')]
    public function index(): Response
    {
        return $this->render('serie/index.html.twig', [
            'LesSeries' => SerieService::getSeries(),
            'SerieCount' => SerieService::count(),
            'LastIds' => SerieService::getLastIds(2)
        ]);
    }

    #[Route('/serie/{id}', name: 'app_serie_details')]
    public function serieDetails($id)
    {
        return $this->render('serie/details.html.twig', [
            'LaSerie' => SerieService::getSerieById($id),
        ]);
    }
}
