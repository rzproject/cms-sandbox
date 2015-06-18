<?php

namespace Application\Sonata\NewsBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Rz\NewsBundle\Controller\NewsCategoryController as BaseController;

/**
 * Class NewsController
 * @package Rz\NewsBundle\Controller
 */
class NewsCategoryController extends BaseController
{
	protected function categoryViewPreRenderEvent(Request $request, $post, $category = null) {
		###############
		# Profiling
		###############
		if($this->container->has('gmi_recommendation.manager.session')) {
			try{
				$sessionManager = $this->get('gmi_recommendation.manager.session');
				$categories = array();
				$tags = null;
				$this->get('sonata.classification.manager.category')->parseCategoryIds($category, $categories);
				$sessionManager->cloudData('category', $categories, 'view');
				#updated tag cloud
				if($post) {
					$tags = $this->get('sonata.classification.manager.tag')->parseTagIds($post->getTags());
					$sessionManager->cloudData('tag', $tags, 'view');
				}
				#send evet to server PIO
				$sessionManager->pioSendUserAction($post, 'view', array('categories'=>$categories, 'tags'=>$tags));
			} catch(\Exception $e) {
				throw $e;
			}
		}
	}
}
