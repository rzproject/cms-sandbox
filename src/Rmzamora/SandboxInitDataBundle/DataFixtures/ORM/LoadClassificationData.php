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

class LoadClassificationData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    function getOrder()
    {
        return 2;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        //Create Default Context
        $context = $this->getContextManager()->create();
        $context->setEnabled(true);
        $context->setName('Default');
        $this->getContextManager()->save($context);

        $this->addReference('default-classification-context', $context);


        $category = $this->getCategoryManager()->create();
        $category->setEnabled(true);
        $category->setName('Default');
        $category->setContext( $this->getReference('default-classification-context'));
        $this->getCategoryManager()->save($category);

        $this->addReference('default-classification-category-default', $category);

        $tags = array(
            'symfony' => null,
            'form' => null,
            'general' => null,
            'web2' => null,
        );

        foreach($tags as $tagName => $null) {
            $tag = $this->getTagManager()->create();
            $tag->setEnabled(true);
            $tag->setName($tagName);
            $tag->setContext($this->getReference('default-classification-context'));
            $tags[$tagName] = $tag;
            $this->getTagManager()->save($tag);
            $this->addReference(sprintf('default-classification-tag-%s', $tagName), $tag);
        }

        $collection = $this->getCollectionManager()->create();
        $collection->setEnabled(true);
        $collection->setName('General');
        $collection->setContext($this->getReference('default-classification-context'));
        $this->getCollectionManager()->save($collection);
        $this->addReference('default-classification-collection-general', $collection);

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
