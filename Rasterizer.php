<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Stopwatch\Stopwatch;

class Rasterizer
{
    protected ConfigHelper $configHelper;
    protected ?Stopwatch $stopwatch;

    public function __construct(ConfigHelper $configHelper, Stopwatch $stopwatch = null)
    {
        $this->configHelper = $configHelper;
        $this->stopwatch = $stopwatch;
    }

    public function rasterize(string $html, array $arguments = [], array $env = [], callable $callback = null): string
    {
        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start('rasterizer');
        }

        $input = new InputStream();

        $process = $this->configHelper->buildProcess($input, $arguments, $env);

        if ($callback) {
            $callback($process);
        }

        $process->start(null, []);

        $input->write($html);
        $input->close();

        $process->wait();

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->stop('rasterizer');
        }

        return $process->getOutput();
    }
}
