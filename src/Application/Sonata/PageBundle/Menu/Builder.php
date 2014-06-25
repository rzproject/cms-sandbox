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
        $isFooter = array_key_exists('is_footer', $options) ? $options['is_footer'] : false;

        $shopCategories = $this->container->get('sonata.classification.manager.category')->findBy(array('enabled' => true, 'parent' => null));

        $menuOptions = array_merge($options, array(
            'childrenAttributes' => array('class' => 'nav nav-pills'),
        ));

        $menu = $factory->createItem('main', $menuOptions);

        $menu->addChild('News', array('route' => 'sonata_news_home'));

        $dropdownExtrasOptions = $isFooter ? array(
            'uri' => "#",
            'attributes' => array('class' => 'span2'),
            'childrenAttributes' => array('class' => 'nav'),
        ) : array(
            'uri' => "#",
            'attributes' => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'data-target' => '#'),
            'label' => 'Solutions <b class="caret caret-menu"></b>',
            'extras' => array(
                'safe_label' => true,
            )
        );
        $extras = $factory->createItem('Discover', $dropdownExtrasOptions);

        $extras->addChild('Api', array('route' => 'page_slug', 'routeParameters' => array('path' => '/api-landing')));
        $extras->addChild('Gallery', array('route' => 'sonata_media_gallery_index'));

        $menu->addChild($extras);

        $menu->addChild('Admin', array(
            'route' => 'page_slug',
            'routeParameters' => array(
                'path' => '/user'
            )
        ));

//        if ($isFooter) {
//            $menu->addChild('Legal notes', array(
//                'route' => 'page_slug',
//                'routeParameters' => array(
//                    'path' => '/legal-notes',
//                )
//            ));
//        }

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        return $this->mainMenu($factory, array_merge($options, array('is_footer' => true)));
    }
}
