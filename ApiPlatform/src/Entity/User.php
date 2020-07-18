<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *   itemOperations={
 *     "get" = {
 *       "security" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *       "normalization_context"={
 *         "groups"={"get"}
 *       }
 *     },
 *     "put" = {
 *       "security" = "is_granted('IS_AUTHENTICATED_FULLY') && object === user",
 *       "denormalization_context"={
 *         "groups"={"put"}
 *       },
 *       "normalization_context"={
 *         "groups"={"get"}
 *       }
 *     }
 *   },
 *   collectionOperations={
 *     "post" = {
 *       "denormalization_context"={
 *         "groups"={"post"}
 *       },
 *       "normalization_context"={
 *         "groups"={"get"}
 *       }
 *     }
 *   }
 * )
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
      * @Groups("get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
      * @Groups({"get", "post", "get_comments_of_post_with_author", "get_post_with_author"})
      * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups({"get", "put", "post", "get_comments_of_post_with_author", "get_post_with_author"})
      * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     * @Groups({"get:owner", "get:admin"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups({"post"})
     * @Assert\NotBlank()
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case lette"
     * )
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *   "this.getPlainPassword() === this.getRetypedPassword()",
     *   message="Passwords does not match"
     * )
     * @Groups({"post"})
     */
    private $retypedPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case lette"
     * )
     * @Groups({"put_reset_password"})
     */
    private $newPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *   "this.getNewPassword() === this.getNewRetypedPassword()",
     *   message="Passwords does not match"
     * )
     * @Groups({"put_reset_password"})
     */
    private $newRetypedPassword;

    /**
     * @Assert\NotBlank()
     * @UserPassword()
     * @Groups({"put_reset_password"})
     */
    private $oldPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "get:admin", "get:owner"})
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
      * @Groups("get")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="author")
     * @Groups("get")
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
     * @return mixed
     */
    public function getPlainPassword():?string {
      return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword(string $plainPassword): self {
      $this->plainPassword = $plainPassword;
      return $this;
    }

    /**
     * @return mixed
     */
    public function getRetypedPassword():?string {
      return $this->retypedPassword;
    }

    /**
     * @param mixed $retypedPassword
     */
    public function setRetypedPassword(string $retypedPassword): self {
      $this->retypedPassword = $retypedPassword;
      return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
      return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
      $this->newPassword = $newPassword;
    }

    /**
     * @return mixed
     */
    public function getNewRetypedPassword()
    {
      return $this->newRetypedPassword;
    }

    /**
     * @param mixed $newRetypedPassword
     */
    public function setNewRetypedPassword($newRetypedPassword)
    {
      $this->newRetypedPassword = $newRetypedPassword;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
      return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
      $this->oldPassword = $oldPassword;
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
