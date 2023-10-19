<?php

namespace App\Entity;

use App\Repository\SerieTvRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieTvRepository::class)]
class SerieTv extends Serie
{
    #[ORM\Column(length: 255)]
    private ?string $chaineDiffusion = null;

    public function getChaineDiffusion(): ?string
    {
        return $this->chaineDiffusion;
    }

    public function setChaineDiffusion(string $chaineDiffusion): static
    {
        $this->chaineDiffusion = $chaineDiffusion;

        return $this;
    }
}
