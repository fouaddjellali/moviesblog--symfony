<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string', columnDefinition: "ENUM('movie', 'serie')")]
    private string $mediaType;
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;
    #[ORM\Column(type: 'text')]
    private string $shortDescription;
    #[ORM\Column(type: 'text')]
    private string $longDescription;
    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $releaseDate;
    #[ORM\Column(type: 'string', length: 255)]
    private string $coverImage;
    #[ORM\Column(type: 'json')]
    private array $staff;
    #[ORM\Column(type: 'json')]
    private array $cast;
    public function getId(): int
    {
        return $this->id;
    }
    public function getMediaType(): string
    {
        return $this->mediaType;
    }
    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;
        return $this;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function getLongDescription(): string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;
        return $this;
    }

    public function getReleaseDate(): \DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getCoverImage(): string
    {
        return $this->coverImage;
    }
    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;
        return $this;
    }
    public function getStaff(): array
    {
        return $this->staff;
    }
    public function setStaff(array $staff): self
    {
        $this->staff = $staff;
        return $this;
    }
    public function getCast(): array
    {
        return $this->cast;
    }
    public function setCast(array $cast): self
    {
        $this->cast = $cast;
        return $this;
    }
}
