<?php

namespace GMI\Bundle\RecommendationBundle\Entity;

use GMI\Bundle\RecommendationBundle\Model\CloudLogs;

class BaseCloudLogs extends CloudLogs {

    protected $cloud;

    /**
     * @return mixed
     */
    public function getCloud()
    {
        return $this->cloud;
    }

    /**
     * @param mixed $cloud
     */
    public function setCloud($cloud)
    {
        $this->cloud = $cloud;
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