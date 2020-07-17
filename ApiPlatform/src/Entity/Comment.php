<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource(
 *   itemOperations={
 *     "get",
 *     "put" = {
 *       "security" = "is_granted('IS_AUTHENTICATED_FULLY') && object.getAuthor() === user"
 *     }
 *   },
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "security" = "is_granted('IS_AUTHENTICATED_FULLY')"
 *     }
 *   },
 *   denormalizationContext={
 *     "groups"={"post"}
 *   },
 * )
 */
class Comment implements AuthoredEntityInterface {
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=300)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=BlogPost::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getPost(): ?BlogPost {
      return $this->post;
    }

    public function setPost(?BlogPost $post): self {
      $this->post = $post;

      return $this;
    }
}
