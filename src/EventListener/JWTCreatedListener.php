<?php
// src/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\SecurityBundle\Security;

class JWTCreatedListener
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository
    ) {}

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();

        $user = $this->userRepository->findOneBy(["email" => $payload["username"]]);

        if ($user) {
            $payload['user_id'] = $user->getId();
        }

        $event->setData($payload);
    }
}
