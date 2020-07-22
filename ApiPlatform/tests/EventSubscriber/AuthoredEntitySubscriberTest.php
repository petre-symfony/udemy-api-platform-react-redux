<?php
namespace App\Test\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\User;
use App\EventSubscriber\AuthoredEntitySubscriber;
use App\EventSubscriber\UserConfirmationSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use PHPUnit\Framework\MockObject\MockObject;

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
    $entityMock = $this->getMockBuilder(BlogPost::class)
      ->setMethods(['setAuthor'])
      ->getMock()
    ;

    $entityMock->expects($this->once())
      ->method('setAuthor');

    $tokenStorageMock = $this->getTokenStorageMock();
    $eventMock = $this->getEventMock('POST', $entityMock);

    (new AuthoredEntitySubscriber($tokenStorageMock))->getAuthenticatedUser($eventMock);

    $tokenStorageMock = $this->getTokenStorageMock();
    $eventMock = $this->getEventMock('GET', new BlogPost());

    (new AuthoredEntitySubscriber($tokenStorageMock))->getAuthenticatedUser($eventMock);
  }

  /**
   * @return MockObject|TokenStorageInterface
   */
  private function getTokenStorageMock(): \PHPUnit\Framework\MockObject\MockObject
  {
    $tokenMock = $this->getMockBuilder(TokenInterface::class)
      ->getMockForAbstractClass();

    $tokenMock
      ->expects($this->once())
      ->method('getUser')
      ->willReturn(new User());

    $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
      ->getMockForAbstractClass();

    $tokenStorageMock
      ->expects($this->once())
      ->method('getToken')
      ->willReturn($tokenMock);
    return $tokenStorageMock;
  }

  /**
   * @return MockObject
   */
  private function getEventMock(string $method, $controllerResult): MockObject
  {
    $requestMock = $this->getMockBuilder(Request::class)
      ->getMock();
    $requestMock->expects($this->once())
      ->method('getMethod')
      ->willReturn($method);

    $eventMock = $this->getMockBuilder(ViewEvent::class)
      ->disableOriginalConstructor()
      ->getMock();

    $eventMock->expects($this->once())
      ->method('getControllerResult')
      ->willReturn($controllerResult)
    ;
    $eventMock->expects($this->once())
      ->method('getRequest')
      ->willReturn($requestMock);

    return $eventMock;
  }
}