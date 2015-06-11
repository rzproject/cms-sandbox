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

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cocur\Slugify\Slugify;

class LoadGMIEventData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    function getOrder()
    {
        return 8;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

	    // create sample users
	    $slugify = new Slugify();

	    /** @var EventClient $eventClient */
	    $eventClient = $this->container->get('endroid.prediction_io.app_one.event_client');

	    $blogCategories = array('technology', 'travel', 'entertainment', 'finance', 'business');

	    $count = 0;
	    //create user on event server
	    foreach($blogCategories as $cat) {
		    //create 2 users per blog category
		    foreach (range(1, 2) as $id) {
			    $response = $eventClient->createUser($this->getReference(sprintf('user-%s-%s', $cat, $id))->getId());
			    $count++;
			    dump($response);
		    }
	    }

	    $eventCategories = array('trade fair', 'travel show', 'press conference', 'product launches', 'business conference', 'award', 'weddings', 'birthday', 'anniversary');
	    foreach($eventCategories as $cat) {
		    //create 2 users per blog category
		    foreach (range(1, 2) as $id) {
			    $response = $eventClient->createUser($this->getReference(sprintf('user-%s-%s', $slugify->slugify($cat), $id))->getId());
			    $count++;
			    dump($response);
		    }
	    }

	    $count = 0;

	    //create articles on event server
	    foreach($blogCategories as $cat) {
	        foreach (range(1, 5) as $id) {
		        $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
		        $categories = $news->getPostHasCategory();
		        foreach ($categories as $categ) {
			        $response =  $eventClient->createEntityEvent('$set', 'article', $news->getId(), array('categories' => array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId())));
		        }
		        $count++;
		        dump($response);
		    }
	    }

	    foreach ($eventCategories as $cat) {
	        foreach (range(1, 3) as $id) {
		        $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
		        $categories = $news->getPostHasCategory();
		        foreach ($categories as $categ) {
			        $response =  $eventClient->createEntityEvent('$set', 'article', $news->getId(), array('categories' => array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId())));
		        }
		        $count++;
		        dump($response);
		    }
	    }

	    dump($count);



	    $count = 0;
	    foreach($blogCategories as $cat) {
		    foreach (range(1, 5) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    foreach($blogCategories as $cat) {
		    foreach (range(1, 5) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    foreach($blogCategories as $cat) {
		    foreach (range(1, 5) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    foreach($eventCategories as $cat) {
		    foreach (range(1, 3) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    foreach($eventCategories as $cat) {
		    foreach (range(1, 3) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    foreach($eventCategories as $cat) {
		    foreach (range(1, 3) as $id) {
			    // Record actions
			    $news = $this->getReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id));
			    $categories = $news->getPostHasCategory();
			    foreach ($categories as $categ) {
				    $response = $eventClient->createCustomEvent('view', 'user',
					    $this->getReference(sprintf('user-%s-%s', $categ->getCategory()->getSlug(), rand(1,2)))->getId(),
					    'article',
					    $news->getId(),
					    array('categories'=>array($categ->getCategory()->getId()), 'tags'=>array($news->getTags()[0]->getId()))
				    );
				    $count++;
				    dump($response);
			    }
		    }
	    }

	    dump($count);
    }
}
