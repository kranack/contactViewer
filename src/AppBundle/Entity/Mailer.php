<?php

namespace AppBundle\Entity;

class Mailer {

  private $mailer;

  private $renderedView;

  public function __construct($mailer, $renderedView) {
    $this->mailer = $mailer;
    $this->renderedView = $renderedView;
  }

  public function alertNewContact () {
      $message = \Swift_Message::newInstance()
       ->setSubject('New contact registred')
       ->setFrom('no-reply@example.com')
       ->setTo('admin@example.com')
       ->setBody(
           $this->renderedView,
           'text/html'
       )
   ;
   $this->mailer->send($message);
  }

}
