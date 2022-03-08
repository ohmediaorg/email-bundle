<?php

namespace OHMedia\EmailBundle\DependencyInjection;

use OHMedia\EmailBundle\Util\EmailAddress;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OHMediaEmailExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('oh_media_email.cleanup', $config['cleanup']);

        $container->setParameter('oh_media_email.from_email', $config['from']['email']);

        $container->setParameter('oh_media_email.from_name', $config['from']['name']);

        $container->setParameter('oh_media_email.subject_prefix', $config['subject_prefix']);
    }
}
