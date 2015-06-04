<?php

namespace GMI\Bundle\RecommendationBundle\Model;


interface CloudTagInterface
{
    /**
     * @return mixed
     */
    public function getPriority();

    /**
     * @param mixed $priority
     */
    public function setPriority($priority);

    /**
     * @return mixed
     */
    public function getEnabled();

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt);
}