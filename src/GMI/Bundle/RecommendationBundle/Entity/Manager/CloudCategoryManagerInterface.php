<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use GMI\Bundle\RecommendationBundle\Model\CloudCategoryInterface;

interface CloudCategoryManagerInterface
{
    /**
     * Creates an empty CloudCategory instance
     *
     * @return CloudCategoryInterface
     */
    function create();

    /**
     * Deletes a CloudCategory
     *
     * @param CloudCategoryInterface $cloudCategory
     *
     * @return void
     */
    function delete(CloudCategoryInterface $cloudCategory);

    /**
     * Finds one CloudCategory by the given criteria
     *
     * @param array $criteria
     *
     * @return CloudCategoryInterface
     */
    function findOneBy(array $criteria);

    /**
     * Finds one CloudCategory by the given criteria
     *
     * @param array $criteria
     *
     * @return CloudCategoryInterface
     */
    function findBy(array $criteria);

    /**
     * Returns the CloudCategory fully qualified class name
     *
     * @return string
     */
    function getClass();

    /**
     * Save a CloudCategory
     *
     * @param CloudCategoryInterface $cloudCategory
     *
     * @return void
     */
    function save(CloudCategoryInterface $cloudCategory);
}
