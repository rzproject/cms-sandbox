<?php
namespace Application\Sonata\PageBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /**
     * Creates the header menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $categoryManager = $this->container->get('sonata.classification.manager.category');
        $isEnabledController = $this->container->getParameter('rz_classification.enable_controllers');
        $newsParentCategory = $categoryManager->findOneBy(array('enabled' => true, 'slug'=>'news'));

        if($newsParentCategory) {
            $categories = $categoryManager->getSubCategories($newsParentCategory->getId());
        }

        $menuOptions = $options;
        $menu = $factory->createItem('main', $menuOptions);

        $menu->addChild('Home', array(
            'route' => 'page_slug',
            'routeParameters' => array(
                'path' => '/'
            )
        ));

        foreach($categories as $cat){
            if ($isEnabledController) {
                $menu->addChild($cat->getName(), array(
                    'route'           => 'rz_news_category',
                    'routeParameters' => array(
                        'permalink' => $categoryManager->getPermalinkGenerator()->generate($cat)
                    ),
                ));
            } elseif($page = $cat->getPage()) {
                $menu->addChild($cat->getName(), array(
                    'route'           => 'page_slug',
                    'routeParameters' => array(
                        'path' => $page->getUrl()
                    ),
                ));
            } else {
                $menu->addChild($cat->getName(), array('uri' => '#'));
            }
        }

        if(array_key_exists('authenticated', $options) && $options['authenticated']) {
            $menuBuilder = $this->container->get('sonata.user.profile.menu_builder');
            $childOptions = array(
                'uri' => "#",
                'extras' => array(
                    'safe_label' => true,
                ));

            $menu->addChild($menuBuilder->createProfileMenu($childOptions));
            $user = $this->container->get('security.context')->getToken()->getUser();
            $menu['profile']->setAttribute('class', 'has-submenu');
            if($user) {
                $menu['profile']->setLabel('<i class="icon-user"></i> Hi '.$user->getUsername().'!');
            } else {
                $menu['profile']->setLabel($this->container->get('translator')->trans('rz_my_profile', array(), 'RzUserBundle'));
            }

        } else {
            $menu->addChild('Register', array('route' => 'fos_user_registration_register'));
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        }

        return $menu;
    }

    public function userMainMenu(FactoryInterface $factory, array $options) {
        return $this->mainMenu($factory, array_merge($options, array('authenticated' => true)));
    }

    public function anonymousMainMenu(FactoryInterface $factory, array $options) {
        return $this->mainMenu($factory, array_merge($options, array('authenticated' => false)));
    }

    public function footerMenu(FactoryInterface $factory, array $options) {
        return $this->mainMenu($factory, array_merge($options, array('is_footer' => true)));
    }
}
