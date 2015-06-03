<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use GMI\Bundle\RecommendationBundle\Model\CloudProfileInterface;

interface CloudProfileManagerInterface
{
    /**
     * Creates an empty Cloud instance
     *
     * @return CloudInterface
     */
    function create();

    /**
     * Deletes a Cloud
     *
     * @param CloudProfileInterfaceInterface $cloudProfile
     *
     */
    function delete(CloudProfileInterface $cloudProfile);

    /**
     * Finds one Cloud by the given criteria
     *
     * @param array $criteria
     *
     * @return CloudInterface
     */
    function findOneBy(array $criteria);

    /**
     * Finds one Cloud by the given criteria
     *
     * @param array $criteria
     *
     * @return CloudInterface
     */
    function findBy(array $criteria);

    /**
     * Returns the Cloud's fully qualified class name
     *
     * @return string
     */
    function getClass();

    /**
     * Save a Cloud
     *
     * @param CloudProfileInterfaceInterface $cloudProfile
     *
     */
    function save(CloudProfileInterface $cloudProfile);
}
