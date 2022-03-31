<?php

namespace Padam87\RasterizeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('padam87_rasterize');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('script')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('callable')->defaultValue('node')->end()
                        ->scalarNode('path')
                            ->defaultValue('assets' . DIRECTORY_SEPARATOR . 'rasterize.js')
                            ->info('Relative to project dir')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('arguments')
                    ->defaultValue(
                        [
                            'format' => 'pdf'
                        ]
                    )
                    ->beforeNormalization()
                        ->ifTrue(function ($v) {
                            return !isset($v['format']);
                        })
                        ->then(function ($v) {
                            return array_merge(['format' => 'pdf'], $v);
                        })
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('env_vars')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
