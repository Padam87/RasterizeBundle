<?php

namespace Padam87\RasterizeBundle\Tests;

use Padam87\RasterizeBundle\ConfigHelper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class ConfigHelperTest extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    public function setUp(): void
    {
        $this->config = [
            'script' => [
                'callable' => 'node',
                'path' => 'assets' . DIRECTORY_SEPARATOR . 'rasterize.js',
            ],
            'arguments' => [
                'format' => 'pdf'
            ],
            'env_vars' => [
                'NODE_PATH' => '/usr/local/lib/node_modules',
            ]
        ];
    }

    #[Test]
    public function isTheProcessBuilt(): void
    {
        $configHelper = new ConfigHelper(__DIR__, $this->config);
        $process = $configHelper->buildProcess(new InputStream());

        $this->assertInstanceOf(Process::class, $process);

        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, ['node ' . __DIR__, 'assets', 'rasterize.js']) . ' pdf',
            str_replace(['"', "'"], '', $process->getCommandLine())
        );

        $this->assertCount(1, $process->getEnv());
    }

    #[Test]
    public function attributeMerge(): void
    {
        $configHelper = new ConfigHelper(__DIR__, $this->config);
        $process = $configHelper->buildProcess(new InputStream(), ['paper' => 'A4']);

        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, ['node ' . __DIR__, 'assets', 'rasterize.js']) . ' pdf A4',
            str_replace(['"', "'"], '', $process->getCommandLine())
        );
    }

    #[Test]
    public function envMerge(): void
    {
        $configHelper = new ConfigHelper(__DIR__, $this->config);
        $process = $configHelper->buildProcess(new InputStream(), [], ['MY_ENV' => 'something']);

        $this->assertCount(2, $process->getEnv());
    }
}
