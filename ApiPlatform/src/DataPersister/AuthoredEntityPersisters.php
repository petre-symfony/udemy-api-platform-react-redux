<?php
namespace App\DataPersister;

/**
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\AuthoredEntityInterface;
use App\Entity\Comment;
use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
*/
class AuthoredEntityPersisters /** implements DataPersisterInterface */{
  /**
   * @var EntityManagerInterface
   */
  #private $em;
  /**
   * @var Security
   */
  #private $security;
  /**
  public function __construct(
    Security $security,
    EntityManagerInterface $em
  ){
    $this->em = $em;
    $this->security = $security;
  }

  public function supports($data): bool {
    return $data instanceof AuthoredEntityInterface;
  }
  */
  /**
   * @param Comment|BlogPost $data
   * @return object|void
   */
  /**
  public function persist($data){
    $user = $this->security->getUser();
    if($user instanceof User){
      $data->setAuthor($user);
    }
    $this->em->persist($data);
    $this->em->flush();
  }

  public function remove($data){
    $this->em->remove($data);
    $this->em->flush();
  }
   */
}