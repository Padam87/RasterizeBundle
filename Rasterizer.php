<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Rasterizer
 *
 * @package Padam87\RasterizeBundle
 */
class Rasterizer
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * @param ConfigHelper                              $configHelper
     * @param \Symfony\Component\Stopwatch\Stopwatch    $stopwatch
     */
    public function __construct(ConfigHelper $configHelper, Stopwatch $stopwatch = null)
    {
        $this->configHelper = $configHelper;
        $this->stopwatch    = $stopwatch;
    }

    /**
     * @param string $html
     * @param array  $arguments
     * @param string $uniqueId
     *
     * @return string
     */
    public function rasterize($html, $arguments = array(), $uniqueId = "")
    {
        if ($uniqueId === "") {
            $uniqueId = uniqid("rasterize-");
        }

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start($uniqueId);
        }

        $input = $this->configHelper->getInputFilePath($uniqueId);

        $fh = fopen($input, 'w');
        fwrite($fh, $html);
        fclose($fh);

        $output = $this->rasterizeUrl($this->configHelper->getOutputFileUrl($uniqueId), $arguments, $uniqueId);

        unlink($input);

        return $output;
    }

    /**
     * @param        $url
     * @param array  $arguments
     * @param string $uniqueId
     *
     * @throws \Exception
     *
     * @return string
     */
    public function rasterizeUrl($url, $arguments = array(), $uniqueId = "")
    {
        if ($uniqueId === "") {
            $uniqueId = uniqid("rasterize-");
        }

        if ($this->stopwatch instanceof Stopwatch) {
            if ($this->stopwatch->isStarted($uniqueId)) {
                $this->stopwatch->lap($uniqueId);
            } else {
                $this->stopwatch->start($uniqueId);
            }
        }

        $process = $this->configHelper->buildProcess($url, $uniqueId, $arguments);
        $exitCode = $process->run();

        if ($exitCode != 0) {
            throw new \Exception(sprintf(
                "Rasterize script failed.\nCommandLine: %s\nExitCode: %d\nErrorOutput: %s",
                $process->getCommandLine(),
                $process->getExitCode(),
                $process->getErrorOutput()
            ));
        }

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->stop($uniqueId);
        }

        $output  = $this->configHelper->getOutputFilePath($uniqueId);
        $content = file_get_contents($output);

        unlink($output);

        return $content;
    }
}
