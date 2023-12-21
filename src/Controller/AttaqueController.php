<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Attaque;
use App\Form\AttaqueType;
use App\Repository\AttaqueRepository;
use Exception;

class AttaqueController extends AbstractController
{
    #[Route('/attaque', name: 'app_attaque_liste')]
    public function listePokemon(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Attaque::class);
        $lesAttaques = $repository->findBy([], ['nom' => 'ASC']);
        return $this->render(
            'attaque/listeAttaques.html.twig',
            ['lesAttaques' => $lesAttaques]
        );
    }

    #[Route('/attaque/add', name: 'app_attaque_add')]
    public function addDresseur(Request $request, ManagerRegistry $doctrine): Response
    {
        $attaque = new Attaque();
        $form = $this->createForm(AttaqueType::class, $attaque);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($attaque);
            $entityManager->flush();
            return $this->redirectToRoute('app_attaque_liste');
        }
        return $this->render('attaque/addAttaque.html.twig', [
            'formAddAttaque' => $form->createView(),
        ]);
    }

    #[Route('/attaque/update/{id}', name: 'app_attaque_update')]
    public function updateDresseur(AttaqueRepository $repo, Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        try {
            $attaque = $repo->find($id);
            if (!$attaque) {
                throw $this->createNotFoundException("L'attaque n'as pas été trouvé !");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }
        $form = $this->createForm(AttaqueType::class, $attaque);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_attaque_liste');
        }
        return $this->render('attaque/addAttaque.html.twig', [
            'formAddAttaque' => $form->createView(),
        ]);
    }

    #[Route('/attaque/delete/{id}', name: 'app_attaque_delete', methods: 'delete')]
    public function deleteDresseur(AttaqueRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        try {
            $attaque = $repo->find($id);

            if ($attaque == null) throw $this->createNotFoundException("L'attaque est introuvable");
            if (
                !$this->isCsrfTokenValid("DeLeteCSRFtokenAttaque" . $id . "AdiosAttaque", $request->get("token"))
            ) {
                throw $this->createAccessDeniedException("Action non permise. Token CSRF invalide.");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($attaque);
        $entityManager->flush();

        return $this->redirectToRoute("app_attaque_liste");
    }
}
