<?php

namespace GMI\Bundle\RecommendationBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class CloudUpdateEvent extends Event
{
    protected $parameters;
    protected $request;

    public function __construct($parameters = array(), Request $request)
    {
        $this->parameters = $parameters;
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
