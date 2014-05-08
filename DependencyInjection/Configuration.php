<?php

namespace Padam87\RasterizeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('padam87_rasterize');

        $rootNode
            ->children()
                ->scalarNode('web_dir')
                    ->defaultValue('/../web')
                    ->info('Temp dir location related to %kernel.root_dir%.')
                ->end()
                ->scalarNode('temp_dir')
                    ->defaultValue('/bundles/padam87rasterize/temp')
                    ->info('Temp dir location related to web dir. Must be in a location accessible by the web server.')
                ->end()
                ->arrayNode('phantomjs')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('callable')->defaultValue('phantomjs')->end()
                        ->arrayNode('options')
                            ->info("https://github.com/ariya/phantomjs/wiki/API-Reference#wiki-command-line-options")
                            ->normalizeKeys(false)
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('script')
                    ->defaultValue('/bundles/padam87rasterize/js/rasterize.js')->info('Relative to web dir')
                ->end()
                ->arrayNode('arguments')
                    ->defaultValue(array(
                        'format' => 'pdf'
                    ))
                    ->beforeNormalization()
                        ->ifTrue(function ($v) {
                            return !isset($v['format']);
                        })
                        ->then(function ($v) {
                            return array_merge(array('format' => 'pdf'), $v);
                        })
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
