<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Rasterizer
 *
 * @package Padam87\RasterizeBundle
 */
class Rasterizer
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * @var string
     */
    protected $contextBaseUrl;

    /**
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * @param array                                     $config
     * @param string                                    $rootDir
     * @param \Symfony\Component\Routing\RequestContext $context
     * @param string                                    $contextBaseUrl
     * @param \Symfony\Component\Stopwatch\Stopwatch    $stopwatch
     */
    public function __construct(array $config, $rootDir, RequestContext $context,
        $contextBaseUrl, Stopwatch $stopwatch = null)
    {
        $this->config         = $config;
        $this->rootDir        = $rootDir;
        $this->context        = $context;
        $this->contextBaseUrl = $contextBaseUrl;
        $this->stopwatch      = $stopwatch;
    }

    /**
     * @param string $html
     * @param array  $arguments
     * @param bool   $uniqueId
     *
     * @return string
     */
    public function rasterize($html, $arguments = array(), $uniqueId = false)
    {
        if (!$uniqueId) {
            $uniqueId = uniqid("rasterize-");
        }

        if ($this->stopwatch instanceof Stopwatch) {
            $this->stopwatch->start($uniqueId);
        }

        $input  = $this->rootDir . $this->config['web_dir'] .
            $this->config['temp_dir'] . DIRECTORY_SEPARATOR . $uniqueId . '.html';

        $fh = fopen($input, 'w');
        fwrite($fh, $html);
        fclose($fh);

        $url = sprintf(
            "%s://%s%s/%s.html",
            $this->context->getScheme(),
            $this->context->getHost(),
            $this->contextBaseUrl === ""
                ? $this->context->getBaseUrl()
                : $this->contextBaseUrl,
            $this->config['temp_dir'] . '/' . $uniqueId
        );

        $output = $this->rasterizeUrl($url, $arguments, $uniqueId);

        unlink($input);

        return $output;
    }

    /**
     * @param string $url
     * @param array  $arguments
     * @param bool   $uniqueId
     *
     * @return string
     * @throws \Exception
     */
    public function rasterizeUrl($url, $arguments = array(), $uniqueId = false)
    {
        if (!$uniqueId) {
            $uniqueId = uniqid("rasterize-");
        }

        if ($this->stopwatch instanceof Stopwatch) {
            if ($this->stopwatch->isStarted($uniqueId)) {
                $this->stopwatch->lap($uniqueId);
            } else {
                $this->stopwatch->start($uniqueId);
            }
        }

        $script = $this->rootDir . $this->config['web_dir'] . DIRECTORY_SEPARATOR . $this->config['script'];
        $output  = $this->rootDir . $this->config['web_dir'] .
            $this->config['temp_dir'] . DIRECTORY_SEPARATOR . $uniqueId . '.' . $this->config['arguments']['format'];

        $builder = new ProcessBuilder();
        $options = array();

        foreach ($this->config['phantomjs']['options'] as $option) {
            $options[] = $option;
        }

        $builder
            ->setPrefix($this->config['phantomjs']['callable'])
            ->setArguments(
                array_merge(
                    $options,
                    array($script, $url, $output),
                    array_values(array_merge($this->config['arguments'], $arguments))
                )
            )
        ;

        $process = $builder->getProcess();
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

        $content = file_get_contents($output);

        unlink($output);

        return $content;
    }
}
