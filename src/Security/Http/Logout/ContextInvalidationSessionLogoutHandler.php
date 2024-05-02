<?php

/*
 * This file is part of the FOSHttpCacheBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\HttpCacheBundle\Security\Http\Logout;

use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\HttpCacheBundle\UserContextInvalidator;

final class ContextInvalidationSessionLogoutHandler implements EventSubscriberInterface
{
    private UserContextInvalidator $invalidator;

    public function __construct(UserContextInvalidator $invalidator)
    {
        $this->invalidator = $invalidator;
    }

    public function onLogout(LogoutEvent $event): void
    {
        if ($event->getRequest()->hasSession()) {
            $this->invalidator->invalidateContext($event->getRequest()->getSession()->getId());
            $event->getRequest()->getSession()->invalidate();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}
