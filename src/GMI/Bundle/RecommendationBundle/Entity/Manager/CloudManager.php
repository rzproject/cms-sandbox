<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\EntityManager;
use GMI\Bundle\RecommendationBundle\Model\CloudInterface;

class CloudManager implements CloudManagerInterface
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
    public function save(CloudInterface $cloud)
    {
        $this->em->persist($cloud);
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
    public function delete(CloudInterface $cloud)
    {
        $this->em->remove($cloud);
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
	
    public function getExpiredCloud($max_days = 7)
    {
		$expiration = new \DateTime();
        $expiration->sub(date_interval_create_from_date_string(sprintf('%s days', $max_days)));
        
        $queryBuilder = $this->em->getRepository($this->class)->createQueryBuilder('c');
        
        try {
			$queryBuilder = $queryBuilder
									 ->select('c')
									 ->where('c.createdAt <= :expiration')
									 ->andWhere('c.user is null')
									 ->setParameter('expiration',$expiration);
			
			$query = $queryBuilder->getQuery();
			$result = $query->getResult();
			return $result;
		} catch (NoResultException $e) {
			return null;
		}
    }
}
