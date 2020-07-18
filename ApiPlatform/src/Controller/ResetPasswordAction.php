<?php
namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction {
  private $validator;
  private $passwordEncoder;
  private $entityManager;
  private $tokenManager;

  public function __construct(
    ValidatorInterface $validator,
    UserPasswordEncoderInterface $passwordEncoder,
    EntityManagerInterface $entityManager,
    JWTTokenManagerInterface $tokenManager
  )
  {

    $this->validator = $validator;
    $this->passwordEncoder = $passwordEncoder;
    $this->entityManager = $entityManager;
    $this->tokenManager = $tokenManager;
  }

  public function __invoke(User $data)
  {
    // $reset = new ResetPasswordAction()
    // $reset()

    //Validator is only called after we return the data from this action
    $this->validator->validate($data);

    $data->setPassword(
      $this->
        passwordEncoder->
        encodePassword($data, $data->getNewPassword())
    );

    //After password change tokens are still valid
    $this->entityManager->flush();
    $token = $this->tokenManager->create($data);

    return new JsonResponse(['token' => $token]);

    //Validator is only called after we return the data from this action
    //Only here it checks for user current password, but we've just modified it
  }
}