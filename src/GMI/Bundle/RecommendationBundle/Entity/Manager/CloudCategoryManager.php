<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Sonata\CoreBundle\Model\BaseEntityManager;

class CloudCategoryManager extends BaseEntityManager
{
    public function getActiveCloudCategories()
    {
        $queryBuilder = $this->em->getRepository($this->class)->createQueryBuilder('cc')
							->select('cc.id, c.id, cc.priority, e.slug')
							->join('cc.category', 'c')
                            ->join('cc.event', 'e')
							->where('cc.enabled = true')
							->orderBy('cc.priority', 'DESC');
		
		$query = $queryBuilder->getQuery();
		$query->useResultCache(true, 3600);
		$result = $query->getScalarResult();

        return $result;
    }
}
