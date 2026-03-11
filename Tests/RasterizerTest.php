<?php

namespace Padam87\RasterizeBundle\Tests;

use Padam87\RasterizeBundle\ConfigHelper;
use Padam87\RasterizeBundle\Rasterizer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Stopwatch\Stopwatch;

class RasterizerTest extends TestCase
{
    private $configHelper;
    private $stopwatch;
    private $process;

    public function setUp(): void
    {
        $this->configHelper = $this->createMock(ConfigHelper::class);
        $this->stopwatch = $this->createMock(Stopwatch::class);
        $this->process = $this->createMock(Process::class);
    }


    #[Test]
    public function testRasterize(): void
    {
        $this->stopwatch->expects($this->once())->method('start');
        $this->stopwatch->expects($this->once())->method('stop');

        $this->configHelper->expects($this->once())->method('buildProcess')->willReturn($this->process);

        $this->process->expects($this->any())->method('start');
        $this->process->expects($this->any())->method('wait');
        $this->process->expects($this->any())->method('getOutput')->willReturn('pdfcontent');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);

        $this->assertSame('pdfcontent', $rasterizer->rasterize('<html></html>'));
    }

    #[Test]
    public function testCallback(): void
    {
        $this->stopwatch->expects($this->once())->method('start');
        $this->stopwatch->expects($this->once())->method('stop');

        $this->configHelper->expects($this->once())->method('buildProcess')->willReturn($this->process);

        $this->process->expects($this->any())->method('start');
        $this->process->expects($this->any())->method('wait');
        $this->process->expects($this->any())->method('setTimeout');
        $this->process->expects($this->any())->method('getOutput')->willReturn('pdfcontent');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);
        $output = $rasterizer->rasterize('<html></html>', [], [], function (Process $process) {
            $process->setTimeout(999);
        });

        $this->assertSame('pdfcontent', $output);
    }
}
