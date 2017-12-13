<?php

namespace Padam87\RasterizeBundle\Tests;

use Mockery as m;
use Mockery\MockInterface;
use Padam87\RasterizeBundle\Rasterizer;

class RasterizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface
     */
    protected $configHelper;

    /**
     * @var MockInterface
     */
    protected $stopwatch;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $process;

    protected function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        $this->configHelper = m::mock('Padam87\RasterizeBundle\ConfigHelper');
        $this->stopwatch = m::mock('Symfony\Component\Stopwatch\Stopwatch');
        $this->process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock()
        ; // Mock process with PHPUnit, mockery has a bug here @see: https://github.com/padraic/mockery/issues/355
    }

    /**
     * @test
     */
    public function testRasterize()
    {
        $this->stopwatch->shouldReceive('start')->once();
        $this->stopwatch->shouldReceive('stop')->once();

        $this->configHelper->shouldReceive('buildProcess')->once()->andReturn($this->process);

        $this->process->expects($this->once())->method('start');
        $this->process->expects($this->once())->method('wait');
        $this->process->expects($this->once())->method('getOutput')->willReturn('pdfcontent');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch, 'test');

        $this->assertSame('pdfcontent', $rasterizer->rasterize('<html></html>'));
    }
}
