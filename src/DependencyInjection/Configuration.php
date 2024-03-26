<?php

namespace OHMedia\EmailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('oh_media_email');

        $treeBuilder->getRootNode()
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

        return $treeBuilder;
    }
}
