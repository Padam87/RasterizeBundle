<?php

namespace Padam87\RasterizeBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Process\ProcessUtils;

class ConfigHelperTest extends WebTestCase
{
    /**
     * @var \Padam87\RasterizeBundle\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
        parent::createClient();

        $this->configHelper = self::$kernel->getContainer()->get('padam87_rasterize.config_helper');
        $this->config       = self::$kernel->getContainer()->getParameter('padam87_rasterize.config');
    }

    /**
     * @test
     */
    public function isServiceRegisteredCorrectly()
    {
        $this->assertInstanceOf('Padam87\RasterizeBundle\ConfigHelper', $this->configHelper);
    }

    /**
     * @test
     */
    public function isInputFilePathCorrect()
    {
        $this->assertEquals(
            $this->normalizePath(
                self::$kernel->getRootDir() . '/../web/bundles/padam87rasterize/temp' . DIRECTORY_SEPARATOR . 'e4e5k2.html'
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
                self::$kernel->getRootDir() . '/../web/bundles/padam87rasterize/temp' . DIRECTORY_SEPARATOR .
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
            ProcessUtils::escapeArgument(sprintf('%s="%s"', '--ignore-ssl-errors', 'true')),
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
