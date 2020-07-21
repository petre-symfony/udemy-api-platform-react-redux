<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 * @ApiResource(
 *   attributes={"order"={"createdAt": "DESC"}},
 *   itemOperations={
 *     "get" = {
 *       "normalization_context"={
 *         "groups" = {"get_post_with_author"}
 *       }
 *     },
 *     "put" = {
 *       "security" = "is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') && object.getAuthor() === user)"
 *     }
 *   },
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "security" = "is_granted('ROLE_WRITER')"
 *     }
 *   },
 *   denormalizationContext={
 *     "groups"={"post"}
 *   },
 * )
 */
class BlogPost implements AuthoredEntityInterface {
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_post_with_author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     * @Groups({"post", "get_post_with_author"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=20)
     * @Groups({"post"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     * @Groups({"get_post_with_author"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_post_with_author"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     * @ApiSubresource()
     * @Groups({"get_post_with_author"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class)
     * @ORM\JoinTable()
     * @Groups({"post", "get_post_with_author"})
     * @ApiSubresource()
     */
    private $images;

    public function __construct()
    {
      $this->comments = new ArrayCollection();
      $this->images = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(?string $slug): self {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection {
      return $this->comments;
    }

  /**
   * @Groups({"get_post_with_author"})
   */
    public function getCreated(): ?\DateTime{
      return $this->getCreatedAt();
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }
}
