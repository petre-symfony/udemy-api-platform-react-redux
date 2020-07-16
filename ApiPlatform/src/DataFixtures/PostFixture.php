<?php


namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends BaseFixture implements DependentFixtureInterface {
  protected function loadData(ObjectManager $manager) {
    $this->createMany(100, 'posts', function(){
      $post = new BlogPost();
      $post->setTitle($this->faker->unique()->realText($this->faker->numberBetween(10, 20)));
      $post->setContent($this->faker->realText($this->faker->numberBetween(50, 100)));
      $post->setPublished($this->faker->dateTimeBetween('-30 days'));
      $post->setAuthor($this->getRandomReference('main_users'));

      return $post;
    });

    $manager->flush();
  }

  public function getDependencies(){
    return [UserFixture::class];
  }
}