<?php
namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface {
  /**
   * @var UserPasswordEncoderInterface
   */
  private $passwordEncoder;
  /**
   * @var EntityManagerInterface
   */
  private $em;

  public function __construct(
    UserPasswordEncoderInterface $passwordEncoder,
    EntityManagerInterface $em
  ){
    $this->passwordEncoder = $passwordEncoder;
    $this->em = $em;
  }

  public function supports($data): bool {
    return $data instanceof User;
  }

  /**
   * @param User $data
   * @return object|void
   */
  public function persist($data){
    if($data->getPlainPassword()){
      $data->setPassword(
        $this->passwordEncoder->encodePassword($data, $data->getPlainPassword())
      );

      $data->eraseCredentials();
    }
    $this->em->persist($data);
    $this->em->flush();
  }

  public function remove($data){
    $this->em->remove($data);
    $this->em->flush();
  }

}