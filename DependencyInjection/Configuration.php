<?php
namespace Vilks\FileSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vilks_file_search');

        $rootNode
            ->children()
                ->scalarNode('engine')->defaultValue('php_read')->end()
            ->end();

        return $treeBuilder;
    }
}
