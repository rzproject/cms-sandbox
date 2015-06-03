<?php

namespace GMI\Bundle\RecommendationBundle\Entity\Manager;

use Doctrine\ORM\EntityManager;
use GMI\Bundle\RecommendationBundle\Model\CloudCategoryInterface;

class CloudCategoryManager implements CloudCategoryManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var string;
     */
    protected $class;
    
    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param string                      $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em    = $em;
        $this->class = $class;
    }
    
    /**
     * {@inheritDoc}
     */
    public function save(CloudCategoryInterface $cloudCategory)
    {
        $this->em->persist($cloudCategory);
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
    public function delete(CloudCategoryInterface $cloudCategory)
    {
        $this->em->remove($cloudCategory);
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
