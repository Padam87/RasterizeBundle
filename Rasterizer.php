<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Stopwatch\Stopwatch;

class Rasterizer
{
    protected $configHelper;
    protected $stopwatch;

    public function __construct(ConfigHelper $configHelper, Stopwatch $stopwatch = null)
    {
        $this->configHelper = $configHelper;
        $this->stopwatch = $stopwatch;
    }

    public function rasterize(string $html, $arguments = []): string
    {
        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start('rasterizer');
        }

        $input = new InputStream();

        $process = $this->configHelper->buildProcess($input, $arguments);
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
