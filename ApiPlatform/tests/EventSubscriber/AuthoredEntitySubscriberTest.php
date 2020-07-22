<?php
namespace App\Test\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\EventSubscriber\UserConfirmationSubscriber;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument\Token\TokenInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class AuthoredEntitySubscriberTest extends TestCase {
  public function testConfiguration(){
    $result = UserConfirmationSubscriber::getSubscribedEvents();

    $this->assertArrayHasKey(KernelEvents::VIEW, $result);
    $this->assertEquals(
      ['confirmUser', EventPriorities::POST_VALIDATE],
      $result[KernelEvents::VIEW]
    );
  }

  public function testSetAuthor(){
    $tokenMock = $this->getMockBuilder(TokenInterface::class)
      ->getMockForAbstractClass();

    $tokenMock
      ->expects($this->once())
      ->method('getUser')
      ->willReturn(new User())
    ;

    $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
      ->getMockForAbstractClass();

    $tokenStorageMock
      ->expects($this->once())
      ->method('getToken')
      ->willReturn($tokenMock)
    ;
  }
}