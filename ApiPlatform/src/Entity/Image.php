<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
   * @Vich\UploadableField(mapping="uploads", fileNameProperty="url")
   */
  private $file;

  /**
   * @ORM\Column(nullable=true)
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
    return $this->url;
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