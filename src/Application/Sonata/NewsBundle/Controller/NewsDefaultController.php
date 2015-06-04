<?php

namespace Application\Sonata\NewsBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Rz\NewsBundle\Controller\NewsDefaultController as BaseController;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class NewsController
 * @package Rz\NewsBundle\Controller
 */
class NewsDefaultController extends BaseController
{
	protected function postViewPreRenderEvent(Request $request, $post) {
		###############
		# Profiling
		###############
		if($this->container->has('gmi_recommendation.manager.session')) {
			try {
				$sessionManager = $this->container->get('gmi_recommendation.manager.session');
				#updated category cloud
				$postHasCategory = $post->getPostHasCategory();
				$categories = array();
				foreach($postHasCategory as $key=>$value) {
					$this->get('sonata.classification.manager.category')->parseCategoryIds($value->getCategory(), $categories);
				}
				$sessionManager->cloudData('category', $categories, 'view');
				#updated tag cloud
				if($post) {
					$tags = $this->get('sonata.classification.manager.tag')->parseTagIds($post->getTags());
					$sessionManager->cloudData('tag', $tags, 'view');
				}
			} catch(\Exception $e) {
				return;
			}
		}
	}
}