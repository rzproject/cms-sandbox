<?php

namespace GMI\Bundle\RecommendationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GMIRecommendationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('admin_orm.xml');
        $loader->load('orm.xml');
        $loader->load('config.xml');
        $loader->load('services.xml');
        $loader->load('listeners.xml');
        $loader->load('subscribers.xml');
        $loader->load('block.xml');

        $this->configureAdminClass($config, $container);
        $this->configureClass($config, $container);
        $this->configureTranslationDomain($config, $container);
        $this->configureController($config, $container);
        $this->configureRzTemplates($config, $container);
        $this->registerDoctrineMapping($config, $container);
        $this->configureProfiler($config, $container);
        $this->configureBlocks($config['blocks'], $container);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureClass($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.admin.cloud.entity', $config['class']['cloud']);
        $container->setParameter('gmi_recommendation.manager.cloud.entity', $config['class']['cloud']);
        $container->setParameter('gmi_recommendation.model.cloud.class', $config['class']['cloud']);
        $container->setParameter('gmi_recommendation.manager.cloud.class', $config['class_manager']['cloud']);

        $container->setParameter('gmi_recommendation.admin.cloud_category.entity', $config['class']['cloud_category']);
        $container->setParameter('gmi_recommendation.manager.cloud_category.entity', $config['class']['cloud_category']);
        $container->setParameter('gmi_recommendation.model.cloud_category.class', $config['class']['cloud_category']);
        $container->setParameter('gmi_recommendation.manager.cloud_category.class', $config['class_manager']['cloud_category']);

	    $container->setParameter('gmi_recommendation.admin.cloud_tag.entity', $config['class']['cloud_tag']);
	    $container->setParameter('gmi_recommendation.manager.cloud_tag.entity', $config['class']['cloud_tag']);
	    $container->setParameter('gmi_recommendation.model.cloud_tag.class', $config['class']['cloud_tag']);
	    $container->setParameter('gmi_recommendation.manager.cloud_tag.class', $config['class_manager']['cloud_tag']);

        $container->setParameter('gmi_recommendation.admin.cloud_profile.entity', $config['class']['cloud_profile']);
        $container->setParameter('gmi_recommendation.manager.cloud_profile.entity', $config['class']['cloud_profile']);
        $container->setParameter('gmi_recommendation.model.cloud_profile.class', $config['class']['cloud_profile']);
        $container->setParameter('gmi_recommendation.manager.cloud_profile.class', $config['class_manager']['cloud_profile']);

        $container->setParameter('gmi_recommendation.admin.cloud_logs.entity', $config['class']['cloud_logs']);
        $container->setParameter('gmi_recommendation.manager.cloud_logs.entity', $config['class']['cloud_logs']);
        $container->setParameter('gmi_recommendation.model.cloud_logs.class', $config['class']['cloud_logs']);
        $container->setParameter('gmi_recommendation.manager.cloud_logs.class', $config['class_manager']['cloud_logs']);

        $container->setParameter('gmi_recommendation.manager.session.class', $config['class_manager']['session']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureAdminClass($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.admin.cloud.class', $config['admin']['cloud']['class']);
        $container->setParameter('gmi_recommendation.admin.cloud_category.class', $config['admin']['cloud_category']['class']);
	    $container->setParameter('gmi_recommendation.admin.cloud_tag.class', $config['admin']['cloud_tag']['class']);
        $container->setParameter('gmi_recommendation.admin.cloud_profile.class', $config['admin']['cloud_profile']['class']);
        $container->setParameter('gmi_recommendation.admin.cloud_logs.class', $config['admin']['cloud_logs']['class']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureTranslationDomain($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.admin.cloud.translation_domain', $config['admin']['cloud']['translation']);
        $container->setParameter('gmi_recommendation.admin.cloud_category.translation_domain', $config['admin']['cloud_category']['translation']);
	    $container->setParameter('gmi_recommendation.admin.cloud_tag.translation_domain', $config['admin']['cloud_tag']['translation']);
        $container->setParameter('gmi_recommendation.admin.cloud_profile.translation_domain', $config['admin']['cloud_profile']['translation']);
        $container->setParameter('gmi_recommendation.admin.cloud_logs.translation_domain', $config['admin']['cloud_logs']['translation']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureController($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.admin.cloud.controller', $config['admin']['cloud']['controller']);
        $container->setParameter('gmi_recommendation.admin.cloud_category.controller', $config['admin']['cloud_category']['controller']);
	    $container->setParameter('gmi_recommendation.admin.cloud_tag.controller', $config['admin']['cloud_tag']['controller']);
        $container->setParameter('gmi_recommendation.admin.cloud_profile.controller', $config['admin']['cloud_profile']['controller']);
        $container->setParameter('gmi_recommendation.admin.cloud_logs.controller', $config['admin']['cloud_logs']['controller']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureRzTemplates($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.configuration.cloud.templates', $config['admin']['cloud']['templates']);
        $container->setParameter('gmi_recommendation.configuration.cloud_category.templates', $config['admin']['cloud_category']['templates']);
	    $container->setParameter('gmi_recommendation.configuration.cloud_tag.templates', $config['admin']['cloud_tag']['templates']);
        $container->setParameter('gmi_recommendation.configuration.cloud_profile.templates', $config['admin']['cloud_profile']['templates']);
        $container->setParameter('gmi_recommendation.configuration.cloud_logs.templates', $config['admin']['cloud_logs']['templates']);
    }


    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureProfiler($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.config.profiler.class', $config['profiler_config_class']);
        $container->setParameter('gmi_recommendation.event_listener.authentication.class', $config['profiler_event_listener_authentication_class']);
        $container->setParameter('gmi_recommendation.event_subscriber.kernel.class', $config['profiler_subscriber_kernel_event_class']);
        $container->setParameter('gmi_recommendation.event_subscriber.user_registration.class', $config['profiler_subscriber_user_registration_event_class']);
        $container->setParameter('gmi_recommendation.manager.session.class', $config['session_manager_class']);
        $container->setParameter('gmi_recommendation.profiler', $config['profiler']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureBlocks($config, ContainerBuilder $container)
    {
        $container->setParameter('gmi_recommendation.block.post_by_recommendation.class', $config['post_by_recommendation_list']['class']);

        # template
        $temp = $config['post_by_recommendation_list']['templates'];
        $templates = array();
        foreach ($temp as $template) {
            $templates[$template['path']] = $template['name'];
        }
        $container->setParameter('gmi_recommendation.block.post_by_recommendation.templates', $templates);

        # ajax template
        $ajaxTemp = $config['post_by_recommendation_list']['ajax_templates'];
        $ajaxTemplates = array();
        foreach ($ajaxTemp as $ajaxTemplate) {
            $ajaxTemplates[$ajaxTemplate['path']] = $ajaxTemplate['name'];
        }
        $container->setParameter('gmi_recommendation.block.post_by_recommendation.ajax_templates', $ajaxTemplates);

        # ajax pager template
        $ajaxPagerTemp = $config['post_by_recommendation_list']['ajax_pager_templates'];
        $ajaxPagerTemplates = array();
        foreach ($ajaxPagerTemp as $ajaxPagerTemplate) {
            $ajaxPagerTemplates[$ajaxPagerTemplate['path']] = $ajaxPagerTemplate['name'];
        }
        $container->setParameter('gmi_recommendation.block.post_by_recommendation.ajax_pager_templates', $ajaxPagerTemplates);

    }

    /**
     * @param array $config
     */
    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        if(class_exists($config['class']['user'])) {
            $collector->addAssociation($config['class']['cloud'], 'mapOneToOne', array(
                'fieldName'    => 'user',
                'targetEntity' => $config['class']['user'],
                'cascade' =>
                    array(
                        1 => 'detach'
                    ),
                'mappedBy'      => NULL,
                'inversedBy'    => NULL,
                'orphanRemoval' => true
            ));
        }

        if(class_exists($config['class']['category'])) {
            $collector->addAssociation($config['class']['cloud_category'], 'mapManyToOne', array(
                'fieldName' => 'category',
                'targetEntity' => $config['class']['category'],
                'cascade' =>
                    array(
                        1 => 'detach',
                    ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' =>
                    array(
                        array(
                            'name' => 'category_id',
                            'referencedColumnName' => 'id',
                        ),
                    ),
                'orphanRemoval' => false,
            ));

            $collector->addAssociation($config['class']['cloud_profile'], 'mapManyToOne', array(
                'fieldName' => 'category',
                'targetEntity' => $config['class']['category'],
                'cascade' =>
                    array(
                        1 => 'detach',
                    ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' =>
                    array(
                        array(
                            'name' => 'category_id',
                            'referencedColumnName' => 'id',
                        ),
                    ),
                'orphanRemoval' => false,
            ));
        }

	    if(class_exists($config['class']['tag'])) {
		    $collector->addAssociation($config['class']['cloud_tag'], 'mapManyToOne', array(
			    'fieldName' => 'tag',
			    'targetEntity' => $config['class']['tag'],
			    'cascade' =>
				    array(
					    1 => 'detach',
				    ),
			    'mappedBy' => NULL,
			    'inversedBy' => NULL,
			    'joinColumns' =>
				    array(
					    array(
						    'name' => 'tag_id',
						    'referencedColumnName' => 'id',
					    ),
				    ),
			    'orphanRemoval' => false,
		    ));
	    }

        if(class_exists($config['class']['collection'])) {
            $collector->addAssociation($config['class']['cloud_category'], 'mapManyToOne', array(
                'fieldName' => 'event',
                'targetEntity' => $config['class']['collection'],
                'cascade' =>
                    array(
                        1 => 'detach',
                    ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' =>
                    array(
                        array(
                            'name' => 'event_id',
                            'referencedColumnName' => 'id',
                        ),
                    ),
                'orphanRemoval' => false,
            ));

	        $collector->addAssociation($config['class']['cloud_tag'], 'mapManyToOne', array(
		        'fieldName' => 'event',
		        'targetEntity' => $config['class']['collection'],
		        'cascade' =>
			        array(
				        1 => 'detach',
			        ),
		        'mappedBy' => NULL,
		        'inversedBy' => NULL,
		        'joinColumns' =>
			        array(
				        array(
					        'name' => 'event_id',
					        'referencedColumnName' => 'id',
				        ),
			        ),
		        'orphanRemoval' => false,
	        ));

            $collector->addAssociation($config['class']['cloud_profile'], 'mapManyToOne', array(
                'fieldName' => 'profileType',
                'targetEntity' => $config['class']['collection'],
                'cascade' =>
                    array(
                        1 => 'detach',
                    ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' =>
                    array(
                        array(
                            'name' => 'profile_type_id',
                            'referencedColumnName' => 'id',
                        ),
                    ),
                'orphanRemoval' => false,
            ));
        }

        $collector->addAssociation($config['class']['cloud_logs'], 'mapManyToOne', array(
            'fieldName' => 'cloud',
            'targetEntity' => $config['class']['cloud'],
            'cascade' =>
                array(
                    1 => 'detach',
                ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
                array(
                    array(
                        'name' => 'cloud_id',
                        'referencedColumnName' => 'id',
                    ),
                ),
            'orphanRemoval' => false,
        ));
    }
}
