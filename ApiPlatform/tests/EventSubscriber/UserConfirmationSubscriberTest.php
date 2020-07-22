<?php
namespace App\Test\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\EventSubscriber\UserConfirmationSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriberTest extends TestCase {
  public function testConfiguration(){
    $result = UserConfirmationSubscriber::getSubscribedEvents();

    $this->assertArrayHasKey(KernelEvents::VIEW, $result);
    $this->assertEquals(
      ['confirmUser', EventPriorities::POST_VALIDATE],
      $result[KernelEvents::VIEW]
    );
  }
}