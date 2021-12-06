<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"event:get"}, "skip_null_values" = false },
 *      attributes={"security"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "post"={"security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_events:post"}},
 *          "get"={"groups"={"events:get"}, "security"="is_granted('ROLE_USER')"},
 *      },
 *      itemOperations={
 *          "get"={"groups"={"event:get"}, "security"="is_granted('ROLE_USER')"},
 *          "put"={"groups"={"event:put"}, "security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_event:put"}},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *      }
 * )
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"events:get", "event:get", "artist:get"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="events")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post"})
     */
    private $artists;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get"})
     */
    private $date;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
