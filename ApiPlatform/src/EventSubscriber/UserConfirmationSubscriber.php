<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserConfirmationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface {
  private UserConfirmationService $userConfirmation;

  public function __construct(
    UserConfirmationService $userConfirmation
  ){

    $this->userConfirmation = $userConfirmation;
  }

  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
    ];
  }

  public function confirmUser(ViewEvent $event){
    $request = $event->getRequest();

    if('api_user_confirmations_post_collection' !== $request->attributes->get('_route')){
      return;
    }

    $confirmationToken = $event->getControllerResult()->confirmationToken;
    $this->userConfirmation->confirmUser($confirmationToken);

    $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
  }
}