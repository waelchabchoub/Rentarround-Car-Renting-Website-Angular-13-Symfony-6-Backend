<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Security;


class JWTCreatedListener
{
    public function __construct(Security $security)
    {
        $this->security =$security;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload= $event->getData();
        $payload['user_id'] = $this->security->getUser()->getId();
        $payload['user_image'] = $this->security->getUser()->getImage();
        $payload['expire_in'] = $_ENV['TTL'];
        $event->setData($payload);
    }
}