<?php

namespace App\EventSubscriber;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventSubscriber implements EventSubscriberInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private FlashyNotifier $flashy;

    public function __construct(UrlGeneratorInterface $urlGenerator, FlashyNotifier $flashy)
    {
        $this->urlGenerator = $urlGenerator;
        $this->flashy = $flashy;
    }

    public function onLogoutEvent(LogoutEvent $event)
    {
        $this->flashy->info('Good Bye !');

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
