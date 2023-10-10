<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
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
    public function indexNews(SerieRepository $serieRepo)
    {
        return $this->render('home/news.html.twig', ["lastSeries" => $serieRepo->findLasts(2)]);
    }

    /*#[Route('/testEntity', name: 'app_testEntity')]
    public function testEntity(ManagerRegistry $doctrine)
    {
        $serie = new Serie();
        $serie->setTitre("Breaking Bad");
        $serie->setResume("Walter White, 50 ans, est professeur de chimie dans un lycée du Nouveau-Mexique. Pour subvenir aux besoins de Skyler, sa femme enceinte, et de Walt Junior, son fils handicapé, il est obligé de travailler doublement. Son quotidien déjà morose devient carrément noir lorsqu'il apprend qu'il est atteint d'un incurable cancer des poumons.");
        $serie->setPremiereDiffusion(new DateTime("2009-10-20"));
        $serie->setNbEpisodes(62);
        $serie->setImage("https://media.senscritique.com/media/000020861455/300/breaking_bad.png");

        $entityManager = $doctrine->getManager();
        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->render('serie/details.html.twig', ["LaSerie" => $serie]);
    }*/
}
