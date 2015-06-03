<?php

namespace  GMI\Bundle\RecommendationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) {

        ####################
        # Override  admin templates
        ####################
        $definition = $container->getDefinition('gmi_recommendation.admin.cloud_category');
        $this->fixTemplates($container, $definition, 'gmi_recommendation.configuration.cloud_category.templates');
        $definition = $container->getDefinition('gmi_recommendation.admin.cloud_profile');
        $this->fixTemplates($container, $definition, 'gmi_recommendation.configuration.cloud_profile.templates');

        ####################
        # Override  profiler
        ####################
        $definition = $container->getDefinition('gmi_recommendation.config.profiler');
        $definition->setClass($container->getParameter('gmi_recommendation.config.profiler.class'));

        ####################
        # Override  event_listener.authentication
        ####################
        $definition = $container->getDefinition('gmi_recommendation.event_listener.authentication');
        $definition->setClass($container->getParameter('gmi_recommendation.event_listener.authentication.class'));

        ####################
        # Override  event_subscriber.kernel
        ####################
        $definition = $container->getDefinition('gmi_recommendation.event_subscriber.kernel');
        $definition->setClass($container->getParameter('gmi_recommendation.event_subscriber.kernel.class'));

        ####################
        # Override  event_subscriber.user_registration
        ####################
        $definition = $container->getDefinition('gmi_recommendation.event_subscriber.user_registration');
        $definition->setClass($container->getParameter('gmi_recommendation.event_subscriber.user_registration.class'));

        ####################
        # Override  manager.session
        ####################
        $definition = $container->getDefinition('gmi_recommendation.manager.session');
        $definition->setClass($container->getParameter('gmi_recommendation.manager.session.class'));
    }

    /**
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param  \Symfony\Component\DependencyInjection\Definition $definition
     * @param $templates
     *
     * @return void
     */
    public function fixTemplates(ContainerBuilder $container, Definition $definition, $templates)
    {
        $defaultTemplates = $container->getParameter('sonata.admin.configuration.templates');
        $definedTemplates = array_merge($defaultTemplates, $container->getParameter('rz_admin.configuration.templates'));
        $definedTemplates = array_merge($definedTemplates, $container->getParameter($templates));

        $methods = array();
        $pos = 0;

        //override all current sonata admin with the Rz Templates
        foreach ($definition->getMethodCalls() as $method) {
            if ($method[0] == 'setTemplates') {
                $definedTemplates = array_merge($definedTemplates, $method[1][0]);
                continue;
            }

            if ($method[0] == 'setTemplate') {
                $definedTemplates[$method[1][0]] = $method[1][1];
                continue;
            }

            $methods[$pos] = $method;
            $pos++;
        }

        $definition->setMethodCalls($methods);
        $definition->addMethodCall('setTemplates', array($definedTemplates));
    }
}
