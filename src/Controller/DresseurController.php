<?php

namespace App\Controller;

use App\Entity\Dresseur;
use App\Form\DresseurType;
use App\Repository\DresseurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DresseurController extends AbstractController
{
    #[Route('/dresseur', name: 'app_dresseur_liste')]
    public function listePokemon(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Dresseur::class);
        $lesDresseurs = $repository->findBy([], ['nom' => 'ASC']);
        return $this->render(
            'dresseur/listeDresseurs.html.twig',
            ['lesDresseurs' => $lesDresseurs]
        );
    }

    #[Route('/dresseur/add', name: 'app_dresseur_add')]
    public function addDresseur(Request $request, ManagerRegistry $doctrine): Response
    {
        $dresseur = new Dresseur();
        $form = $this->createForm(DresseurType::class, $dresseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($dresseur);
            $entityManager->flush();
            return $this->redirectToRoute('app_dresseur_liste');
        }
        return $this->render('dresseur/addDresseur.html.twig', [
            'formAddDresseur' => $form->createView(),
        ]);
    }

    #[Route('/dresseur/update/{id}', name: 'app_dresseur_update')]
    public function updateDresseur(DresseurRepository $repo, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        try {
            $dresseur = $repo->find($id);
            if (!$dresseur) {
                throw $this->createNotFoundException("Le dresseur n'as pas été trouvé !");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }
        $form = $this->createForm(DresseurType::class, $dresseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_dresseur_liste');
        }
        return $this->render('dresseur/addDresseur.html.twig', [
            'formAddDresseur' => $form->createView(),
        ]);
    }

    #[Route('/dresseur/delete/{id}', name: 'app_dresseur_delete', methods: 'delete')]
    public function deleteDresseur(DresseurRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        try {
            $dresseur = $repo->find($id);

            if ($dresseur == null) throw $this->createNotFoundException("Le dresseur est introuvable");
            if (
                !$this->isCsrfTokenValid("DeLeteCSRFtokenDresseur" . $id . "AdiosDresseur", $request->get("token"))
            ) {
                throw $this->createAccessDeniedException("Action non permise. Token CSRF invalide.");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($dresseur);
        $entityManager->flush();

        return $this->redirectToRoute("app_dresseur_liste");
    }
}
