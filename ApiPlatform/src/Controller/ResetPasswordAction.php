<?php
namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction {
  private $validator;
  private $passwordEncoder;

  public function __construct(
    ValidatorInterface $validator,
    UserPasswordEncoderInterface $passwordEncoder
  )
  {

    $this->validator = $validator;
    $this->passwordEncoder = $passwordEncoder;
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
    return $data;

    //Validator is only called after we return the data from this action
    //Only here it checks for user current password, but we've just modified it
    //Entity is persisted automatically, only validation pass
  }
}