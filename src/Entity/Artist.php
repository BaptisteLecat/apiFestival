<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=MusicGender::class, inversedBy="artists")
     */
    private $musicGenders;

    public function __construct()
    {
        $this->musicGenders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MusicGender[]
     */
    public function getMusicGenders(): Collection
    {
        return $this->musicGenders;
    }

    public function addMusicGender(MusicGender $musicGender): self
    {
        if (!$this->musicGenders->contains($musicGender)) {
            $this->musicGenders[] = $musicGender;
        }

        return $this;
    }

    public function removeMusicGender(MusicGender $musicGender): self
    {
        $this->musicGenders->removeElement($musicGender);

        return $this;
    }
}
