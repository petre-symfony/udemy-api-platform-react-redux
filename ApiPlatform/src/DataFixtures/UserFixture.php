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
    $this->createMany(5, 'writer_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_WRITER']);
      $user->setEnabled(true);

      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'engage'
      ));

      return $user;
    });

    $this->createMany(5, 'comentator_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_COMENTATOR']);
      $user->setEnabled(true);

      $user->setPassword($this->passwordEncoder->encodePassword(
          $user,
          'engage'
      ));


      return $user;
    });

    $this->createMany(5, 'editor_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_EDITOR']);
      $user->setEnabled(true);

      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'engage'
      ));


      return $user;
    });

    $this->createMany(5, 'admin_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_ADMIN']);
      $user->setEnabled(true);

      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'engage'
      ));


      return $user;
    });

    $this->createMany(5, 'superadmin_users', function($i) use ($manager) {
      $user = new User();
      $firstName = $this->faker->unique()->firstName;
      $lastName = $this->faker->lastName;
      $user->setEmail($firstName . '@gmail.com');
      $user->setName($firstName . ' ' . $lastName);
      $user->setUsername(str_replace(' ', '_', $user->getName()));
      $user->setRoles(['ROLE_SUPERADMIN']);
      $user->setEnabled(true);

      $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        'engage'
      ));

      return $user;
    });

    $manager->flush();
  }
}