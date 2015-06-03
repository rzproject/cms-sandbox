<?php

namespace GMI\Bundle\RecommendationBundle\Events\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use GMI\Bundle\RecommendationBundle\Services\SessionManager;
use Rz\UserBundle\RzUserEvents;
use Rz\UserBundle\Event\RzUserEvent;

class UserRegistrationEventSubscriber implements EventSubscriberInterface
{
	protected $profileSessionManager;
	protected $beforeAuthUid;
	
	public function __construct(SessionManager $profileSessionManager)
	{
		$this->profileSessionManager = $profileSessionManager;
		$this->beforeAuthUid         = null;
	}
	
	/**
	 * @inherit
	 */
    public static function getSubscribedEvents()
    {
        return array(
            RzUserEvents::BEFORE_REGISTRATION_AUTH => array('onBeforeRegistrationAuth'),
            RzUserEvents::AFTER_REGISTRATION_AUTH  => array('onAfterRegistrationAuth')
        );
    }
    
    public function onBeforeRegistrationAuth(RzUserEvent $event)
    {
		if (!($cloud = $this->profileSessionManager->getCloud())) {
			$cloud = $this->profileSessionManager->loadCloud();
		}
		if ($cloud && $cloud->getUid()) {
			$this->beforeAuthUid = $cloud->getUid();
		}
	}
	
    public function onAfterRegistrationAuth(RzUserEvent $event)
    {
		$user = $event->getUser();
		if ($this->beforeAuthUid) {
			$this->profileSessionManager->linkSessionCloudToUser($user);
		}
	}
    
}
