<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Stopwatch\Stopwatch;

class Rasterizer
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @param ConfigHelper $configHelper
     * @param Stopwatch    $stopwatch
     */
    public function __construct(ConfigHelper $configHelper, Stopwatch $stopwatch = null)
    {
        $this->configHelper = $configHelper;
        $this->stopwatch    = $stopwatch;
    }

    /**
     * @param string $html
     * @param array  $arguments
     *
     * @return string
     */
    public function rasterize($html, $arguments = array())
    {
        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start('rasterizer');
        }

        $input = new InputStream();

        $process = $this->configHelper->buildProcess($input, $arguments);
        $process->start();

        $input->write($html);
        $input->close();

        $process->wait();

        return $process->getOutput();
    }
}
