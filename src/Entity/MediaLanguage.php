<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MediaLanguages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Media $media;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Languages $language;

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;
        return $this;
    }

    public function getLanguage(): Languages
    {
        return $this->language;
    }

    public function setLanguage(Languages $language): self
    {
        $this->language = $language;
        return $this;
    }
}
