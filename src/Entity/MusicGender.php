<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MusicGenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"musicgender:get"}, "skip_null_values" = false },
 *      attributes={"security"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "post"={"security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_musicgenders:post"}},
 *          "get"={"groups"={"musicgenders:get"}, "security"="is_granted('ROLE_USER')"},
 *      },
 *      itemOperations={
 *          "get"={"groups"={"musicgender:get"}, "security"="is_granted('ROLE_USER')"},
 *          "put"={"groups"={"musicgender:put"}, "security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_musicgender:put"}},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *      }
 * )
 * @ORM\Entity(repositoryClass=MusicGenderRepository::class)
 */
class MusicGender
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"musicgenders:get", "musicgender:get", "artist:get", "event:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"musicgenders:get", "musicgender:get","denormalization_musicgenders:post", "denormalization_musicgender:put", "artist:get", "event:get"})
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, mappedBy="musicGenders")
     * @Groups({"musicgenders:get", "musicgender:get", "denormalization_musicgender:put"})
     */
    private $artists;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="musicgenders")
     * @Groups({"musicgenders:get", "musicgender:get", "denormalization_musicgender:put"})
     */
    private $events;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
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
            $artist->addMusicGender($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeMusicGender($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addMusicgender($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->removeElement($event)) {
            $event->removeMusicgender($this);
        }

        return $this;
    }
}
