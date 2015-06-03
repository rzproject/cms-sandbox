<?php

namespace GMI\Bundle\RecommendationBundle\Events\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use GMI\Bundle\RecommendationBundle\Services\SessionManager;
use GMI\Bundle\RecommendationBundle\Events\Events;
use GMI\Bundle\RecommendationBundle\Events\CloudUpdateEvent;

class CloudUpdateSubscriber implements EventSubscriberInterface
{
    protected $sessionManager;

    /**
     * @inherit
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::CLOUD_UPDATE  => array('onCloudUpdate')
        );
    }

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function onCloudUpdate(CloudUpdateEvent $event)
    {

//        $request      = $event->getRequest();
//        $enabled      = $this->sessionManager->isEnabled();
//        $allowedRoute = $this->sessionManager->isAllowedInRoute($request->get('_route'));
//
//        if (!($enabled && $allowedRoute)) {
//            return false;
//        }
//
//        if (!$this->cloud) {
//            return;
//        }
//
//        $parameters = $event->getParameters();
//        if ($this->sessionManager->getConfig()->isEnabled()) {
//            //$this->sessionManager->updateCloud();
//        }
    }
}
