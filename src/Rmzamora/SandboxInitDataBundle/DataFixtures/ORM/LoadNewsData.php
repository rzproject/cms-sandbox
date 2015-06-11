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
use Cocur\Slugify\Slugify;

class LoadNewsData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    function getOrder()
    {
        return 6;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $postManager = $this->getPostManager();

        $faker = $this->getFaker();

	    $blogCategories = array('technology', 'travel', 'entertainment', 'finance', 'business');
	    $slugify = new Slugify();

        $i = 0;
        foreach (range(1, 5) as $id) {
	        foreach($blogCategories as $cat) {
	            $post = $postManager->create();
	            $post->setAuthor($this->getReference('user-admin'));

	            $post->setCollection($this->getReference('news-classification-collection-blog'));
	            $post->setAbstract($faker->sentence(10));
	            $post->setEnabled(true);
		        $post->setTitle(sprintf('SAMPLE %s %s',$cat ,$faker->sentence(3)));
	            $post->setPublicationDateStart($faker->dateTimeBetween('-30 days', '-1 days'));
	            $post->setImage($this->getReference('sonata-media-news-'.$faker->numberBetween(0,2)));

                $this->addCategory($post, $this->getReference(sprintf('news-classification-category-news-blog-%s', $cat)));

	            $raw = null;

	            $raw .= sprintf("%s\n\n%s\n\n %s\n\n%s",
	                $faker->sentence(rand(3, 6)),
	                $faker->text(100),
	                $faker->sentence(rand(3, 6)),
	                $faker->text(100)
	            );

	            $post->setRawContent($raw);
	            $post->setContentFormatter('richhtml');

	            $post->setContent($this->getPoolFormatter()->transform($post->getContentFormatter(), $post->getRawContent()));
	            $post->setCommentsDefaultStatus(CommentInterface::STATUS_VALID);

	            $settings = array('template'=>'RzNewsBundle:Post:view.html.twig');
	            $post->setSettings($settings);

	            foreach(array('blog') as $key=>$tag) {
	                $post->addTags($this->getReference(sprintf('news-classification-tag-%s', $tag)));
	            }

//	            foreach(range(1, $faker->randomDigit + 2) as $commentId) {
//	                $comment = $this->getCommentManager()->create();
//	                $comment->setEmail($faker->email);
//	                $comment->setName($faker->name);
//	                $comment->setStatus(CommentInterface::STATUS_VALID);
//	                $comment->setMessage($faker->sentence(25));
//	                $comment->setUrl($faker->url);
//
//	                $post->addComments($comment);
//	            }

	            $this->addReference(sprintf('sonata-news-%s-%s', $cat, $id), $post);

	            $postManager->save($post);
	        }
        }

	    $eventCategories = array('trade fair', 'travel show', 'press conference', 'product launches', 'business conference', 'award', 'weddings', 'birthday', 'anniversary');

        foreach (range(1, 3) as $id) {
	        foreach($eventCategories as $cat) {
		        $post = $postManager->create();
		        $post->setAuthor($this->getReference('user-admin'));

		        $post->setCollection($this->getReference('news-classification-collection-event'));
		        $post->setAbstract($faker->sentence(10));
		        $post->setEnabled(true);
		        $post->setTitle(sprintf('SAMPLE %s %s',$cat ,$faker->sentence(3)));
		        $post->setPublicationDateStart($faker->dateTimeBetween('-30 days', '-1 days'));
		        $post->setImage($this->getReference('sonata-media-news-' . $faker->numberBetween(0, 2)));
		        $this->addCategory($post, $this->getReference(sprintf('news-classification-category-news-event-%s', $slugify->slugify($cat))));
		        $raw = null;
		        $raw .= sprintf("%s\n\n%s\n\n %s\n\n%s",
			        $faker->sentence(rand(3, 6)),
			        $faker->text(100),
			        $faker->sentence(rand(3, 6)),
			        $faker->text(100)
		        );

		        $post->setRawContent($raw);
		        $post->setContentFormatter('richhtml');

		        $post->setContent($this->getPoolFormatter()->transform($post->getContentFormatter(), $post->getRawContent()));
		        $post->setCommentsDefaultStatus(CommentInterface::STATUS_VALID);

		        //set event settings
		        $end_day = null;
		        $month = $faker->numberBetween(1, 12);

		        if ($month == 2) {
			        $day = $faker->numberBetween(1, 20);
			        $end_day = $faker->numberBetween(21, 28);
		        } else {
			        $day = $faker->numberBetween(1, 20);
			        $end_day = $faker->numberBetween(21, 30);
		        }


		        $year = $faker->numberBetween(2014, 2016);
		        $settings = array('template'   => 'RzNewsBundle:Post:view_event.html.twig',
		                          'start_date' => array("year" => $year, "month" => $month, "day" => $day),
		                          'end_date'   => array("year" => $year, "month" => $month, "day" => $end_day),
		                          "location"   => array("lat" => "16.0432998", "lng" => "120.33331240000007"),
		                          "address"    => $faker->address
		        );

		        $post->setSettings($settings);

		        foreach (array('event') as $key => $tag) {
			        $post->addTags($this->getReference(sprintf('news-classification-tag-%s', $tag)));
		        }

//		        foreach (range(1, $faker->randomDigit + 2) as $commentId) {
//			        $comment = $this->getCommentManager()->create();
//			        $comment->setEmail($faker->email);
//			        $comment->setName($faker->name);
//			        $comment->setStatus(CommentInterface::STATUS_VALID);
//			        $comment->setMessage($faker->sentence(25));
//			        $comment->setUrl($faker->url);
//
//			        $post->addComments($comment);
//		        }

		        $this->addReference(sprintf('sonata-news-%s-%s', $slugify->slugify($cat), $id), $post);

		        $postManager->save($post);
	        }
        }
    }

    public function addCategory($post, $category) {
        $postHasCategory = new \Application\Sonata\NewsBundle\Entity\PostHasCategory();
        $postHasCategory->setCategory($category);
        $postHasCategory->setPost($post);
        $postHasCategory->setPosition(count($post->getPostHasCategory()) + 1);
        $postHasCategory->setEnabled(true);

        $post->addPostHasCategory($postHasCategory);
    }

    public function getPoolFormatter()
    {
        return $this->container->get('sonata.formatter.pool');
    }


    /**
     * @return \Sonata\NewsBundle\Model\PostManagerInterface
     */
    public function getPostManager()
    {
        return $this->container->get('sonata.news.manager.post');
    }

    /**
     * @return \Sonata\NewsBundle\Model\CommentManagerInterface
     */
    public function getCommentManager()
    {
        return $this->container->get('sonata.news.manager.comment');
    }



    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}
