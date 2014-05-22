<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Routing\RequestContext;

class ConfigHelper
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
     * @param array                                     $config
     * @param string                                    $rootDir
     * @param \Symfony\Component\Routing\RequestContext $context
     * @param string                                    $contextBaseUrl
     */
    public function __construct(
        array $config,
        $rootDir,
        RequestContext $context,
        $contextBaseUrl
    ) {
        $this->config         = $config;
        $this->rootDir        = $rootDir;
        $this->context        = $context;
        $this->contextBaseUrl = $contextBaseUrl;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return \Symfony\Component\Routing\RequestContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContextBaseUrl()
    {
        return $this->contextBaseUrl;
    }

    /**
     * @param string $contextBaseUrl
     */
    public function setContextBaseUrl($contextBaseUrl)
    {
        $this->contextBaseUrl = $contextBaseUrl;
    }

    /**
     * @param        $url
     * @param string $uniqueId
     * @param array  $arguments
     *
     * @return \Symfony\Component\Process\Process
     */
    public function buildProcess($url, $uniqueId, $arguments = array())
    {
        $script = $this->getWebDir() . DIRECTORY_SEPARATOR . $this->config['script'];
        $output = $this->getOutputFilePath($uniqueId);

        $builder = new ProcessBuilder();

        $builder
            ->setPrefix($this->config['phantomjs']['callable'])
            ->setArguments(
                array_merge(
                    $this->processPhantomjsOptions(),
                    array($script, $url, $output),
                    array_values(array_merge($this->config['arguments'], $arguments))
                )
            )
        ;

        return $builder->getProcess();
    }

    /**
     * @param string $uniqueId
     *
     * @return string
     */
    public function getInputFilePath($uniqueId)
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . $uniqueId . '.html';
    }

    /**
     * @param string $uniqueId
     *
     * @return string
     */
    public function getOutputFilePath($uniqueId)
    {
        return $this->getTempDir() . DIRECTORY_SEPARATOR . $uniqueId . '.' . $this->config['arguments']['format'];
    }

    /**
     * @param $uniqueId
     *
     * @return string
     */
    public function getOutputFileUrl($uniqueId)
    {
        return sprintf(
            "%s://%s%s%s/%s.html",
            $this->context->getScheme(),
            $this->context->getHost(),
            $this->contextBaseUrl === "" ? $this->context->getBaseUrl() : $this->contextBaseUrl,
            $this->config['temp_dir'],
            $uniqueId
        );
    }

    /**
     * @return string
     */
    protected function getTempDir()
    {
        return $this->getWebDir() . $this->config['temp_dir'];
    }

    /**
     * @return string
     */
    protected function getWebDir()
    {
        return $this->rootDir . $this->config['web_dir'];
    }

    /**
     * @return array
     */
    protected function processPhantomjsOptions()
    {
        $options = array();

        foreach ($this->config['phantomjs']['options'] as $name => $value) {
            if (is_numeric($name)) {
                $options[] = $value;
            } else {
                if (is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                }
                $options[] = sprintf('%s="%s"', $name, $value);
            }
        }

        return $options;
    }
}
