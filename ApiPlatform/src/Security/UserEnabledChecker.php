<?php
namespace App\Security;


use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface {
  public function checkPreAuth(UserInterface $user)
  {
    if(!$user instanceof User){
      return;
    }

    if(!$user->isEnabled()){
      throw new CustomUserMessageAccountStatusException('Bad credentials');
    }
  }

  public function checkPostAuth(UserInterface $user)
  {
    // TODO: Implement checkPostAuth() method.
  }

}