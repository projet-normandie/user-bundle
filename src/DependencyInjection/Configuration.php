<?php

namespace ProjetNormandie\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('projet_normandie_user');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('register')
                    ->children()
                        ->scalarNode('uri_confirmation')->defaultValue(null)->end()
                    ->end()
                ->end()//register
            ->end();
        return $treeBuilder;
    }
}
