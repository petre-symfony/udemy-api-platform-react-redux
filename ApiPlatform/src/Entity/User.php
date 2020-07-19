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
use App\Controller\ResetPasswordAction;

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
 *     },
 *     "put_reset_password" = {
 *       "security" = "is_granted('IS_AUTHENTICATED_FULLY') && object === user",
 *       "method"="PUT",
 *       "path"="/users/{id}/reset-password",
 *       "controller"=ResetPasswordAction::class,
 *       "denormalization_context"={
 *         "groups"={"put_reset_password"}
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
      * @Assert\NotBlank(groups={"post"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups({"get", "put", "post", "get_comments_of_post_with_author", "get_post_with_author"})
      * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min=5, max=225, groups={"post", "put"})
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
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case lette",
     *   groups={"post"}
     * )
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Expression(
     *   "this.getPlainPassword() === this.getRetypedPassword()",
     *   message="Passwords does not match",
     *   groups={"post"}
     * )
     * @Groups({"post"})
     */
    private $retypedPassword;

    /**
     * @Assert\NotBlank(groups={"put_reset_password"})
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case lette",
     *   groups={"put_reset_password"}
     * )
     * @Groups({"put_reset_password"})
     */
    private $newPassword;

    /**
     * @Assert\NotBlank(groups={"put_reset_password"})
     * @Assert\Expression(
     *   "this.getNewPassword() === this.getNewRetypedPassword()",
     *   message="Passwords does not match",
     *   groups={"put_reset_password"}
     * )
     * @Groups({"put_reset_password"})
     */
    private $newRetypedPassword;

    /**
     * @Assert\NotBlank(groups={"put_reset_password"})
     * @UserPassword(groups={"put_reset_password"})
     * @Groups({"put_reset_password"})
     */
    private $oldPassword;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "get:admin", "get:owner"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Email(groups={"post", "put"})
     * @Assert\Length(min=6, max=255, groups={"post", "put"})
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $confirmationToken;

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
        $this->enabled = false;
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
        // guarantee every user at least has ROLE_COMENTATOR
        $roles[] = 'ROLE_COMENTATOR';

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
    public function getNewPassword():?string
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
    public function getNewRetypedPassword():?string
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
    public function getOldPassword():?string
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

    /**
     * @return mixed
     */
    public function getPasswordChangeDate()
    {
      return $this->passwordChangeDate;
    }

    /**
     * @param mixed $passwordChangeDate
     */
    public function setPasswordChangeDate($passwordChangeDate)
    {
      $this->passwordChangeDate = $passwordChangeDate;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
      return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
      $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
      return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): void
    {
      $this->confirmationToken = $confirmationToken;
    }
}
