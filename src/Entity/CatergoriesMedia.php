<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CategoriesMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Categorie $category;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Media $media;

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategory(): Categorie
    {
        return $this->category;
    }

    public function setCategory(Categorie $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;
        return $this->media;
    }
}