<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer {

  private $mailer;

  public function __construct(MailerInterface $mailer){
    $this->mailer = $mailer;
  }

  public function sendConfirmationEmail(User $user, $confirmationToken){
    $email = (new TemplatedEmail())
      ->from(new Address('redux@example.com', 'Redux ApiPlatform Blog'))
      ->to(new Address($user->getEmail(), $user->getName()))
      ->subject('Confirm your registration')
      ->htmlTemplate('email/confirmation_registration.html.twig')
      ->context([
        'confirmationToken' => $confirmationToken,
        'user' => $user
      ])
    ;

    $this->mailer->send($email);
  }
}