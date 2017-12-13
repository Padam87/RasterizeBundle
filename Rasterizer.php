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
     * @var array
     */
    protected $environment;

    /**
     * @param ConfigHelper $configHelper
     * @param Stopwatch    $stopwatch
     * @param string       $environment
     */
    public function __construct(ConfigHelper $configHelper, Stopwatch $stopwatch = null, $environment)
    {
        $this->configHelper = $configHelper;
        $this->stopwatch = $stopwatch;
        $this->environment = [ $environment ];
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
        $process->start(null, $this->environment);

        $input->write($html);
        $input->close();

        $process->wait();

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->stop('rasterizer');
        }

        return $process->getOutput();
    }
}
