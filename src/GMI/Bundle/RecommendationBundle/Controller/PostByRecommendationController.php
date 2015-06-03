<?php
namespace GMI\Bundle\RecommendationBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Rz\NewsBundle\Controller\AbstractNewsController;

/**
 * Class NewsController
 * @package Rz\NewsBundle\Controller
 */
class PostByRecommendationController extends AbstractNewsController
{

    protected function getCategoryXhrResponse($category, $block, $parameters) {
        $settings = $block->getSettings();
        $ajaxTemplate = isset($settings['ajaxTemplate']) ? $settings['ajaxTemplate'] : 'RzNewsBundle:Block:post_by_category_ajax.html.twig';
        $templatePagerAjax = isset($settings['ajaxPagerTemplate']) ? $settings['ajaxPagerTemplate'] : 'RzNewsBundle:Block:post_by_category_ajax_pager.html.twig';
        $html = $this->container->get('templating')->render($ajaxTemplate, $parameters);
        $html_pager = $this->container->get('templating')->render($templatePagerAjax, $parameters);
        return new JsonResponse(array('html' => $html, 'html_pager'=>$html_pager));
    }

    protected function getCategoryDataForView($category, $block, $page = null) {

        $parameters = array('category' => $category);

        if($page) {
            $parameters['page'] = $page;
        }

        $pager = $this->fetchNews($parameters);

        if ($pager->getNbResults() <= 0) {
            throw new NotFoundHttpException('Invalid URL');
        }

        return $this->buildParameters($pager, $this->get('request_stack')->getCurrentRequest(), array('category' => $category, 'block'=>$block));
    }

    protected function verifyCategory($categoryId) {

        $collection = $this->get('sonata.classification.manager.category')->findOneBy(array(
            'id' => $categoryId,
            'enabled' => true
        ));

        if (!$collection) {
            return false;
        }

        if (!$collection->getEnabled()) {
            return false;
        }

        return $collection;
    }

    protected function verifyBlock($blockId) {

        $block = $this->get('sonata.page.manager.block')->findOneBy(array(
            'id' => $blockId,
        ));

        if (!$block) {
            return false;
        }

        if (!$block->getEnabled()) {
            return false;
        }

        return $block;
    }


    /**
     * @param Request $request
     * @param $categiryId
     * @param $blockId
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @internal param $collectionId
     * @internal param $collection
     */
    public function postsByRecommendationAjaxPagerAction(Request $request, $categoryId, $blockId, $page = 1) {

        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException('Unable to find page');
        }

        if(!$category = $this->verifyCategory($categoryId)) {
            throw new NotFoundHttpException('Unable to find the category');
        }

        if(!$block = $this->verifyBlock($blockId)) {
            throw new NotFoundHttpException('Unable to find the block');
        }

        //redirect to normal controller if not ajax
        if (!$this->get('request_stack')->getCurrentRequest()->isXmlHttpRequest()) {
            //TODO implement central pager for SEO purposes
            //return $this->redirect($this->generateUrl('rz_news_collection_pager', array('collection'=>$collection->getSlug(), 'page'=>$page)), 301);
        }

        try {
            $parameters = $this->getCategoryDataForView($category, $block, $page);
        } catch(\Exception $e) {
            throw $e;
        }

        return $this->getCategoryXhrResponse($category, $block, $parameters);
    }

}