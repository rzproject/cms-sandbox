<?php

namespace GMI\Bundle\RecommendationBundle\Events\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use GMI\Bundle\RecommendationBundle\Services\SessionManager;

class KernelEventSubscriber implements EventSubscriberInterface
{
	protected $sessionManager;
	
	public function __construct(SessionManager $sessionManager)
	{
		$this->sessionManager = $sessionManager;
	}
	
	/**
	 * @inherit
	 */
    public static function getSubscribedEvents()
    {
        return array(
			'kernel.request'  => array('onKernelRequest'),
            'kernel.response' => array(
				array('onKernelResponsePre',  5),
				array('onKernelResponsePost', 1),
			)
        );
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
			return;
		}
		$this->sessionManager->loadCloud();
	}
	
    public function onKernelResponsePre(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
			return;
		}
		// TODO: for future use
	}
	
    public function onKernelResponsePost(FilterResponseEvent $event)
    {
//		$this->sessionManager->updateCloud($event->getResponse());
	}
}
