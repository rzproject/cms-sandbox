<?php

namespace GMI\Bundle\RecommendationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gmi_recommendation');

        $rootNode
			->addDefaultsIfNotSet()
			->canBeUnset()
            ->children()
                ->scalarNode('profiler_config_class')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Services\\Config')->end()
                ->scalarNode('profiler_event_listener_authentication_class')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Events\\Listeners\\AuthenticationListener')->end()
                ->scalarNode('profiler_subscriber_kernel_event_class')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Events\\Subscribers\\KernelEventSubscriber')->end()
                ->scalarNode('profiler_subscriber_user_registration_event_class')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Events\\Subscribers\\UserRegistrationEventSubscriber')->end()
                ->scalarNode('session_manager_class')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Services\\SessionManager')->end()
				->arrayNode('profiler')
					->addDefaultsIfNotSet()
					->canBeUnset()
					->children()
						->booleanNode('enabled')->defaultTrue()->end()
					    ->scalarNode('page_view_threshold')->defaultValue(3)->end()
						->arrayNode('page_routes')
							->prototype('scalar')
							->end()
						->end()
					->end()
				->end()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cloud')->defaultValue('Application\\GMI\\Bundle\RecommendationBundle\\Entity\\Cloud')->end()
                        ->scalarNode('cloud_category')->defaultValue('Application\\GMI\\Bundle\RecommendationBundle\\Entity\\CloudCategory')->end()
                        ->scalarNode('cloud_profile')->defaultValue('Application\\GMI\\Bundle\RecommendationBundle\\Entity\\CloudProfile')->end()
                        ->scalarNode('cloud_logs')->defaultValue('Application\\GMI\\Bundle\RecommendationBundle\\Entity\\CloudLogs')->end()
                        ->scalarNode('category')->defaultValue('Application\\Sonata\\ClassificationBundle\\Entity\\Category')->end()
                        ->scalarNode('collection')->defaultValue('Application\\Sonata\\ClassificationBundle\\Entity\\Collection')->end()
                        ->scalarNode('user')->defaultValue('Application\\Sonata\\UserBundle\\Entity\\User')->end()
                    ->end()
                ->end()
                ->arrayNode('class_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cloud')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Entity\\Manager\\CloudManager')->end()
                        ->scalarNode('cloud_category')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Entity\\Manager\\CloudCategoryManager')->end()
                        ->scalarNode('cloud_profile')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Entity\\Manager\\CloudProfileManager')->end()
                        ->scalarNode('cloud_logs')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Entity\\Manager\\CloudLogsManager')->end()
                        ->scalarNode('session')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Services\\SessionManager')->end()
                        ->scalarNode('profiler')->defaultValue('GMI\\Bundle\\RecommendationBundle\\Services\\Config')->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('cloud')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('GMI\\Bundle\\RecommendationBundle\\Admin\\CloudAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('GMIRecommendationBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('cloud_category')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('GMI\\Bundle\\RecommendationBundle\\Admin\\CloudCategoryAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('GMIRecommendationBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('cloud_profile')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('GMI\\Bundle\\RecommendationBundle\\Admin\\CloudProfileAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('GMIRecommendationBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('cloud_logs')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('GMI\\Bundle\\RecommendationBundle\\Admin\\CloudLogsAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('GMIRecommendationBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end();
        $this->addBlockSettings($rootNode);
        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBlockSettings(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('blocks')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('post_by_recommendation_list')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('GMI\\Bundle\\RecommendationBundle\\Block\\PostByRecommendationBlockService')->end()
                                ->arrayNode('ajax_templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('GMIRecommendationBundle:Block:post_by_recommendation_ajax.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('ajax_pager_templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('GMIRecommendationBundle:Block:post_by_recommendation_ajax_pager.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('GMIRecommendationBundle:Block:post_by_recommendation_list.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
