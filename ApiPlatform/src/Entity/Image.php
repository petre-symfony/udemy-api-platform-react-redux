<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\UploadImageAction;

/**
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *   collectionOperations={
 *     "get",
 *     "post"={
 *       "method"="POST",
 *       "path"="/images",
 *       "controller"=UploadImageAction::class,
 *       "defaults" = {
 *         "_api_receive"=false
 *       }
 *     }
 *   }
 * )
 */
class Image {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @Vich\UploadableField(mapping="images", fileNameProperty="url")
   * @Assert\NotNull()
   */
  private $file;

  /**
   * @ORM\Column(nullable=true)
   * @Groups({"get_post_with_author"})
   */
  private $url;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
    return '/uploads/images/' . $this->url;
  }

  /**
   * @return mixed
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * @param mixed $file
   */
  public function setFile($file): void
  {
    $this->file = $file;
  }

  /**
   * @param mixed $url
   */
  public function setUrl($url): void
  {
    $this->url = $url;
  }
}