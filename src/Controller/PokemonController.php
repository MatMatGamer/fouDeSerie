<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PokemonCasanier;
use App\Entity\Pokemon;
use App\Form\PokemonCasanierType;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'app_pokemon_liste')]
    public function listePokemon( ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Pokemon::class);
        $lesPokemons = $repository->findBy([], ['nom' => 'ASC']);
        return $this->render('pokemon/listePokemons.html.twig', 
                            ['lesPokemons' => $lesPokemons] );
    }

    #[Route('/pokemonCasanier/add', name: 'app_pokemonCasanier_add')]
    public function addPokemon( Request $request, ManagerRegistry $doctrine): Response
    {
        $pokemon= new PokemonCasanier();
        $form=$this->createForm(PokemonCasanierType::class, $pokemon);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($pokemon);
            $entityManager->flush();
            return $this->redirectToRoute('app_pokemon_liste');
        }     
        return $this->render('pokemon/addPokemonCasanier.html.twig', [
            'formAddPokemon' => $form->createView(),
        ]);
    }


}
