<?php

namespace Padam87\RasterizeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('padam87_rasterize');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('script')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('callable')->defaultValue('node')->end()
                        ->scalarNode('path')
                            ->defaultValue($this->getAssetsDir() . DIRECTORY_SEPARATOR . 'rasterize.js')
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

    private function getAssetsDir()
    {
        switch (Kernel::MAJOR_VERSION) {
            case 4:
                return 'assets';
            default:
                return 'web';
        }
    }
}
