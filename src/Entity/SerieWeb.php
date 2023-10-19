<?php

namespace App\Entity;

use App\Repository\SerieWebRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieWebRepository::class)]
class SerieWeb extends Serie
{
    #[ORM\Column(length: 255)]
    private ?string $site = null;

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): static
    {
        $this->site = $site;

        return $this;
    }
}
