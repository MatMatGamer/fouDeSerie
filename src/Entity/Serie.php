<?php

namespace App\Entity;

use App\Entity\Pays;
use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'serie')]
#[ORM\Entity(repositoryClass: SerieRepository::class)]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['tv' => SerieTv::class, 'web' => SerieWeb::class])]
#[UniqueEntity("titre", message: "Le titre doit être unique !")]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, name: "premiereDiffusion")]
    private ?\DateTimeInterface $premiereDiffusion = null;

    #[ORM\Column(nullable: true, name: "nbEpisodes")]
    #[Assert\GreaterThan(0, message: "Vous devez entrer un nombre d'épisode supérieur à 0.")]
    #[Assert\LessThan(10000, message: "Vous devez entrer un nombre d'épisode inférieur à 10 000.")]
    private ?int $nbEpisodes = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Pays::class, inversedBy: "series")]
    private Pays $pays;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'lesSeries')]
    #[ORM\JoinColumn(name: "idSerie", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "idGenre", referencedColumnName: "id")]
    private Collection $lesGenres;

    public function __construct()
    {
        $this->lesGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getPremiereDiffusion(): ?\DateTimeInterface
    {
        return $this->premiereDiffusion;
    }

    public function setPremiereDiffusion(?\DateTimeInterface $premiereDiffusion): static
    {
        $this->premiereDiffusion = $premiereDiffusion;

        return $this;
    }

    public function getNbEpisodes(): ?int
    {
        return $this->nbEpisodes;
    }

    public function setNbEpisodes(?int $nbEpisodes): static
    {
        $this->nbEpisodes = $nbEpisodes;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection<int, genre>
     */
    public function getLesGenres(): Collection
    {
        return $this->lesGenres;
    }

    public function addLesGenre(genre $lesGenre): static
    {
        if (!$this->lesGenres->contains($lesGenre)) {
            $this->lesGenres->add($lesGenre);
        }

        return $this;
    }

    public function removeLesGenre(genre $lesGenre): static
    {
        $this->lesGenres->removeElement($lesGenre);

        return $this;
    }
}
