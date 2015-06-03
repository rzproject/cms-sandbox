<?php

namespace GMI\Bundle\RecommendationBundle\Entity;

use GMI\Bundle\RecommendationBundle\Model\Cloud;
use Doctrine\Common\Collections\ArrayCollection;

class BaseCloud extends Cloud
{
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->data     = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Pre Persist method
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Pre Update method
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}