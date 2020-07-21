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
 *   attributes={
 *     "order"={"createdAt": "DESC"}
 *     "pagination_client_enabled"=true,
 *     "pagination_client_items_per_page"=true
 *   },
 *   itemOperations={
 *     "get",
 *     "put" = {
 *       "security" = "is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMENTATOR') && object.getAuthor() === user)"
 *     }
 *   },
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "security" = "is_granted('ROLE_COMENTATOR')"
 *     }
 *   },
 *   subresourceOperations = {
 *     "api_blog_posts_comments_get_subresource" = {
 *       "normalization_context"={
 *         "groups" = {"get_comments_of_post_with_author"}
 *       }
 *     }
 *   },
 *   denormalizationContext={
 *     "groups"={"post_comment"}
 *   },
 * )
 */
class Comment implements AuthoredEntityInterface {
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_comments_of_post_with_author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post_comment", "get_comments_of_post_with_author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=300)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_comments_of_post_with_author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=BlogPost::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post_comment"})
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

  /**
   * @Groups({"get_comments_of_post_with_author"})
   */
    public function getCreated():\DateTime {
      return $this->getCreatedAt();
    }
}
