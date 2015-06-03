<?php

namespace GMI\Bundle\RecommendationBundle\Entity;

use GMI\Bundle\RecommendationBundle\Model\CloudProfile;

class BaseCloudProfile extends CloudProfile {

    protected $category;
    protected $profileType;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getProfileType()
    {
        return $this->profileType;
    }

    /**
     * @param mixed $profileType
     */
    public function setProfileType($profileType)
    {
        $this->profileType = $profileType;
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