<?php


namespace GMI\Bundle\RecommendationBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\BlockBundle\Exception\BlockNotFoundException;
use GMI\Bundle\RecommendationBundle\Services\SessionManager;

class PostByRecommendationBlockService extends BaseBlockService
{
    protected $categoryManager;
    protected $categoryAdmin;
    protected $templates;
    protected $ajaxTemplates;
    protected $ajaxPagerTemplates;
    protected $postManager;
    protected $maxPerPage;
    protected $isEnabledController;
    protected $isCanonicalPageEnabled;
    protected $sessionManager;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ManagerInterface $categoryManager
     * @param AdminInterface $categoryAdmin
     * @param ManagerInterface $postManager
     * @param array $templates
     * @param array $ajaxTemplates
     * @param array $ajaxPagerTemplates
     * @param $maxPerPage
     * @param bool $isEnabledController
     * @param bool $isCanonicalPageEnabled
     * @param SessionManager $sessionManager
     */
    public function __construct($name,
                                EngineInterface $templating,
                                ManagerInterface $categoryManager,
                                AdminInterface $categoryAdmin,
                                ManagerInterface $postManager,
                                array $templates = array(),
                                array $ajaxTemplates = array(),
                                array $ajaxPagerTemplates = array(),
                                $maxPerPage,
                                $isEnabledController = true,
                                $isCanonicalPageEnabled = false,
                                SessionManager $sessionManager)
    {
        $this->name       = $name;
        $this->templating = $templating;
        $this->categoryManager = $categoryManager;
        $this->categoryAdmin = $categoryAdmin;
        $this->postManager = $postManager;
        $this->templates = $templates;
        $this->ajaxTemplates = $ajaxTemplates;
        $this->ajaxPagerTemplates = $ajaxPagerTemplates;
        $this->maxPerPage = $maxPerPage;
        $this->isEnabledController = $isEnabledController;
        $this->isCanonicalPageEnabled = $isCanonicalPageEnabled;
        $this->sessionManager = $sessionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block) {

//        if (!$block->getSetting('category') instanceof CategoryInterface) {
//            $this->load($block);
//        }

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('mode', 'choice', array(
                    'choices' => array(
                        'public' => 'public',
                        'admin'  => 'admin'
                    )
                )),
	            array('noOfPost', 'integer', array('data' => 3)),
                array('template', 'choice', array('choices' => $this->templates)),
                array('ajaxTemplate', 'choice', array('choices' => $this->ajaxTemplates)),
                array('ajaxPagerTemplate', 'choice', array('choices' => $this->ajaxPagerTemplates)),
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
//        $category = $block->getSetting('category', null);
//
//        if (is_int($category)) {
//            $category = $this->categoryManager->findOneBy(array('id' => $category));
//        }
//
//        $block->setSetting('category', $category);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if(!$blockContext->getBlock()->getEnabled()) {
            throw new BlockNotFoundException(sprintf('Block "%s" is disabled.', $blockContext->getBlock()->getId()));
        }

//        $blockSettings['category'] = $blockContext->getBlock()->getSettings('category');
//	    $blockSettings['tag'] = $blockContext->getBlock()->getSettings('tag');
        ####
        # Get category cloud
        ###
	    $categoryData = $this->sessionManager->fetchRawProfile();

	    if($categoryData) {
		    $criteria['category_id'] = array_keys($categoryData);
	    }



	    $tagData = $this->sessionManager->fetchRawProfile('tags');

	    if($tagData) {
		    $criteria['tag_id'] = array_keys($tagData);
	    }

	    $settings = $blockContext->getSettings();

        $parameters = array(
            'block_context'  => $blockContext,
            'settings'       => $settings,
            'block'          => $blockContext->getBlock(),
            'enable_category_canonical_page' => $this->isCanonicalPageEnabled,
            'is_controller_enabled' => $this->isEnabledController,
        );

	    $criteria['mode'] = $settings['mode'];
	    $criteria['enabled'] = true;
	    $noOfPost = isset($settings['noOfPost']) ?: 3;

	    $sort = array('publicationDateStart'=>'DESC', 'tag'=>'DESC');

	    $pager = $this->postManager->getCustomNewsPager($criteria, $sort);
	    $pager->setMaxPerPage($noOfPost);
	    $pager->setCurrentPage(1, false, true);

	    $parameters['pager'] = $pager;

        if ($blockContext->getSetting('mode') !== 'public') {
            return $this->renderPrivateResponse($blockContext->getTemplate(), $parameters, $response);
        }

        return $this->renderResponse($blockContext->getTemplate(), $parameters, $response);
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Post By Recommendation List';
    }


    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'mode'       => 'public',
            'template'   => 'GMIRecommendationBundle:Block:post_by_recommendation_list.html.twig',
            'ajaxTemplate'   => 'GMIRecommendationBundle:Block:post_by_recommendation_ajax.html.twig',
            'ajaxPagerTemplate'   => 'GMIRecommendationBundle:Block:post_by_recommendation_ajax_pager.html.twig',
            'category' => null,
            'tag' => null,
        ));
    }
}
