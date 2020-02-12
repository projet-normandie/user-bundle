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
     * {@inheritdoc}
     * @throws \RuntimeException When the node type is not supported.
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('projet_normandie_user');
        $rootNode
            ->children()
            ->arrayNode('directory')
            ->children()
            ->scalarNode('avatar')->defaultValue(null)->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
