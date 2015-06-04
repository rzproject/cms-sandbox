<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Doctrine\ORM\NoResultException;
use Sonata\CoreBundle\Model\BaseEntityManager;

class CloudManager extends BaseEntityManager
{
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
