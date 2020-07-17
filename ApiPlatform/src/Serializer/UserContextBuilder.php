<?php
namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserContextBuilder implements SerializerContextBuilderInterface {
  /**
   * @var SerializerContextBuilderInterface
   */
  private $decorated;
  /**
   * @var AuthorizationCheckerInterface
   */
  private $authorizationChecker;

  public function __construct(
    SerializerContextBuilderInterface $decorated,
    AuthorizationCheckerInterface $authorizationChecker
  ){
    $this->decorated = $decorated;
    $this->authorizationChecker = $authorizationChecker;
  }

  public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array{
    $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

    //class being serialized, deserialized
    $resourceClass = $context['resource_class'] ?? null; //defualt to null if not set

    if(
      User::class === $resourceClass &&
      $this->authorizationChecker->isGranted('ROLE_ADMIN') &&
      isset($context['groups']) &&
      $normalization === true
    ) {
      $context['groups'][]='get:admin';
    }

    return $context;
  }

}