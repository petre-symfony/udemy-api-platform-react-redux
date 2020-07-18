<?php
namespace App\Controller;


use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResetPasswordAction {
  /**
   * @var ValidatorInterface
   */
  private $validator;

  public function __construct(ValidatorInterface $validator)
  {

    $this->validator = $validator;
  }

  public function __invoke(User $data)
  {
    // $reset = new ResetPasswordAction()
    // $reset()
    dd(
      $data->getOldPassword(),
      $data->getNewRetypedPassword(),
      $data->getNewPassword(),
      $data->getRetypedPassword()
    );
    //Validator is only called after we return the data from this action
    $this->validator->validate($data);
  }
}