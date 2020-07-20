<?php
namespace App\Controller;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UploadImageAction {
  private $formFactory;
  private $entityManager;
  private ValidatorInterface $validator;

  public function __construct(
    FormFactoryInterface $formFactory,
    EntityManagerInterface $entityManager,
    ValidatorInterface $validator
  ){
    $this->formFactory = $formFactory;
    $this->entityManager = $entityManager;
    $this->validator = $validator;
  }

  public function __invoke(Request $request){
    // Create an image instance
    $image = new Image();
    // Validate the form
    $form = $this->formFactory->create(null, $image);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      //Persist the new image entity
      $this->entityManager->persist($image);
      $this->entityManager->flush();

      $image->setFile(null);

      return $image;
    }

    // Uploading done for us in background by Vich uploader

    // throw an validation exception, that means something went wrong
    // during form validation
    throw new ValidationException(
      $this->validator->validate($image)
    );
  }
}