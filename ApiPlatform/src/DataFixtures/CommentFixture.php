<?php
namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixture extends BaseFixture  implements DependentFixtureInterface {
  protected function loadData(ObjectManager $manager){
    $this->createMany(50, 'comments', function() {
      $comment = new Comment();
      $comment->setCreatedAt($this->faker->dateTimeBetween('-30 days'));
      $comment->setContent($this->faker->unique()->realText($this->faker->numberBetween(20, 40)));
      $comment->setAuthor($this->getRandomReference('comentator_users'));
      $comment->setPost($this->getRandomReference('posts'));

      return $comment;
    });

    $this->createMany(50, 'comments', function() {
      $comment = new Comment();
      $comment->setCreatedAt($this->faker->dateTimeBetween('-30 days'));
      $comment->setContent($this->faker->unique()->realText($this->faker->numberBetween(20, 40)));
      $comment->setAuthor($this->getRandomReference('writer_users'));
      $comment->setPost($this->getRandomReference('posts'));

      return $comment;
    }, 50);

    $manager->flush();

  }

  public function getDependencies(){
    return [
      UserFixture::class,
      PostFixture::class
    ];
  }

}