<?php
namespace App\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends JWTTokenAuthenticator {

  /**
   * @param mixed $preAuthToken
   * @param UserProviderInterface $userProvider
   * @return \Symfony\Component\Security\Core\User\UserInterface|void|null
   */
  public function getUser($preAuthToken, UserProviderInterface $userProvider)
  {
    /** @var User $user */
    $user = parent::getUser($preAuthToken, $userProvider);

    //dd($preAuthToken->getPayload());
    if($user->getPasswordChangeDate() && $preAuthToken->getPayload()['iat'] < $user->getPasswordChangeDate()){
      throw new ExpiredTokenException();
    }

    return $user;
  }

}