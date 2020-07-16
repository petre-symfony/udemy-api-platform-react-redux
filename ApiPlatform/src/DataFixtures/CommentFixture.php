<?php
namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixture extends BaseFixture  implements DependentFixtureInterface {
  protected function loadData(ObjectManager $manager){
    $this->createMany(100, 'comments', function() {
      $comment = new Comment();
      $comment->setPublished($this->faker->dateTimeBetween('-30 days'));
      $comment->setContent($this->faker->unique()->realText($this->faker->numberBetween(20, 40)));
      $comment->setAuthor($this->getRandomReference('main_users'));
      $comment->setPost($this->getRandomReference('posts'));

      return $comment;
    });

    $manager->flush();
  }

  public function getDependencies(){
    return [
      UserFixture::class,
      PostFixture::class
    ];
  }

}