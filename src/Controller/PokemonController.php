<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PokemonCasanier;
use App\Entity\Pokemon;
use App\Entity\PokemonMer;
use App\Form\PokemonCasanierType;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Exception;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'app_pokemon_liste')]
    public function listePokemon(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Pokemon::class);
        $lesPokemons = $repository->findBy([], ['nom' => 'ASC']);
        return $this->render(
            'pokemon/listePokemons.html.twig',
            ['lesPokemons' => $lesPokemons]
        );
    }

    #[Route('/pokemon/{type}/add', name: 'app_pokemon_add')]
    public function addPokemon(Request $request, ManagerRegistry $doctrine, string $type): Response
    {
        if ($type == "casanier") {
            $pokemon = new PokemonCasanier();
            $form = $this->createForm(PokemonType::class, $pokemon)
                ->add('nbPattes')
                ->add('nbHeuresTv');
        } else if ($type == "mer") {
            $pokemon = new PokemonMer();
            $form = $this->createForm(PokemonType::class, $pokemon)
                ->add('nbNageoires');
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($pokemon);
            $entityManager->flush();
            $this->addFlash("success", "Le pokemon à bien été ajouté avec succès !");
            return $this->redirectToRoute('app_pokemon_liste');
        }
        return $this->render('pokemon/addPokemon.html.twig', [
            'formAddPokemon' => $form->createView(),
            "type" => $type
        ]);
    }

    #[Route('/pokemon/update/{id}', name: 'app_pokemon_update')]
    public function updatePokemon(Request $request, ManagerRegistry $doctrine, PokemonRepository $repo, int $id): Response
    {
        try {
            $pokemon = $repo->find($id);
            if (!$pokemon) {
                throw $this->createNotFoundException("Le pokemon n'as pas été trouvé !");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }
        $form = $this->createForm(PokemonType::class, $pokemon);
        if ($pokemon instanceof PokemonCasanier) {
            $form->add('nbPattes')
                ->add('nbHeuresTv');
            $type = "casanier";
        } else if ($pokemon instanceof PokemonMer) {
            $form->add('nbNageoires');
            $type = "mer";
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash("success", "Le pokemon à bien été modifié avec succès !");
            return $this->redirectToRoute('app_pokemon_liste');
        }
        return $this->render('pokemon/addPokemon.html.twig', [
            'formAddPokemon' => $form->createView(),
            "type" => $type
        ]);
    }

    #[Route('/pokemon/delete/{id}', name: 'app_pokemon_delete', methods: 'delete')]
    public function deleteDresseur(PokemonRepository $repo, ManagerRegistry $manager, Request $request, int $id): Response
    {
        try {
            $pokemon = $repo->find($id);

            if ($pokemon == null) throw $this->createNotFoundException("Le pokemon est introuvable");
            if (
                !$this->isCsrfTokenValid("DeLeteCSRFtokenPokemon" . $id . "AdiosPokemon", $request->get("token"))
            ) {
                throw $this->createAccessDeniedException("Action non permise. Token CSRF invalide.");
            }
        } catch (Exception $e) {
            return $this->render('errors/error.html.twig', ["message" => $e->getMessage()]);
        }

        $entityManager = $manager->getManager();
        $entityManager->remove($pokemon);
        $entityManager->flush();
        $this->addFlash("success", "Le pokemon à bien été supprimé avec succès !");
        return $this->redirectToRoute("app_pokemon_liste");
    }

    #[Route(path: "/testinounet", name: "testinounetAddHeritageFormPokemonDuTurfu")]
    public function test()
    {
        $pokemon = new PokemonCasanier();
        $form = $this->createForm(PokemonCasanierType::class, $pokemon);
        return $this->render("test.html.twig", ["form" => $form->createView()]);
    }
}
