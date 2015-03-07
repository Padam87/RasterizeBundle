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
    public function testRasterizeUrl()
    {
        $output = sys_get_temp_dir() . '/ID.pdf';

        $this->stopwatch->shouldReceive('isStarted')->once()->andReturn(false);
        $this->stopwatch->shouldReceive('start')->once();
        $this->stopwatch->shouldReceive('stop')->once();

        $this->configHelper
            ->shouldReceive('buildProcess')->once()->andReturn($this->process)
        ;
        $this->configHelper->shouldReceive('getOutputFilePath')->once()->andReturn($output);

        $this->process->expects($this->any())->method('run');
        file_put_contents($output, 'content');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);
        $content = $rasterizer->rasterizeUrl('test');

        $this->assertSame('content', $content);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function testRasterizeUrlScriptFailed()
    {
        $this->stopwatch->shouldReceive('isStarted')->once()->andReturn(false);
        $this->stopwatch->shouldReceive('start')->once();

        $this->configHelper
            ->shouldReceive('buildProcess')->once()->andReturn($this->process)
        ;

        $this->process->expects($this->once())->method('run')->willReturn(1);
        $this->process->expects($this->once())->method('getCommandLine');
        $this->process->expects($this->once())->method('getExitCode');
        $this->process->expects($this->once())->method('getErrorOutput');

        $this->assertInstanceOf('Symfony\Component\Process\Process', $this->process);

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);
        $rasterizer->rasterizeUrl('test');
    }

    /**
     * @test
     */
    public function testRasterize()
    {
        $input = sys_get_temp_dir() . '/ID.html';
        $output = sys_get_temp_dir() . '/ID.pdf';

        $this->stopwatch->shouldReceive('isStarted')->once()->andReturn(true);
        $this->stopwatch->shouldReceive('start')->once();
        $this->stopwatch->shouldReceive('lap')->once();
        $this->stopwatch->shouldReceive('stop')->once();

        $this->configHelper->shouldReceive('getInputFilePath')->once()->andReturn($input);
        $this->configHelper
            ->shouldReceive('buildProcess')->once()->andReturn($this->process)
        ;
        $this->configHelper->shouldReceive('getOutputFilePath')->once()->andReturn($output);
        $this->configHelper->shouldReceive('getOutputFileUrl')->once()->andReturn('test');

        $this->process->expects($this->once())->method('run');
        file_put_contents($output, 'content');

        $rasterizer = new Rasterizer($this->configHelper, $this->stopwatch);
        $content = $rasterizer->rasterize('test');

        $this->assertSame('content', $content);
    }
}
