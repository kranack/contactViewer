<?php

namespace AppBundle\Entity;

class Mailer {

  private $mailer;

  public function __construct($mailer) {
    $this->mailer = $mailer;
  }

  public function alertNewContact ($contact) {
      $message = \Swift_Message::newInstance()
       ->setSubject('New contact registred')
       ->setFrom('no-reply@example.com')
       ->setTo('admin@example.com')
       ->setBody(
           $this->renderView( 
               // app/Resources/views/Emails/registration.html.twig
               'Emails/registration.html.twig',
               array('name' => sprintf("%s %s", $contact->getName, $contact->getLastname))
           ),
           'text/html'
       )
   ;
   $this->mailer->send($message);
  }

}
