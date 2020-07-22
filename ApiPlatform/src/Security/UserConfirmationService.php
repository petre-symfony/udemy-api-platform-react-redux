<?php

namespace App\Security;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exception\InvalidConfirmationTokenException;

class UserConfirmationService {
  /**
   * @var UserRepository
   */
  private UserRepository $userRepository;
  /**
   * @var EntityManagerInterface
   */
  private EntityManagerInterface $entityManager;

  public function __construct(
    UserRepository $userRepository,
    EntityManagerInterface $entityManager
  ) {
    $this->userRepository = $userRepository;
    $this->entityManager = $entityManager;
  }

  public function confirmUser($confirmationToken){
    /** @var User $user */
    $user = $this->userRepository->findOneBy([
      'confirmationToken' => $confirmationToken
    ]);

    if(!$user){
      throw new InvalidConfirmationTokenException();
    }

    $user->setEnabled(true);
    $user->setConfirmationToken(null);
    $this->entityManager->flush();
  }
}