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
    const NEWS_LIST_TYPE_DEFAULT = 'archive';
    protected $recommendationParameters;

    /**
     *
     * @param $permalink
     * @param string $_format
     *
     * @return Response
     */
    public function viewAction(Request $request, $permalink, $_format = 'html')
    {
        $post = $this->getPostManager()->findOneByPermalink($permalink, $this->container->get('sonata.news.blog'));

        if (!$post || !$post->isPublic()) {
            throw new NotFoundHttpException('Unable to find the post');
        }

        if ($seoPage = $this->getSeoPage()) {

            $seoPage->setTitle($post->getSetting('seoTitle', null) ? $post->getSetting('seoTitle', null) : $post->getTitle());
            $seoPage->addMeta('name', 'description', $post->getSetting('seoMetaDescription', null)? $post->getSetting('seoMetaDescription', null) : $post->getAbstract());
            if($post->getSetting('seoMetaKeyword', null)) {
                $seoPage->addMeta('name', 'keywords', $post->getSetting('seoMetaKeyword', null));
            }
            $seoPage->addMeta('property', 'og:title', $post->getSetting('ogTitle', null) ? $post->getSetting('ogTitle', null) : $post->getTitle());
            $seoPage->addMeta('property', 'og:type', $post->getSetting('ogType', null) ? $post->getSetting('ogType', null): 'Article');
            $seoPage->addMeta('property', 'og:url', $this->generateUrl('rz_news_view', array(
                'permalink' => $this->getBlog()->getPermalinkGenerator()->generate($post, true),
                '_format' => $_format
            ), true));
            $seoPage->addMeta('property', 'og:description', $post->getSetting('ogDescription', null) ? $post->getSetting('ogDescription', null) : $post->getAbstract());
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
            $postHasCategory = $post->getPostHasCategory();
            $categories = array();
            foreach($postHasCategory as $key=>$value) {
                $this->getCategoryIds($value->getCategory(), $categories);
            }
            $sessionManager = $this->container->get('gmi_recommendation.manager.session');
            $sessionManager->cloudData($categories, $request, 'view');
        }

        return $this->render($template, array(
            'post' => $post,
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
