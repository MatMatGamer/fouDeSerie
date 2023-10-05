<?php

namespace App\Controller;

use App\Services\SerieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/news', name: 'app_news')]
    public function indexNews(): Response
    {
        $last = SerieService::getLast(2);
        return $this->render('home/news.html.twig', ["lastSeries" => $last]);
    }
}
