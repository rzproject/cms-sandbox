<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Doctrine\ORM\EntityManager;
use GMI\Bundle\RecommendationBundle\Model\CloudProfileInterface;


class CloudProfileManager implements CloudProfileManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param string                      $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em     = $em;
        $this->class  = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function save(CloudProfileInterface $cloudProfile)
    {
        $this->em->persist($cloudProfile);
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {
        return $this->em->getRepository($this->class)->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return $this->em->getRepository($this->class)->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(CloudProfileInterface $cloudProfile)
    {
        $this->em->remove($cloudProfile);
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        return new $this->class;
    }
}
