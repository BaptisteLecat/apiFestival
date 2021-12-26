<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"user:get"}, "skip_null_values" = false },
 *      attributes={"security"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "post"={"security"="is_granted('ROLE_ADMIN')", "denormalization_context"={"groups"="denormalization_users:post"}},
 *          "get"={"groups"={"users:get"}, "security"="is_granted('ROLE_ADMIN')"},
 *      },
 *      itemOperations={
 *          "get"={"groups"={"user:get"}, "security"="is_granted('ROLE_ADMIN') or object == user"},
 *          "put"={"groups"={"user:put"}, "security"="is_granted('ROLE_ADMIN') or object == user", "denormalization_context"={"groups"="denormalization_user:put"}},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *      }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users:get", "user:get","barcode:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     * @Groups({"users:get", "user:get", "denormalization_users:post", "denormalization_user:put","barcode:get"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     * @Groups({"users:get", "user:get", "denormalization_users:post", "denormalization_user:put","barcode:get"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Groups({"users:get", "user:get", "denormalization_users:post", "denormalization_user:put","barcode:get"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users:get", "user:get", "user:put", "denormalization_user:put"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"denormalization_users:post"})
     */
    private $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $jwt;

    /**
     * @ORM\OneToMany(targetEntity=Barcode::class, mappedBy="user", orphanRemoval=true)
     */
    private $barcodes;

    public function __construct()
    {
        $this->barcodes = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getJwt(): ?string
    {
        return $this->jwt;
    }

    public function setJwt(?string $jwt): self
    {
        $this->jwt = $jwt;

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
            $barcode->setUser($this);
        }

        return $this;
    }

    public function removeBarcode(Barcode $barcode): self
    {
        if ($this->barcodes->removeElement($barcode)) {
            // set the owning side to null (unless already changed)
            if ($barcode->getUser() === $this) {
                $barcode->setUser(null);
            }
        }

        return $this;
    }
}
