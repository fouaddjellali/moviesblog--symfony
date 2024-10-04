<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Serie::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Serie $serie;

    #[ORM\Column(type: 'integer')]
    private int $seasonNumber;

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getSerie(): Serie
    {
        return $this->serie;
    }

    public function setSerie(Serie $serie): self
    {
        $this->serie = $serie;
        return $this;
    }

    public function getSeasonNumber(): int
    {
        return $this->seasonNumber;
    }

    public function setSeasonNumber(int $seasonNumber): self
    {
        $this->seasonNumber = $seasonNumber;
        return $this;
    }
}
