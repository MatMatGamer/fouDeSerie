<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $drapeau = null;

    #[ORM\OneToMany(targetEntity: Serie::class, mappedBy: 'pays')]
    private Collection $series;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDrapeau(): ?string
    {
        return $this->drapeau;
    }

    public function setDrapeau(string $drapeau): static
    {
        $this->drapeau = $drapeau;

        return $this;
    }

    public function getSeries(): ?Collection
    {
        return $this->series;
    }

    public function addSeries(Serie $serie): static
    {
        if (!$this->series->contains($serie)) {
            $this->series->add($serie);
            $serie->setPays($this);
        }

        return $this;
    }

    public function removeSeries(Serie $serie): static
    {
        if ($this->series->contains($serie)) {
            $this->series->removeElement($serie);
            $serie->setPays(null);
        }

        return $this;
    }
}
