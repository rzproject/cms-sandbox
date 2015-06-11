<?php

/*
 * This file is part of the RmzamoraSandboxInitDataBundle package.
 *
 * (c) mell m. zamora <me@mellzamora.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rmzamora\SandboxInitDataBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Sonata\NewsBundle\Model\CommentInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadGMIClassificationData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    function getOrder()
    {
        return 4;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        //Create Recommendation Context
        $context = $this->getContextManager()->create();
        $context->setId('recommendation-events');
        $context->setEnabled(true);
        $context->setName('GMI Recommendation Events');
        $this->getContextManager()->save($context);
        $this->addReference('recommendation-events-classification-context', $context);

	    $context = $this->getContextManager()->create();
	    $context->setId('recommendation-profile-type');
	    $context->setEnabled(true);
	    $context->setName('GMI Recommendation Profile Type');
	    $this->getContextManager()->save($context);
	    $this->addReference('recommendation-profile-type-classification-context', $context);

	    $events = array('view', 'like', 'share');

        foreach($events as $cat) {
	        $collection = $this->getCollectionManager()->create();
	        $collection->setEnabled(true);
	        $collection->setName(sprintf('%s', ucfirst($cat)));
	        $collection->setContext($this->getReference('recommendation-events-classification-context'));
	        $this->getCollectionManager()->save($collection);
	        $this->addReference(sprintf('recommendation-events-classification-collection-%s',$cat), $collection);
        }
    }

    /**
     * @return \Sonata\ClassificationBundle\Model\TagManagerInterface
     */
    public function getTagManager()
    {
        return $this->container->get('sonata.classification.manager.tag');
    }

    /**
     * @return \Sonata\ClassificationBundle\Model\CollectionManagerInterface
     */
    public function getCollectionManager()
    {
        return $this->container->get('sonata.classification.manager.collection');
    }


    /**
     * @return \Sonata\CoreBundle\Model\ManagerInterface
     */
    public function getCategoryManager()
    {
        return $this->container->get('sonata.classification.manager.category');
    }

    /**
     * @return \Sonata\CoreBundle\Model\ManagerInterface
     */
    public function getContextManager()
    {
        return $this->container->get('sonata.classification.manager.context');
    }



    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}
