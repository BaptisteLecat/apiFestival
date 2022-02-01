<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"payment:get"}, "skip_null_values" = false },
 *      attributes={"security"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "post"={"denormalization_context"={"groups"="denormalization_payments:post"}},
 *          "get"={"groups"={"payments:get"}, "security"="is_granted('ROLE_ADMIN')"},
 *      },
 *      itemOperations={
 *          "get"={"groups"={"payment:get"}, "security"="is_granted('ROLE_ADMIN')"},
 *          "put"={"groups"={"payment:put"}, "security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_payment:put"}},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"session": "exact"}) 
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment implements OwnerForceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"payments:get", "payment:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"payments:get", "payment:get", "denormalization_payments:post"})
     */
    private $datePayment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="payments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put"})
     */
    public $user;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put", "denormalization_payments:post"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put", "denormalization_payments:post"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put", "denormalization_payments:post"})
     */
    private $session;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put", "denormalization_payments:post"})
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="events")
     * @Groups({"payments:get", "payment:get", "denormalization_payment:put", "denormalization_payments:post"})
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->datePayment;
    }

    public function setDatePayment(\DateTimeInterface $datePayment): self
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(?string $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
}
