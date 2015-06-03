<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use GMI\Bundle\RecommendationBundle\Model\CloudLogsInterface;

interface CloudLogsManagerInterface
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
     * @param CloudLogsInterface $cloudLogs
     *
     */
    function delete(CloudLogsInterface $cloudLogs);

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
     * @param CloudLogsInterface $cloudLogs
     *
     */
    function save(CloudLogsInterface $cloudLogs);
}
