<?php

namespace App\EventSubscriber;

use App\Entity\UserConnection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(Security $security, EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function ipAndDateConnection(): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return; 
        }

        $request = $this->requestStack->getCurrentRequest();
        $ip = $request ? $request->getClientIp() : '127.0.0.1'; 

        $newConnection = new UserConnection();
        $newConnection->setUser($user);
        $newConnection->setIpAddress($ip);
        $newConnection->setConnectionAt(new \DateTimeImmutable());

        $this->em->persist($newConnection);
        $this->em->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'ipAndDateConnection',
        ];
    }
}
