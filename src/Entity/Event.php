<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

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
 * @ApiFilter(SearchFilter::class, properties={"musicgenders.label": "exact"})
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"events:get", "event:get", "artist:get","barcode:get"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="events")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post","barcode:get"})
     */
    private $artists;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get","barcode:get"})
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get","barcode:get"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get","barcode:get"})
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get","barcode:get"})
     */
    private $endDate;

    /**
     * @ORM\ManyToMany(targetEntity=MusicGender::class, inversedBy="yes")
     * @Groups({"events:get", "event:get", "denormalization_event:put"})
     */
    private $musicgenders;

    /**
     * @ORM\Column(type="text")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get","barcode:get"})
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     *  @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     *  @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get"})
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Barcode::class, mappedBy="event", orphanRemoval=true)
     * @Groups({"events:get", "event:get", "artist:get"})
     */
    private $barcodes;

    /**
     * @ORM\Column(type="float")
     * @Groups({"events:get", "event:get", "denormalization_event:put", "denormalization_events:post", "artist:get"})
     */
    private $price;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->musicgenders = new ArrayCollection();
        $this->barcodes = new ArrayCollection();
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
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

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|MusicGender[]
     */
    public function getMusicgenders(): Collection
    {
        return $this->musicgenders;
    }

    public function addMusicgender(MusicGender $musicgender): self
    {
        if (!$this->musicgenders->contains($musicgender)) {
            $this->musicgenders[] = $musicgender;
        }

        return $this;
    }

    public function removeMusicgender(MusicGender $musicgender): self
    {
        $this->musicgenders->removeElement($musicgender);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Barcode[]
     */
    public function getBarcodes(): Collection
    {
        return $this->barcodes;
    }

    public function addBarcode(Barcode $barcode): self
    {
        if (!$this->barcodes->contains($barcode)) {
            $this->barcodes[] = $barcode;
            $barcode->setEvent($this);
        }

        return $this;
    }

    public function removeBarcode(Barcode $barcode): self
    {
        if ($this->barcodes->removeElement($barcode)) {
            // set the owning side to null (unless already changed)
            if ($barcode->getEvent() === $this) {
                $barcode->setEvent(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
