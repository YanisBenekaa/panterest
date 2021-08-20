<?php

namespace App\EventSubscriber;

use App\Entity\Pin;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'setPinDateAndUser',
        ];
    }

    public function setPinDateAndUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Pin)) {
            return;
        }

        $now = new DateTimeImmutable('now');
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);

        $user = $this->security->getUser();
        $entity->setUser($user);
    }
}
