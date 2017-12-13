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
        $this->config = [
            'script' => [
                'callable' => 'node',
                'path' => 'assets' . DIRECTORY_SEPARATOR . 'rasterize.js',
            ],
            'arguments' => [
                'format' => 'pdf'
            ]
        ];
    }

    /**
     * @test
     */
    public function isTheProcessBuilt()
    {
        $configHelper = new ConfigHelper(__DIR__, $this->config);
        $process = $configHelper->buildProcess(new InputStream());

        $this->assertInstanceOf(Process::class, $process);

        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, ['node ' . __DIR__, 'assets', 'rasterize.js']) . ' pdf',
            str_replace(['"', "'"], '', $process->getCommandLine())
        );
    }
}
