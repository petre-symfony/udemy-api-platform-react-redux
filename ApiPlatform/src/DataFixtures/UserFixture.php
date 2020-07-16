<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixture extends BaseFixture {
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder){
    $this->passwordEncoder = $passwordEncoder;
  }

  protected function loadData(ObjectManager $manager) {
    $this->createMany(10, 'main_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_USER']);

      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'engage'
      ));

      return $user;
    });
    $manager->flush();
  }
}