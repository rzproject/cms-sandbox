<?php

namespace Application\Sonata\NewsBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Rz\NewsBundle\Controller\NewsCategoryController as BaseController;

/**
 * Class NewsController
 * @package Rz\NewsBundle\Controller
 */
class NewsCategoryController extends BaseController
{

    const NEWS_LIST_TYPE_CATEGORY = 'category';


    protected function renderCategoryView($post, $category) {

        if (!$post || !$post->isPublic()) {
            throw new NotFoundHttpException('Unable to find the post');
        }

        if ($seoPage = $this->getSeoPage()) {
            $request = $this->get('request_stack')->getCurrentRequest();

            $seoPage->setTitle($post->getSetting('seoTitle', null) ? $post->getSetting('seoTitle', null) : $post->getTitle());
            $seoPage->addMeta('name', 'description', $post->getSetting('seoMetaDescription', null)? $post->getSetting('seoMetaDescription', null) : $post->getAbstract());
            if($post->getSetting('seoMetaKeyword', null)) {
                $seoPage->addMeta('name', 'keywords', $post->getSetting('seoMetaKeyword', null));
            }
            $seoPage->addMeta('property', 'og:title', $post->getSetting('ogTitle', null) ? $post->getSetting('ogTitle', null) : $post->getTitle());
            $seoPage->addMeta('property', 'og:type', $post->getSetting('ogType', null) ? $post->getSetting('ogType', null): 'Article');
            $seoPage->addMeta('property', 'og:url',  $this->generateUrl('rz_news_category_view', array(
                'category' => $this->getCategoryManager()->getPermalinkGenerator()->createSubCategorySlug($category),
                'permalink'  => $this->getBlog()->getPermalinkGenerator()->generate($post, true),
                '_format' => $request->getRequestFormat()
            ), true));
            $seoPage->addMeta('property', 'og:description', $post->getSetting('ogDescription', null) ? $post->getSetting('ogDescription', null) : $post->getAbstract());
            $seoPage->setLinkCanonical($this->generateUrl('rz_news_view', array(
                    'permalink'  => $this->getBlog()->getPermalinkGenerator()->generate($post, true),
                    '_format' => $request->getRequestFormat()
                ), true))
            ;
        }

        //set default template
        $template = $this->getFallbackTemplate();

        $viewTemplate = $post->getSetting('template');
        if($viewTemplate) {
            if ($this->getTemplating()->exists($template)) {
                $template = $viewTemplate;
            } else {
                //get generic template
                $pool = $this->getNewsPool();
                $defaultTemplateName = $pool->getDefaultTemplateNameByCollection($pool->getDefaultDefaultCollection());
                $defaultViewTemplate = $pool->getTemplateByCollection($defaultTemplateName);

                if($defaultViewTemplate) {
                    $template = $viewTemplate['path'];
                }
            }
        }

        ###############
        # Profiling
        ###############
        if($this->container->has('gmi_recommendation.manager.session')) {
            $sessionManager = $this->container->get('gmi_recommendation.manager.session');
            $categories = array();
            $this->getCategoryIds($category, $categories);
            $sessionManager->cloudData($categories, $request, 'view');
        }

        return $this->render($template, array(
            'post' => $post,
            'category' => $category,
            'form' => false,
            'is_controller_enabled' => $this->container->getParameter('rz_classification.enable_controllers'),
            'blog' => $this->get('sonata.news.blog')
        ));
    }

    protected function getCategoryIds($category, &$categories) {
        $categories[] = $category->getId();
        if($category->getParent() && $category->getParent()->getSlug() !== 'news') {
            $this->getCategoryIds($category->getParent(), $categories);
        }

    }
}
