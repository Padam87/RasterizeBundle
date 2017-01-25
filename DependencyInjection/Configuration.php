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
                ->arrayNode('phantomjs')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('callable')->defaultValue('phantomjs')->end()
                        ->arrayNode('options')
                            ->defaultValue(
                                [
                                    '--output-encoding' => $this->isWin() ? 'ISO-8859-1' : 'UTF-8',
                                ]
                            )
                            ->normalizeKeys(false)
                            ->beforeNormalization()
                                ->ifTrue(function ($v) {
                                    return !isset($v['--output-encoding']);
                                })
                                ->then(function ($v) {
                                    return array_merge(
                                        [
                                            '--output-encoding' => $this->isWin() ? 'ISO-8859-1' : 'UTF-8',
                                        ],
                                        $v
                                    );
                            })
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('script')
                    ->defaultValue('../web/bundles/padam87rasterize/js/rasterize.js')->info('Relative to root dir')
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
            ->end()
        ;

        return $treeBuilder;
    }

    private function isWin()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
