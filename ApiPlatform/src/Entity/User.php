<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *   itemOperations={"get"},
 *   collectionOperations={},
 *   normalizationContext={
 *     "groups"={"read"}
 *   }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
      * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
      * @Groups("read")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups("read")
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
      * @Groups("read")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="author")
      * @Groups("read")
     */
    private $posts;

    public function __construct() {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return (string) $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string {
      return $this->name;
    }

    public function setName(string $name): self {
      $this->name = $name;

      return $this;
    }

    public function getEmail(): ?string {
      return $this->email;
    }

    public function setEmail(string $email): self {
      $this->email = $email;

      return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string) $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection {
        return $this->comments;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPosts(): Collection {
        return $this->posts;
    }
}
