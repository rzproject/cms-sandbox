<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Sonata\CoreBundle\Model\BaseEntityManager;

class CloudTagManager extends BaseEntityManager
{
    public function getActiveCloudTags()
    {
        $queryBuilder = $this->em->getRepository($this->class)->createQueryBuilder('ct')
							->select('ct.id, ct.id, ct.priority, e.slug')
							->join('ct.tag', 't')
                            ->join('ct.event', 'e')
							->where('ct.enabled = true')
							->orderBy('ct.priority', 'DESC');
		
		$query = $queryBuilder->getQuery();
		$query->useResultCache(true, 3600);
		$result = $query->getScalarResult();

        return $result;
    }
}
