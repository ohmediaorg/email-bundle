<?php

namespace OHMedia\EmailBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OHMediaEmailBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('cleanup')
                    ->defaultValue('-1 year')
                ->end()
                ->arrayNode('from')
                    ->isRequired()
                    ->children()
                      ->scalarNode('email')
                          ->isRequired()
                      ->end()
                      ->scalarNode('name')
                          ->isRequired()
                      ->end()
                  ->end()
                ->end()
                ->scalarNode('subject_prefix')->end()
                ->scalarNode('header_background')
                    ->defaultValue('#ff5b16')
                ->end()
                ->scalarNode('link_color')
                    ->defaultValue('#ff5b16')
                ->end()
            ->end()
        ;
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $containerConfigurator,
        ContainerBuilder $containerBuilder
    ): void {
        $containerConfigurator->import('../config/services.yaml');

        $containerConfigurator->parameters()
            ->set('oh_media_email.cleanup', $config['cleanup'])
            ->set('oh_media_email.from_email', $config['from']['email'])
            ->set('oh_media_email.from_name', $config['from']['name'])
            ->set('oh_media_email.subject_prefix', $config['subject_prefix'])
            ->set('oh_media_email.header_background', $config['header_background'])
            ->set('oh_media_email.link_color', $config['link_color'])
        ;
    }
}
