<?php

namespace Padam87\RasterizeBundle\Tests;

use Mockery as m;
use Mockery\MockInterface;
use Padam87\RasterizeBundle\ConfigHelper;
use Padam87\RasterizeBundle\Rasterizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Stopwatch\Stopwatch;

class RasterizerTest extends TestCase
{
    private $configHelper;
    private $stopwatch;
    private $process;

    protected function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        $this->configHelper = m::mock(ConfigHelper::class);
        $this->stopwatch = m::mock(Stopwatch::class);
        $this->process = m::mock(Process::class);
    }

    /**
     * @test
     */
    public function testRasterize()
    {
        $this->stopwatch->shouldReceive('start')->once();
        $this->stopwatch->shouldReceive('stop')->once();

        $this->configHelper->shouldReceive('buildProcess')->once()->andReturn($this->process);

        $this->process->shouldReceive('start');
        $this->process->shouldReceive('wait');
        $this->process->shouldReceive('getOutput')->andReturn('pdfcontent');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);

        $this->assertSame('pdfcontent', $rasterizer->rasterize('<html></html>'));
    }

    /**
     * @test
     */
    public function testCallback()
    {
        $this->stopwatch->shouldReceive('start')->once();
        $this->stopwatch->shouldReceive('stop')->once();

        $this->configHelper->shouldReceive('buildProcess')->once()->andReturn($this->process);

        $this->process->shouldReceive('start');
        $this->process->shouldReceive('wait');
        $this->process->shouldReceive('setTimeout');
        $this->process->shouldReceive('getOutput')->andReturn('pdfcontent');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);
        $output = $rasterizer->rasterize('<html></html>', [], [], function (Process $process) {
            $process->setTimeout(999);
        });

        $this->assertSame('pdfcontent', $output);
    }
}
