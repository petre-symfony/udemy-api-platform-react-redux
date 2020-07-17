<?php
namespace App\Serializer\Normalizer;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class UserNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface {
  use SerializerAwareTrait;
  private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';

  private $tokenStorage;

  public function __construct(TokenStorageInterface $tokenStorage){
    $this->tokenStorage = $tokenStorage;
  }

  public function supportsNormalization($data, string $format = null, array $context = [])
  {
    if (isset($context[self::ALREADY_CALLED])){
      return false;
    }

    return $data instanceof User;
  }

  /**
   * @param User $object
   */
  public function normalize($object, string $format = null, array $context = []){
    if($this->isUserHimself($object)){
      $context['groups'][]='get:owner';
    }

    return $this->passOn($object, $format, $context);
  }

  /**
   * @param User $object
   * @return bool
   */
  private function isUserHimself($object){
    return $this->tokenStorage->getToken()->getUsername() === $object->getUsername();
  }

  private function passOn($object, $format, $context){
    if(!$this->serializer instanceof NormalizerInterface){
      throw new \LogicException(sprintf('Cannot normalize object "%s" because the injected serializer is not a normalizer',$object));
    }

    $context[self::ALREADY_CALLED] = true;

    return $this->serializer->normalize($object, $format, $context);
  }
}