<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // SYMFONY STANDARD EDITION
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // DOCTRINE
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),


            // KNP HELPER BUNDLES
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),

            // USER
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Rz\UserBundle\RzUserBundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),

            // PAGE
            new Sonata\PageBundle\SonataPageBundle(),
            new Rz\PageBundle\RzPageBundle(),
            new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),

            // NEWS
            new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Sonata\NewsBundle\SonataNewsBundle(),
            new Rz\NewsBundle\RzNewsBundle(),
            new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),

            // MEDIA
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Rz\MediaBundle\RzMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),

            // SONATA CORE & HELPER BUNDLES
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Rz\ClassificationBundle\RzClassificationBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),

            // Enable this if you want to audit backend action
            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            //CMF Router
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            //FOS
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new FOS\RestBundle\FOSRestBundle(),

            //JMS
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new JMS\CommandBundle\JMSCommandBundle(),

            new Liip\MonitorBundle\LiipMonitorBundle(),

            //Stof
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            //HWI
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

            //RZ & RMZAMORA bundles
            new Rmzamora\SandboxInitDataBundle\RmzamoraSandboxInitDataBundle(),
            new Rmzamora\BootstrapBundle\RmzamoraBootstrapBundle(),
            new Rmzamora\JqueryBundle\RmzamoraJqueryBundle(),
            new Rz\CkeditorBundle\RzCkeditorBundle(),
            new Rz\CodemirrorBundle\RzCodemirrorBundle(),
            new Rz\AdminBundle\RzAdminBundle(),
            new Rz\BlockBundle\RzBlockBundle(),
            new Rz\DoctrineORMAdminBundle\RzDoctrineORMAdminBundle(),
            new Rz\FieldTypeBundle\RzFieldTypeBundle(),
            new Rz\FormatterBundle\RzFormatterBundle(),
            new Rz\OAuthBundle\RzOAuthBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
