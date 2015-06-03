<?php

namespace GMI\Bundle\RecommendationBundle\Events\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use GMI\Bundle\RecommendationBundle\Services\SessionManager;

class AuthenticationListener
{
	protected $sessionManager;
	
	public function __construct(SessionManager $sessionManager)
	{
		$this->sessionManager = $sessionManager;
	}
	
    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
//		if ($this->sessionManager->getConfig()->isEnabled()) {
//			//$this->sessionManager->updateCloud();
//		}
	}
}
