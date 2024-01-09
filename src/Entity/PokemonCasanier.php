<?php

namespace App\Entity;

use App\Repository\PokemonCasanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PokemonCasanierRepository::class)]
class PokemonCasanier extends Pokemon
{

    #[ORM\Column(nullable: true, name: 'nbPattes')]
    #[Assert\GreaterThanOrEqual(0, message: "Vous devez entrer un nombre de pattes supérieur ou égal à 0.")]
    #[Assert\LessThanOrEqual(48, message: "Vous devez entrer un nombre de pattes inférieur ou égal à 48.")]
    private ?int $nbPattes = null;

    #[ORM\Column(nullable: true, name: 'nbHeuresTv')]
    private ?int $nbHeuresTv = null;



    public function getNbPattes(): ?int
    {
        return $this->nbPattes;
    }

    public function setNbPattes(?int $nbPattes): static
    {
        $this->nbPattes = $nbPattes;

        return $this;
    }

    public function getNbHeuresTv(): ?int
    {
        return $this->nbHeuresTv;
    }

    public function setNbHeuresTv(?int $nbHeuresTv): static
    {
        $this->nbHeuresTv = $nbHeuresTv;

        return $this;
    }
}
