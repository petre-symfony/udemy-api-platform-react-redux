<?php
namespace App\Test\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\EventSubscriber\AuthoredEntitySubscriber;
use App\EventSubscriber\UserConfirmationSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
      ->expects($this->exactly(2))
      ->method('getUser')
      ->willReturn(new User())
    ;

    $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
      ->getMockForAbstractClass();

    $tokenStorageMock
      ->expects($this->never())
      ->method('getToken')
      ->willReturn($tokenMock)
    ;

    $eventMock = $this->getMockBuilder(ViewEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    (new AuthoredEntitySubscriber($tokenStorageMock))->getAuthenticatedUser($eventMock);
  }
}