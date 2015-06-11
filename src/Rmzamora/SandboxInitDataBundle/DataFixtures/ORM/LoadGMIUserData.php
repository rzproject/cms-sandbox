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

class LoadGMIUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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

	    // create sample users
        $manager = $this->getUserManager();
        $faker = $this->getFaker();

	    $blogCategories = array('technology', 'travel', 'entertainment', 'finance', 'business');

	    foreach($blogCategories as $cat) {
		    //create 2 users per blog category
		    foreach (range(1, 2) as $id) {
			    $user = $manager->createUser();
			    $user->setUsername(sprintf('%s.user%s', strtolower($cat),$id));
			    $user->setEmail($faker->safeEmail);
			    $user->setPlainPassword('12345');
			    $user->setEnabled(true);
			    $user->setSuperAdmin(false);
			    $user->setLocked(false);
			    $manager->updateUser($user);
			    $this->addReference(sprintf('user-%s-%s', $cat, $id), $user);
		    }
	    }

	    $slugify = new Slugify();

	    $eventCategories = array('trade fair', 'travel show', 'press conference', 'product launches', 'business conference', 'award', 'weddings', 'birthday', 'anniversary');
	    foreach($eventCategories as $cat) {
		    //create 2 users per blog category
		    foreach (range(1, 2) as $id) {
			    $user = $manager->createUser();
			    $user->setUsername(sprintf('%s.user%s', strtolower($cat),$id));
			    $user->setEmail($faker->safeEmail);
			    $user->setPlainPassword('12345');
			    $user->setEnabled(true);
			    $user->setSuperAdmin(false);
			    $user->setLocked(false);
			    $manager->updateUser($user);
			    $this->addReference(sprintf('user-%s-%s', $slugify->slugify($cat), $id), $user);
		    }
	    }
    }

    /**
     * @return \FOS\UserBundle\Model\UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}
