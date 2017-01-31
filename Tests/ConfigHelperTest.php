<?php

namespace Padam87\RasterizeBundle\Tests;

use Padam87\RasterizeBundle\ConfigHelper;
use Mockery as m;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

class ConfigHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $config;

    protected function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        $this->config       = array(
            'phantomjs' => array(
                'callable' => 'phantomjs',
                'options' => array(),
            ),
            'script' => '/bundles/padam87rasterize/js/rasterize.js',
            'arguments' => array(
                'format' => 'pdf',
            ),
        );
    }

    /**
     * @test
     */
    public function isTheProcessBuilt()
    {
        $configHelper = new ConfigHelper($this->config);
        $process = $configHelper->buildProcess(new InputStream());

        $this->assertInstanceOf(Process::class, $process);
        $this->assertEquals('"phantomjs" "/bundles/padam87rasterize/js/rasterize.js" "pdf"', $process->getCommandLine());
    }

    /**
     * @test
     */
    public function arePhantomjsOptionsHandled()
    {
        $this->config['phantomjs']['options'] = [
            '--ignore-ssl-errors' => true
        ];

        $configHelper = new ConfigHelper($this->config);

        $process = $configHelper->buildProcess(new InputStream());

        $this->assertContains(
            ProcessUtils::escapeArgument(sprintf('%s=%s', '--ignore-ssl-errors', 'true')),
            $process->getCommandLine()
        );
        $this->assertNotContains('0', $process->getCommandLine());
    }
}
