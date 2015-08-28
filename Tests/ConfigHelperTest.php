<?php

namespace Padam87\RasterizeBundle\Tests;

use Padam87\RasterizeBundle\ConfigHelper;
use Mockery as m;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Routing\RequestContext;

class ConfigHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $rootDir = '/';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    protected function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        $this->config       = array(
            'web_dir' => '/../web',
            'temp_dir' => '/bundles/padam87rasterize/temp',
            'phantomjs' => array(
                'callable' => 'phantomjs',
                'options' => array(),
            ),
            'script' => '/bundles/padam87rasterize/js/rasterize.js',
            'arguments' => array(
                'format' => 'pdf',
            ),
        );

        $this->configHelper = new ConfigHelper($this->config, $this->rootDir, new RequestContext());
    }

    /**
     * @test
     */
    public function isInputFilePathCorrect()
    {
        $this->assertEquals(
            $this->normalizePath(
                $this->rootDir . '/../web/bundles/padam87rasterize/temp' . DIRECTORY_SEPARATOR . 'e4e5k2.html'
            ),
            $this->normalizePath($this->configHelper->getInputFilePath('e4e5k2'))
        );
    }

    /**
     * @test
     */
    public function isOutputFilePathCorrect()
    {
        $this->assertEquals(
            $this->normalizePath(
                $this->rootDir . '/../web/bundles/padam87rasterize/temp' . DIRECTORY_SEPARATOR .
                'e4e5k2.' . $this->config['arguments']['format']
            ),
            $this->normalizePath($this->configHelper->getOutputFilePath('e4e5k2'))
        );
    }

    /**
     * @test
     */
    public function isOutputFileUrlCorrect()
    {
        $this->assertEquals(
            'http://localhost/bundles/padam87rasterize/temp/e4e5k2.html',
            $this->configHelper->getOutputFileUrl('e4e5k2')
        );

        $this->configHelper->setContextBaseUrl('/project/web');

        $this->assertEquals(
            'http://localhost/project/web/bundles/padam87rasterize/temp/e4e5k2.html',
            $this->configHelper->getOutputFileUrl('e4e5k2')
        );
    }

    /**
     * @test
     */
    public function isTheProcessBuilt()
    {
        $this->assertInstanceOf(
            'Symfony\Component\Process\Process',
            $this->configHelper->buildProcess($this->configHelper->getOutputFileUrl('e4e5k2'), 'e4e5k2')
        );
    }

    /**
     * @test
     */
    public function arePhantomjsOptionsHandled()
    {
        $url = $this->configHelper->getOutputFileUrl('e4e5k2');

        $this->configHelper->setConfig(
            array_merge_recursive(
                $this->config,
                array(
                    'phantomjs' => array(
                        'options' => array(
                            '--ignore-ssl-errors' => true
                        )
                    )
                )
            )
        );

        $process = $this->configHelper->buildProcess($url, 'e4e5k2');

        $this->assertContains(
            ProcessUtils::escapeArgument(sprintf('%s=%s', '--ignore-ssl-errors', 'true')),
            $process->getCommandLine()
        );

        $this->configHelper->setConfig(
            array_merge_recursive(
                $this->config,
                array(
                    'phantomjs' => array(
                        'options' => array(
                            '--ignore-ssl-errors=true'
                        )
                    )
                )
            )
        );

        $process = $this->configHelper->buildProcess($url, 'e4e5k2');
        $command = $process->getCommandLine();

        $this->assertContains(
            ProcessUtils::escapeArgument('--ignore-ssl-errors=true'),
            $process->getCommandLine()
        );
        $this->assertNotContains('0', $command);
    }

    /**
     * @test
     */
    public function gettersAndSetters()
    {
        $this->assertSame($this->configHelper, $this->configHelper->setConfig(array()));
        $this->assertSame(array(), $this->configHelper->getConfig());

        $context = new RequestContext();
        $this->assertSame($this->configHelper, $this->configHelper->setContext($context));
        $this->assertSame($context, $this->configHelper->getContext());

        $this->assertSame($this->configHelper, $this->configHelper->setContextBaseUrl('www.example.com'));
        $this->assertSame('www.example.com', $this->configHelper->getContextBaseUrl());

        $this->assertSame($this->configHelper, $this->configHelper->setRootDir('/test'));
        $this->assertSame('/test', $this->configHelper->getRootDir());
    }

    protected function normalizePath($path)
    {
        $root = ($path[0] === '/') ? '/' : '';

        $segments = explode('/', trim($path, '/'));
        $ret = array();

        foreach($segments as $segment){
            if (($segment == '.') || empty($segment)) {
                continue;
            }
            if ($segment == '..') {
                array_pop($ret);
            } else {
                array_push($ret, $segment);
            }
        }

        return $root . implode('/', $ret);
    }
}
