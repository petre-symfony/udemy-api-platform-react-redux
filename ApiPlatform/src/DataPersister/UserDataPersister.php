<?php
namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface {
  private $passwordEncoder;

  private $em;
  private $requestStack;


  public function __construct(
    UserPasswordEncoderInterface $passwordEncoder,
    EntityManagerInterface $em,
    RequestStack $requestStack
  ){
    $this->passwordEncoder = $passwordEncoder;
    $this->em = $em;

    $this->requestStack = $requestStack;
  }

  public function supports($data): bool {
    return $data instanceof User;
  }

  /**
   * @param User $data
   * @return object|void
   */
  public function persist($data){
    if($this->requestStack->getCurrentRequest()->isMethod('PUT')){
      return;
    };

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