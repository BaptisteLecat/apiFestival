<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BarcodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"barcode:get"}, "skip_null_values" = false },
 *      attributes={"security"="is_granted('ROLE_ADMIN')"},
 *      collectionOperations={
 *          "get"={"groups"={"barcodes:get"}, "security"="is_granted('ROLE_ADMIN')"},
 *      },
 *      itemOperations={
 *          "get"={"groups"={"barcode:get"}, "security"="is_granted('ROLE_ADMIN') or object.getUser() == user"},
 *          "put"={"groups"={"barcode:put"}, "security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_barcode:put"}},
 *          "delete"={"security"="is_granted('ROLE_ADMIN') or object.getUser() == user"},
 *      }
 * )
 * @ORM\Entity(repositoryClass=BarcodeRepository::class)
 */
class Barcode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "event:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put"})
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="barcodes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put"})
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="barcodes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put", "event:get"})
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"barcodes:get","barcode:get", "barcode:put", "denormalization_barcode:put"})
     */
    private $firstname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }
}
